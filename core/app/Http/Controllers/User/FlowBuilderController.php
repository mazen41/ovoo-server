<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Flow;
use App\Models\FlowEdge;
use App\Models\FlowNode;
use App\Models\FlowNodeMedia;
use App\Models\WhatsappAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlowBuilderController extends Controller
{
    public function index()
    {
        $pageTitle = "Automation Flow List";
        $user      = getParentUser();

        $flows     = Flow::where('user_id', $user->id)->orderBy('id', 'desc')->searchable(['name'])->paginate(getPaginate());
        $userWhatsAppAccounts = WhatsappAccount::where('user_id', $user->id)->get();

        return view('Template::user.flow.index', compact('pageTitle', 'flows', 'userWhatsAppAccounts'));
    }

    public function create()
    {
        $pageTitle = "Create Automation Flow";
        $view      = 'Template::user.flow.create';

        return responseManager("flow_builder", $pageTitle, "success", [
            'view'           => $view,
            'pageTitle'      => $pageTitle,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:40']);

        $user = getParentUser();

        if (Flow::where('user_id', $user->id)->where('name', $request->name)->exists()) {
            return apiResponse("flow_builder", "error", ["The flow name already exists."]);
        }

        if (!featureAccessLimitCheck($user->flow_limit)) {
            return apiResponse("flow_builder", "error", ["You have reached the maximum flow limit."]);
        }


        $flowData = json_decode($request->data, true);

        return $this->saveFlowData($user, $flowData);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:40']);

        $user = getParentUser();

        $flow = Flow::where('user_id', $user->id)->find($id);

        if (!$flow) {
            return apiResponse("flow_builder", "error", ["Flow not found."]);
        }

        if (Flow::where('user_id', $user->id)->where('id', '!=', $id)->where('name', $request->name)->exists()) {
            return apiResponse("flow_builder", "error", ["Flow name already exists."]);
        }

        $flowData = json_decode($request->data, true);

        return $this->saveFlowData($user, $flowData, $flow);
    }

    private function saveFlowData($user, $flowData, $flow = null)
    {

        $nodes       = $flowData['nodes'] ?? [];
        $edges       = $flowData['edges'] ?? [];
        $triggerNode = $nodes[0] ?? null;

        if (!$triggerNode) {
            return apiResponse("flow_builder", "error", ["Trigger node not found."]);
        }

        $flowNodes = array_filter($nodes, fn($node) => $node['id'] != 1 && $node['type'] != "triggerNode");


        if (!$flowNodes || count($flowNodes) === 0) {
            return apiResponse("flow_builder", "error", ["At least one flow node is required."]);
        }

        if (!$edges || count($edges) == 0) {
            return apiResponse("flow_builder", "error", ["Connect at least one flow node to trigger node."]);
        }

        if (!$flow) {

            if (request()->account_id) {
                $whatsappAccount = WhatsappAccount::where('user_id', $user->id)->find(request()->account_id);
            } else {
                $whatsappAccount = WhatsappAccount::where('user_id', $user->id)->where('is_default', Status::YES)->first();
            }

            if (!$whatsappAccount) {
                return apiResponse('invalid', 'error', ['The  whatsapp account is not found']);
            }

            $flow                      = new Flow();
            $flow->user_id             = $user->id;
            $flow->whatsapp_account_id = $whatsappAccount->id;
        }

        $flow->name         = request('name');
        $flow->trigger_type = getTriggerType($triggerNode['data']['trigger']);
        $flow->keyword      = ($triggerNode['data']['trigger'] === 'keyword_match' && $triggerNode['data']['keyword'])
            ? $triggerNode['data']['keyword']
            :  null;
        $flow->nodes_json = json_encode($nodes);
        $flow->edges_json = json_encode($edges);
        $flow->save();

        if (!$flow->wasRecentlyCreated) {
            FlowNode::where('flow_id', $flow->id)->delete();
            FlowEdge::where('flow_id', $flow->id)->delete();
        }

        foreach ($flowNodes as $node) {

            $location = null;
            if ($node['type'] === 'sendLocation' && $node['data']['latitude'] && $node['data']['longitude']) {
                $location = [
                    'latitude' => $node['data']['latitude'],
                    'longitude' => $node['data']['longitude']
                ];
            }

            $flowNode                           = new FlowNode();
            $flowNode->flow_id                  = $flow->id;
            $flowNode->node_id                  = $node['id'];
            $flowNode->type                     = getNodeType($node['type']);
            $flowNode->text                     = @$node['data']['message'] ?? null;
            $flowNode->position_x               = @$node['position']['x'] ?? null;
            $flowNode->position_y               = @$node['position']['y'] ?? null;
            $flowNode->location                 = $location;
            $flowNode->nodes_json               = json_encode($node);

            if ($node['type'] == 'sendButton') {
                $flowNode->buttons_json = json_encode($node['data'] ?? []);
            }
            if ($node['type'] == 'sendCtaUrl') {
                $flowNode->cta_url_id = @$node['data']['selectedCta']['id'] ?? 0;
            }
            if ($node['type'] == 'sendList') {
                $flowNode->interactive_list_id = @$node['data']['selectedList']['id'] ?? 0;
            }

            $flowNode->save();
        }

        foreach ($edges as $edge) {
            $flowEdge = new FlowEdge();
            $flowEdge->flow_id = $flow->id;
            $flowEdge->source_node_id = $edge['source'];
            $flowEdge->target_node_id = $edge['target'];
            $flowEdge->edge_json = json_encode($edge);

            $flowEdge->button_index = null;
            if (isset($edge['sourceHandle']) && str_starts_with($edge['sourceHandle'], 'button-')) {
                $flowEdge->button_index = intval(str_replace('button-', '', $edge['sourceHandle']));
            }

            $flowEdge->save();
        }

        if (!$flow) {
            decrementFeature($user, 'flow_limit');
        }

        return apiResponse(
            "flow_builder",
            "success",
            [$flow->wasRecentlyCreated ? "Flow created successfully." : "Flow updated successfully."]
        );
    }

    public function status($id)
    {
        $user = getParentUser();
        $flow = Flow::where('user_id', $user->id)->find($id);

        if (!$flow) {
            return apiResponse("flow_builder", "error", ["Sorry, flow not found."]);
        }

        $flow->status = !$flow->status;
        $flow->save();

        return apiResponse("flow_builder", "success", ["Flow status updated successfully."]);
    }

    public function edit($id)
    {
        $view = 'Template::user.flow.edit';
        $user = getParentUser();
        $flow = Flow::where('user_id', $user->id)->find($id);

        if (!$flow) {
            $notify[] = ['error', 'Flow not found'];
            return back()->withNotify($notify);
        }
        $pageTitle = "Edit Flow - " . $flow->name;

        return responseManager("flow_builder", $pageTitle, "success", [
            'view'           => $view,
            'pageTitle'      => $pageTitle,
            'flow'           => $flow
        ]);
    }

    public function delete($id)
    {
        $user = getParentUser();
        $flow = Flow::where('user_id', $user->id)->find($id);

        if (!$flow) {
            $notify[] = ['error', 'Flow not found'];
            return back()->withNotify($notify);
        }

        foreach ($flow->nodes as $node) {
            $media  = $node->media;
            if ($media) {
                $filePath = getFilePath('flowBuilderMedia') . '/' . $media->media_path;
                if ($media->media_path && file_exists($filePath)) {
                    unlink($filePath);
                }
                $media->delete();
            }

            $node->delete();
        }

        $flow->delete();

        $notify[] = ['success', 'Flow deleted successfully'];
        return back()->withNotify($notify);
    }

    public function mediaUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:image,video,audio,document',
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            return apiResponse("media_upload", "error", $validator->errors()->all());
        }

        $fileValidation = $this->validateMediaFile($request->file('file'), $request->input('type'));

        if ($fileValidation !== true) {
            return apiResponse("media_upload", "error", [$fileValidation]);
        }


        $user = getParentUser();

        $oldNodeMedia = FlowNodeMedia::where('user_id', $user->id)->where('flow_node_id', $request->node_id)->first();

        $nodeMedia               = new FlowNodeMedia();
        $nodeMedia->user_id      = $user->id;
        $nodeMedia->flow_node_id = $request->node_id;
        $nodeMedia->media_type   = getNodeMediaIntType($request->type);

        if ($request->hasFile('file')) {
            try {
                $old = $oldNodeMedia->media_path ?? null;
                $fileName = $request->node_id . '.' . $request->file->getClientOriginalExtension();
                $nodeMedia->media_path = fileUploader($request->file, getFilePath('flowBuilderMedia'), getFileSize('flowBuilderMedia'), $old, filename: $fileName);
            } catch (\Exception $e) {
                return apiResponse("media_upload", "error", [$e->getMessage()]);
            }
        }

        if ($oldNodeMedia) {
            $oldNodeMedia->delete();
        }

        $nodeMedia->save();

        return apiResponse("media_upload", "success", ["Media uploaded successfully"], [
            'mediaPath' => route('home') . '/' . getFilePath('flowBuilderMedia') . '/' . $nodeMedia->media_path
        ]);
    }

    private function validateMediaFile($file, $type)
    {
        switch ($type) {
            case 'image':
                if (!in_array($file->extension(), ['jpg', 'jpeg', 'png'])) {
                    return 'Only JPG/PNG images allowed.';
                }
                if ($file->getSize() > 5 * 1024 * 1024) {
                    return 'The image size must be under 5MB.';
                }
                break;

            case 'video':
                if (!in_array($file->extension(), ['mp4', '3gp'])) {
                    return 'Only MP4/3GP videos allowed.';
                }
                if ($file->getSize() > 16 * 1024 * 1024) {
                    return 'The video size must be under 16MB.';
                }
                break;

            case 'audio':
                if (!in_array($file->extension(), ['aac', 'mp4', 'mpeg', 'amr', 'ogg'])) {
                    return 'Invalid audio format.';
                }
                if ($file->getSize() > 16 * 1024 * 1024) {
                    return 'The audio size must be under 16MB.';
                }
                break;

            case 'document':
                if ($file->getSize() > 100 * 1024 * 1024) {
                    return 'The document size must be under 100MB.';
                }
                break;

            default:
                return 'Invalid media type.';
        }

        return true;
    }
}
