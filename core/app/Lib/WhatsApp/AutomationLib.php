<?php

namespace App\Lib\WhatsApp;

use App\Constants\Status;
use App\Events\ReceiveMessage;
use App\Models\ContactFlowState;
use App\Models\CtaUrl;
use App\Models\FlowEdge;
use App\Models\FlowNode;
use App\Models\InteractiveList;
use App\Models\Message;
use App\Models\Template;
use App\Models\WhatsappAccount;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AutomationLib
{
    /**
     * The canvas id of the trigger node. The builder always gives it this fixed id and
     * never saves it as a flow_nodes row, so a flow really starts at whatever the
     * trigger's edge points to.
     */
    private const TRIGGER_NODE_ID = '1';

    /** Nodes that ask the contact a question: the flow stops there and waits for the answer. */
    private const WAITING_NODE_TYPES = [Status::NODE_TYPE_BUTTON, Status::NODE_TYPE_LIST];

    /** How many nodes of the current run actually reached the contact. */
    private int $delivered = 0;

    public function process($user, $flow, $lastState = null, $conversation, $message = null)
    {
        // Run the flow against the account it was built for. Falls back to the default
        // account for flows saved before whatsapp_account_id was populated.
        $whatsappAccount = null;

        if (@$flow->whatsapp_account_id) {
            $whatsappAccount = WhatsappAccount::where('user_id', $user->id)->find($flow->whatsapp_account_id);
        }

        if (!$whatsappAccount) {
            $whatsappAccount = $user->currentWhatsapp();
        }

        if (!$flow || !$conversation || !$message || !$user || !$whatsappAccount) return;

        $startNode = ($lastState && $lastState->status == Status::FLOW_STATE_WAITING)
            ? $this->resumeNode($flow, $lastState, $message)
            : $this->entryNode($flow);

        if (!$startNode) {
            return;
        }

        $this->delivered = 0;

        $visited = [];
        $this->traverse($whatsappAccount, $conversation, $startNode, $visited);

        // The contact answered, but the branch behind that answer could deliver nothing -
        // a template that was deleted, a button node saved with no buttons. Leaving the
        // state on WAITING would pin them to a question they have already answered: every
        // later message would walk back into the same dead branch, and even the flow's own
        // keyword would stop working for them.
        if (!$this->delivered && $lastState && $lastState->status == Status::FLOW_STATE_WAITING) {
            $lastState->status = Status::FLOW_STATE_SENT;
            $lastState->save();
        }
    }

    /** The node the trigger is wired to, which is not necessarily the first node saved. */
    private function entryNode($flow)
    {
        $edge = $this->outgoingEdges($flow->id, self::TRIGGER_NODE_ID)->first();

        if ($edge && $edge->targetNode) {
            return $edge->targetNode;
        }

        // Flows saved before the trigger edge was persisted have no edge from "1"; those
        // really did start at the first saved node, so keep them working.
        return $flow->nodes->first();
    }

    /** Where to carry on after the contact answered the question the flow paused on. */
    private function resumeNode($flow, $lastState, $message)
    {
        $lastNode = FlowNode::where('flow_id', $lastState->flow_id)
            ->where('node_id', $lastState->current_node_id)
            ->first();

        if (!$lastNode) return null;

        $edges = $this->outgoingEdges($flow->id, $lastNode->node_id);

        if ($lastNode->type == Status::NODE_TYPE_BUTTON) {
            $clickedIndex = $this->matchButtonIndex($lastNode, $message);

            if ($clickedIndex === null) return null;

            $edge = $edges->first(fn($edge) => (int) $edge->button_index === $clickedIndex);

            return $edge?->targetNode;
        }

        // A list has a single outgoing branch: every row continues down the same path.
        return $edges->first()?->targetNode;
    }

    /**
     * Which button was pressed. Replies carry back the id we sent, which is positional,
     * so two buttons sharing a label still resolve to different branches. Buttons sent
     * by older releases carried the label as the id, and a quick reply on a template
     * carries only the label, so fall back to matching on the text.
     */
    private function matchButtonIndex($node, $message)
    {
        $message = trim((string) $message);

        if (preg_match('/^btn_(\d+)$/', $message, $match)) {
            return (int) $match[1];
        }

        foreach ($this->buttonData($node)['buttons'] ?? [] as $index => $button) {
            if (strcasecmp(trim($button['text'] ?? ''), $message) === 0) {
                return (int) $index;
            }
        }

        return null;
    }

    /**
     * The builder json_encode()s the button payload into a column that is itself cast to
     * array, so the accessor hands back a string on rows saved that way and an array on
     * rows saved any other way.
     */
    private function buttonData($node)
    {
        $data = $node->buttons_json;

        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        return is_array($data) ? $data : [];
    }

    private function outgoingEdges($flowId, $sourceNodeId)
    {
        return FlowEdge::where('flow_id', $flowId)
            ->where('source_node_id', $sourceNodeId)
            ->with('targetNode')
            ->orderBy('id')
            ->get();
    }

    /**
     * Walks the flow from $node. Returns true once a node has asked the contact
     * something, which stops every remaining branch: the bot must not keep talking over
     * a question it has just asked.
     */
    private function traverse($whatsappAccount, $conversation, $node, array &$visited)
    {
        // A loop on the canvas would otherwise send messages until the request dies.
        if (!$node || isset($visited[$node->node_id])) return false;

        $visited[$node->node_id] = true;

        $sent = $this->sendMessageAndTrack($whatsappAccount, $conversation, $node);

        if ($sent) {
            $this->delivered++;
        }

        if (in_array($node->type, self::WAITING_NODE_TYPES)) {
            // The branches below a question are its answers, so they never run here either
            // way. But only a question the contact actually received puts the flow on hold:
            // reporting a wait for a question nobody was asked would silently drop whatever
            // else the node was wired to.
            return $sent;
        }

        foreach ($this->outgoingEdges($node->flow_id, $node->node_id) as $edge) {
            if ($this->traverse($whatsappAccount, $conversation, $edge->targetNode, $visited)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns whether the node reached the contact and left a state row behind.
     */
    private function sendMessageAndTrack($whatsappAccount, $conversation, $node)
    {
        try {
            return $this->sendAndRecord($whatsappAccount, $conversation, $node);
        } catch (Exception $ex) {
            // One failing node - a rejected send, an expired token, an opted-out contact -
            // must not abandon the rest of the flow.
            Log::error('Flow ' . $node->flow_id . ' node ' . $node->node_id . ': ' . $ex->getMessage());

            return false;
        }
    }

    private function sendAndRecord($whatsappAccount, $conversation, $node)
    {
        $messageSend = $this->sendNode($whatsappAccount, $conversation, $node);

        if (!$messageSend || !isset($messageSend['whatsAppMessage'][0]['id'])) return false;

        // Each send method returns a different subset of these keys, so fill the gaps
        // instead of reading undefined variables out of the result.
        $sent = array_merge([
            'ctaUrlId'          => 0,
            'interactiveListId' => 0,
            'mediaId'           => null,
            'mediaUrl'          => null,
            'mediaPath'         => null,
            'mediaCaption'      => null,
            'mediaFileName'     => null,
            'messageType'       => null,
            'mimeType'          => null,
            'mediaType'         => null,
            'location'          => null,
        ], $messageSend);

        $message                      = new Message();
        $message->user_id             = $whatsappAccount->user_id;
        $message->whatsapp_account_id = $whatsappAccount->id;
        $message->whatsapp_message_id = $sent['whatsAppMessage'][0]['id'];
        $message->conversation_id     = $conversation->id;
        $message->cta_url_id          = $sent['ctaUrlId'];
        $message->interactive_list_id = $sent['interactiveListId'];
        $message->template_id         = $node->type == Status::NODE_TYPE_TEMPLATE ? $node->template_id : 0;
        $message->type                = Status::MESSAGE_SENT;
        $message->message             = $node->type == Status::NODE_TYPE_TEXT ? $node->text : '';
        $message->media_id            = $sent['mediaId'];
        // Templates report no message type of their own; the column is not nullable and
        // the inbox leaves those rows on the same "text" default.
        $message->message_type        = getIntMessageType($sent['messageType']) ?? Status::TEXT_TYPE_MESSAGE;
        $message->location            = $sent['location'];
        $message->flow_id             = $node->flow_id;
        $message->flow_node_id        = $node->id;
        $message->media_caption       = $sent['mediaCaption'];
        $message->media_filename      = $sent['mediaFileName'];
        $message->media_url           = $sent['mediaUrl'];
        $message->media_path          = $sent['mediaPath'];
        $message->mime_type           = $sent['mimeType'];
        $message->media_type          = $sent['mediaType'];
        $message->status              = Status::SENT;
        $message->ordering            = Carbon::now();
        $message->save();

        $conversation->last_message_at = Carbon::now();
        $conversation->save();

        // The message has already reached the contact. A failing realtime push must not
        // skip trackState(), or a question would never be marked as waiting for its answer.
        try {
            $html                        = view('Template::user.inbox.single_message', compact('message'))->render();
            $lastConversationMessageHtml = view("Template::user.inbox.conversation_last_message", compact('message'))->render();

            event(new ReceiveMessage($whatsappAccount->id, [
                'html'            => $html,
                'message'         => $message,
                'newMessage'      => true,
                'newContact'      => false,
                'lastMessageHtml' => $lastConversationMessageHtml,
                'unseenMessage'   => $conversation->unseenMessages()->count() < 10 ? $conversation->unseenMessages()->count() : '9+',
                'lastMessageAt'   => showDateTime(Carbon::now()),
                'conversationId'  => $conversation->id,
                'mediaPath'       => s3_configured() ? url('api/inbox/media/by-path/') : getFilePath('conversation')
            ]));
        } catch (\Throwable $ex) {
            Log::error('Flow ' . $node->flow_id . ' node ' . $node->node_id . ' realtime update failed: ' . $ex->getMessage());
        }

        $this->trackState($whatsappAccount, $conversation, $node);

        return true;
    }

    private function trackState($whatsappAccount, $conversation, $node)
    {
        $state = ContactFlowState::where('conversation_id', $conversation->id)
            ->where('flow_id', $node->flow_id)
            ->first();

        if (!$state) {
            $state = new ContactFlowState();
            $state->conversation_id = $conversation->id;
            $state->flow_id = $node->flow_id;
        }

        // Left at 0 before, which made the cleanup of an abandoned flow match nothing.
        $state->user_id            = $whatsappAccount->user_id;
        $state->current_node_id    = $node->node_id;
        $state->status             = in_array($node->type, self::WAITING_NODE_TYPES) ? Status::FLOW_STATE_WAITING : Status::FLOW_STATE_SENT;
        $state->button_index       = null;
        $state->last_interacted_at = now();
        $state->save();
    }

    /**
     * Sends one node. Returns null when the node has nothing to send - a template that
     * has since been deleted, a media node whose file was never uploaded - so the flow
     * carries on instead of delivering an empty message.
     */
    private function sendNode($whatsappAccount, $conversation, $node)
    {
        $whatsappLib = new WhatsAppLib();
        $toNumber    = $conversation->contact->mobileNumber;

        if ($node->type == Status::NODE_TYPE_LIST) {
            $interactiveList = InteractiveList::where('user_id', $whatsappAccount->user_id)->find($node->interactive_list_id);

            return $interactiveList
                ? $whatsappLib->sendInteractiveListMessage($toNumber, $whatsappAccount, $interactiveList)
                : null;
        }

        if ($node->type == Status::NODE_TYPE_CTA_URL) {
            $ctaUrl = CtaUrl::where('user_id', $whatsappAccount->user_id)->find($node->cta_url_id);

            return $ctaUrl
                ? $whatsappLib->sendCtaUrlMessage($toNumber, $whatsappAccount, $ctaUrl)
                : null;
        }

        if ($node->type == Status::NODE_TYPE_BUTTON) {
            return $whatsappLib->sendButtonMessage($toNumber, $whatsappAccount, $node);
        }

        if ($node->type == Status::NODE_TYPE_TEMPLATE) {
            $template = Template::where('user_id', $whatsappAccount->user_id)->find($node->template_id);

            return $template
                ? $whatsappLib->sendTemplateMessage($this->templateRequest($node), $whatsappAccount, $template, $conversation->contact)
                : null;
        }

        $request      = new Request([]);
        $tempFilePath = null;

        if ($node->type == Status::NODE_TYPE_TEXT) {
            if (!strlen(trim((string) $node->text))) return null;

            $request['message'] = $node->text;
        } elseif ($node->type == Status::NODE_TYPE_LOCATION) {
            if (!$node->location) return null;

            $request['latitude']  = $node->location['latitude'];
            $request['longitude'] = $node->location['longitude'];
        } else {
            // image / video / audio / document
            if (!$node->media) return null;

            $nodeMediaPath = 'flowBuilderMedia/' . $node->media->media_path;

            if (s3_configured()) {
                $tempFilePath = tempnam(sys_get_temp_dir(), 's3_');
                file_put_contents($tempFilePath, s3_disk()->get($nodeMediaPath));
                $uploadedFile = new UploadedFile($tempFilePath, basename($node->media->media_path), s3_disk()->mimeType($nodeMediaPath), null, true);
            } else {
                $filePath = getFilePath('flowBuilderMedia') . '/' . $node->media->media_path;

                if (!file_exists($filePath)) return null;

                $uploadedFile = new UploadedFile($filePath, basename($filePath), mime_content_type($filePath), null, true);
            }

            $request[getNodeMediaStringType($node->media->media_type)] = $uploadedFile;
        }

        try {
            return $whatsappLib->messageSend($request, $toNumber, $whatsappAccount);
        } finally {
            if ($tempFilePath && file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }
        }
    }

    /** The template variables the builder saved on the node, in placeholder order. */
    private function templateRequest($node)
    {
        $request = new Request([]);

        $request['header_variables'] = array_values($this->decodeParams($node->header_params));
        $request['body_variables']   = array_values($this->decodeParams($node->body_params));

        return $request;
    }

    private function decodeParams($params)
    {
        if (is_string($params)) {
            $params = json_decode($params, true);
        }

        return is_array($params) ? $params : [];
    }
}
