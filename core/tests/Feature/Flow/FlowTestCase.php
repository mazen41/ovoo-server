<?php

namespace Tests\Feature\Flow;

use App\Constants\Status;
use App\Models\Contact;
use App\Models\Conversation;
use App\Models\ContactFlowState;
use App\Models\Flow;
use App\Models\FlowEdge;
use App\Models\FlowNode;
use App\Models\Message;
use App\Models\User;
use App\Models\InteractiveList;
use App\Models\Template;
use App\Models\TemplateLanguage;
use App\Models\WelcomeMessage;
use App\Models\WhatsappAccount;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Drives the real /webhook endpoint with real Meta payloads and captures what the
 * application tries to send back to the Cloud API.
 *
 * Nothing here stubs the flow engine: the only thing faked is the outbound HTTP to
 * graph.facebook.com, so a passing test means the controller, AutomationLib and
 * WhatsAppLib genuinely produced that sequence of messages.
 */
abstract class FlowTestCase extends TestCase
{
    use DatabaseTransactions;

    protected User $user;
    protected WhatsappAccount $account;

    /** The contact's number, as Meta sends it. */
    protected string $senderPhone = '8801712345678';

    protected string $wabaId = 'TEST-WABA-1';
    protected string $phoneNumberId = 'TEST-PHONE-1';

    /** Incrementing id so every inbound webhook message is treated as new. */
    private int $inboundSequence = 0;

    /** The only database these tests are ever allowed to write to. */
    private const TEST_DATABASE = 'product_ovowpp_test';

    protected function setUp(): void
    {
        // These tests write real rows, so running them under the shipped phpunit.xml
        // would point them at whatever DB_DATABASE the .env names - in a dev checkout,
        // the working database. Skip instead, and say how to run them properly.
        $database = $_ENV['DB_DATABASE'] ?? $_SERVER['DB_DATABASE'] ?? getenv('DB_DATABASE');

        if ($database !== self::TEST_DATABASE) {
            $this->markTestSkipped(
                'Flow tests need the throwaway database. Run: ./vendor/bin/phpunit -c tests/phpunit.flow.xml (see tests/README.md)'
            );
        }

        parent::setUp();

        // config/broadcasting.php hardcodes 'pusher' as the default connection, so the
        // realtime inbox events would try to reach Pusher for real during a test.
        config(['broadcasting.default' => 'null']);

        $this->fakeMetaApi();

        $this->user = new User();
        $this->user->firstname = 'Flow';
        $this->user->lastname = 'Tester';
        $this->user->username = 'flowtester' . uniqid();
        $this->user->email = uniqid('flow') . '@example.test';
        $this->user->password = bcrypt('password');
        $this->user->status = Status::ENABLE;
        $this->user->ev = Status::YES;
        $this->user->sv = Status::YES;
        $this->user->save();

        $this->account = new WhatsappAccount();
        $this->account->user_id = $this->user->id;
        $this->account->whatsapp_business_account_id = $this->wabaId;
        $this->account->phone_number_id = $this->phoneNumberId;
        $this->account->access_token = 'TEST-TOKEN';
        $this->account->is_default = Status::YES;
        $this->account->save();
    }

    /**
     * Every Cloud API call succeeds with a plausible payload. Individual tests override
     * this to simulate a rejection.
     */
    protected function fakeMetaApi(array $overrides = []): void
    {
        Http::fake(array_merge($overrides, [
            '*/messages*' => Http::response([
                'messaging_product' => 'whatsapp',
                'contacts' => [['input' => $this->senderPhone, 'wa_id' => $this->senderPhone]],
                'messages' => [['id' => 'wamid.' . uniqid()]],
            ], 200),
            '*/media*' => Http::response(['id' => 'MEDIA-' . uniqid()], 200),
            '*' => Http::response([], 200),
        ]));
    }

    // ---------------------------------------------------------------- flow building

    /**
     * Builds a flow the way the React builder does, including the deliberate ability to
     * persist nodes in a different order from the order they are wired together.
     *
     * @param  array $nodes  ['key' => ['type' => Status::NODE_TYPE_*, ...attributes]] in canvas order
     * @param  array $edges  [['from' => 'key'|'trigger', 'to' => 'key', 'button' => ?int]]
     */
    protected function makeFlow(array $nodes, array $edges, array $flowAttributes = []): Flow
    {
        $flow = new Flow();
        $flow->name = $flowAttributes['name'] ?? 'Test flow ' . uniqid();
        $flow->user_id = $this->user->id;
        $flow->whatsapp_account_id = $this->account->id;
        $flow->trigger_type = $flowAttributes['trigger_type'] ?? Status::FLOW_TRIGGER_KEYWORD_MATCH;
        $flow->keyword = $flowAttributes['keyword'] ?? 'start';
        $flow->status = $flowAttributes['status'] ?? Status::ENABLE;
        $flow->save();

        $nodeIds = [];

        foreach ($nodes as $key => $attributes) {
            $node = new FlowNode();
            $node->flow_id = $flow->id;
            $node->node_id = $key . '-' . $flow->id;
            $node->type = $attributes['type'];
            $node->text = $attributes['text'] ?? null;

            if (isset($attributes['buttons'])) {
                // Stored exactly the way FlowBuilderController::saveFlowData() stores it: a
                // json_encode()d string assigned to an attribute that is *also* cast to array.
                // Mirroring that quirk is the whole point - a test that stored a clean array
                // would not be testing the data real installs actually hold.
                $node->buttons_json = json_encode([
                    'body' => $attributes['body'] ?? 'Choose one',
                    'footer' => $attributes['footer'] ?? '',
                    'buttons' => array_map(fn($label) => ['text' => $label, 'target_node_id' => null], $attributes['buttons']),
                ]);
            }

            foreach (['template_id', 'interactive_list_id', 'cta_url_id', 'header_params', 'body_params', 'location'] as $column) {
                if (array_key_exists($column, $attributes)) {
                    $node->$column = $attributes[$column];
                }
            }

            $node->save();
            $nodeIds[$key] = $node->node_id;
        }

        foreach ($edges as $edge) {
            $flowEdge = new FlowEdge();
            $flowEdge->flow_id = $flow->id;
            // 'trigger' is the unsaved canvas node the builder gives the fixed id "1".
            $flowEdge->source_node_id = $edge['from'] === 'trigger' ? '1' : $nodeIds[$edge['from']];
            $flowEdge->target_node_id = $nodeIds[$edge['to']];
            $flowEdge->button_index = $edge['button'] ?? null;
            $flowEdge->save();
        }

        return $flow->fresh();
    }

    /**
     * A contact that has already talked to this number before, so the inbound message is
     * not the conversation's first. Tests that care about the brand-new-contact path
     * simply skip this.
     */
    protected function seedExistingContact(): Conversation
    {
        $contact = new Contact();
        $contact->user_id = $this->user->id;
        $contact->firstname = 'Test';
        $contact->lastname = 'Contact';
        $contact->mobile_code = '880';
        $contact->mobile = '1712345678';
        $contact->save();

        $conversation = new Conversation();
        $conversation->contact_id = $contact->id;
        $conversation->user_id = $this->user->id;
        $conversation->whatsapp_account_id = $this->account->id;
        $conversation->save();

        // An earlier inbound message, so welcome-message handling does not treat the next
        // one as the first of the conversation.
        $message = new Message();
        $message->user_id = $this->user->id;
        $message->whatsapp_account_id = $this->account->id;
        $message->conversation_id = $conversation->id;
        $message->whatsapp_message_id = 'wamid.SEED-' . uniqid();
        $message->message = 'hello';
        $message->type = Status::MESSAGE_RECEIVED;
        $message->message_type = Status::TEXT_TYPE_MESSAGE;
        $message->ordering = now();
        $message->save();

        return $conversation;
    }

    /** An interactive list the flow can point a NODE_TYPE_LIST node at. */
    protected function makeInteractiveList(array $rows = ['Row one', 'Row two']): InteractiveList
    {
        $list = new InteractiveList();
        $list->user_id = $this->user->id;
        $list->name = 'Test list';
        $list->button_text = 'Pick one';
        $list->header = ['type' => 'text', 'text' => 'Header'];
        $list->body = ['text' => 'Please choose'];
        $list->footer = ['text' => 'Footer'];
        $list->sections = [[
            'title' => 'Section',
            'rows' => array_map(fn($row, $i) => [
                'id' => 'row_' . $i,
                'title' => $row,
                'description' => '',
            ], $rows, array_keys($rows)),
        ]];
        $list->save();

        return $list;
    }

    /** An approved template a NODE_TYPE_TEMPLATE node can send. */
    protected function makeTemplate(string $name = 'flow_template', string $body = 'Hi {{1}}, your order {{2}} is ready.'): Template
    {
        $template = new Template();
        $template->user_id = $this->user->id;
        $template->whatsapp_account_id = $this->account->id;
        $template->whatsapp_template_id = 'WT-' . uniqid();
        $template->name = $name;
        $template->body = $body;
        $template->buttons = [];
        $template->category_id = 1;
        // The send reads $template->language->code, so the row has to exist rather than
        // being assumed present in whatever database the tests were pointed at.
        $language = TemplateLanguage::where('code', 'en_US')->first();

        if (!$language) {
            $language = new TemplateLanguage();
            $language->code = 'en_US';
            $language->country = 'United States';
            $language->save();
        }

        $template->language_id = $language->id;
        $template->status = Status::TEMPLATE_APPROVED;
        $template->save();

        return $template->fresh();
    }

    protected function enableWelcomeMessage(string $text = 'Welcome!'): WelcomeMessage
    {
        $welcome = new WelcomeMessage();
        $welcome->whatsapp_account_id = $this->account->id;
        $welcome->message = $text;
        $welcome->status = Status::ENABLE;
        $welcome->save();

        $this->account->refresh();

        return $welcome;
    }

    /** The single flow state row for the seeded conversation, or null. */
    protected function flowState(): ?ContactFlowState
    {
        return ContactFlowState::whereIn(
            'conversation_id',
            Conversation::where('user_id', $this->user->id)->pluck('id')
        )->latest('id')->first();
    }

    // ---------------------------------------------------------------- webhook drivers

    protected function receiveText(string $text)
    {
        return $this->postWebhook([
            'type' => 'text',
            'text' => ['body' => $text],
        ]);
    }

    protected function receiveButtonReply(string $id, string $title)
    {
        return $this->postWebhook([
            'type' => 'interactive',
            'interactive' => [
                'type' => 'button_reply',
                'button_reply' => ['id' => $id, 'title' => $title],
            ],
        ]);
    }

    protected function receiveListReply(string $id, string $title, string $description = '')
    {
        return $this->postWebhook([
            'type' => 'interactive',
            'interactive' => [
                'type' => 'list_reply',
                'list_reply' => ['id' => $id, 'title' => $title, 'description' => $description],
            ],
        ]);
    }

    /** A quick-reply button on a *template* message, which Meta sends as type "button". */
    protected function receiveTemplateButton(string $text)
    {
        return $this->postWebhook([
            'type' => 'button',
            'button' => ['text' => $text, 'payload' => $text],
        ]);
    }

    private function postWebhook(array $message)
    {
        $this->inboundSequence++;

        $message = array_merge([
            'from' => $this->senderPhone,
            'id' => 'wamid.IN-' . $this->inboundSequence . '-' . uniqid(),
            'timestamp' => (string) time(),
        ], $message);

        return $this->postJson('/webhook', [
            'object' => 'whatsapp_business_account',
            'entry' => [[
                'id' => $this->wabaId,
                'changes' => [[
                    'field' => 'messages',
                    'value' => [
                        'messaging_product' => 'whatsapp',
                        'metadata' => [
                            'display_phone_number' => '15550001111',
                            'phone_number_id' => $this->phoneNumberId,
                        ],
                        'contacts' => [[
                            'profile' => ['name' => 'Test Contact'],
                            'wa_id' => $this->senderPhone,
                        ]],
                        'messages' => [$message],
                    ],
                ]],
            ]],
        ]);
    }

    // ---------------------------------------------------------------- assertions

    /**
     * The messages the application actually tried to send, in order, reduced to something
     * readable: 'text:Hello', 'button:Choose one', 'template:order_update', 'image', ...
     */
    protected function sentMessages(): array
    {
        $sent = [];

        foreach (Http::recorded() as [$request]) {
            /** @var ClientRequest $request */
            if (!str_contains($request->url(), '/messages')) {
                continue;
            }

            $body = $request->data();
            $type = $body['type'] ?? 'text';

            $sent[] = match ($type) {
                'text' => 'text:' . ($body['text']['body'] ?? ''),
                'interactive' => ($body['interactive']['type'] ?? 'interactive') . ':'
                    . ($body['interactive']['body']['text'] ?? $body['interactive']['action']['name'] ?? ''),
                'template' => 'template:' . ($body['template']['name'] ?? ''),
                default => $type,
            };
        }

        return $sent;
    }

    /** The raw decoded bodies of every outbound /messages call, for detailed assertions. */
    protected function sentPayloads(): array
    {
        $payloads = [];

        foreach (Http::recorded() as [$request]) {
            if (str_contains($request->url(), '/messages')) {
                $payloads[] = $request->data();
            }
        }

        return $payloads;
    }
}
