<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignContact;
use App\Models\ContactList;
use App\Models\ContactTag;
use App\Models\Message;
use App\Models\Template;
use App\Models\WhatsappAccount;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        $user      = getParentUser();
        $pageTitle = "Manage Campaign";
        $baseQuery = Campaign::where('user_id', $user->id)->where('whatsapp_account_id', getWhatsappAccountId($user))->with('template')->searchable(['title'])->filter(['status'])->orderBy('id', 'desc');

        if (request()->export) {
            return exportData($baseQuery, request()->export, "campaign", "A4 landscape");
        }
        $campaigns = $baseQuery->paginate(getPaginate());
        return view('Template::user.campaign.index', compact('pageTitle', 'campaigns'));
    }

    public function createCampaign()
    {
        $user             = getParentUser();
        $pageTitle        = "New Campaign";
        $contactLists     = ContactList::where('user_id', $user->id)->with('contact')->orderBy('name', 'asc')->get();
        $contactTags      = ContactTag::where('user_id', $user->id)->with('contacts')->orderBy('name', 'asc')->get();
        $templates        = Template::where('user_id', $user->id)->approved()->orderBy('id', 'desc')->get();
        $whatsappAccounts = WhatsappAccount::where('user_id', $user->id)->with('templates')->get();

        return view('Template::user.campaign.create', compact('pageTitle', 'contactLists', 'templates', 'whatsappAccounts', 'contactTags'));
    }

    public function saveCampaign(Request $request)
    {
        $request->validate([
            'title'               => 'required',
            'contact_lists'       => 'required',
            'template_id'         => 'required',
            'whatsapp_account_id' => 'required',
            'schedule'            => 'nullable|in:on,off',
            'scheduled_at'        => 'required_if:schedule,on|date',
        ]);

        $user            = getParentUser();
        $whatsappAccount = WhatsappAccount::where('user_id', $user->id)
            ->where('id', $request->whatsapp_account_id)
            ->with('templates')
            ->first();

        if (!$whatsappAccount) {
            return responseManager('invalid', 'The selected whatsapp account is invalid');
        }

        if (!featureAccessLimitCheck($user->campaign_limit)) {
            return responseManager('subscription_required', 'You have reached the maximum limit of campaigns');
        }

        if ($request->schedule == 'on') {
            if (Carbon::parse($request->scheduled_at)->isPast()) {
                return responseManager('future_date_required', 'Scheduled date must be future date');
            }
        }

        $campaignExists = Campaign::where('user_id', $user->id)->where("title", $request->title)->first();

        if ($campaignExists) {
            return responseManager('exists', 'The campaign title already exists');
        }
        $template = Template::where('user_id', $user->id)
            ->approved()
            ->with('language', 'category')
            ->where('id', $request->template_id)
            ->first();

        if (!$template) {
            return responseManager('not_found', 'The selected template is not found');
        }
        if ($template->whatsapp_account_id != $whatsappAccount->id) {
            return responseManager('same_required', 'The selected whatsapp account & template whatsapp account id must be same');
        }

        $bodyParams = [];
        $headerParams = [];
        foreach (($request->body_variables ?? []) as $value) {
            $bodyParams[] = [
                'type' => 'text',
                'text' => $value ?? '',
            ];
        }

        foreach (($request->header_variables ?? []) as $value) {
            $headerParams[] = [
                'type' => 'text',
                'text' => $value ?? '',
            ];
        }


        $contactIds = [];

        $contactIdsFromList = ContactList::where('user_id', getParentUser()->id)
            ->whereIn('id', $request->contact_lists ?? [])
            ->with('contact')
            ->get()
            ->flatMap(fn($contactList) => $contactList->contact->where('is_blocked', Status::NO)->pluck('id'))
            ->toArray();

        $contactIdsFromTags = ContactTag::where('user_id', getParentUser()->id)
            ->whereIn('id', $request->contact_tags ?? [])
            ->with('contacts')
            ->get()
            ->flatMap(fn($contactTag) => $contactTag->contacts->where('is_blocked', Status::NO)->pluck('id'))
            ->toArray();

        $contactIds = array_unique(array_merge($contactIdsFromList, $contactIdsFromTags)) ?? [];

        if (empty($contactIds)) {
            return responseManager('contact_limit', 'At least one contact is required');
        }

        if ($request->schedule == 'on' && $request->scheduled_at) {
            $status          = Status::CAMPAIGN_SCHEDULED;
            $sendAt          = now()->parse($request->scheduled_at);
        } else {
            $status          = Status::CAMPAIGN_RUNNING;
            $sendAt          = now();
        }

        $campaign                         = new Campaign();
        $campaign->title                  = $request->title;
        $campaign->user_id                = $user->id;
        $campaign->whatsapp_account_id    = $whatsappAccount->id;
        $campaign->template_id            = $template->id;
        $campaign->template_header_params = $headerParams ?? [];
        $campaign->template_body_params   = $bodyParams ?? [];
        $campaign->status                 = $status;
        $campaign->send_at                = $sendAt;
        $campaign->total_message          = count($contactIds);
        $campaign->save();

        $campaign->contacts()->sync($contactIds);
        CampaignContact::where('campaign_id', $campaign->id)->update(['send_at' => $sendAt]);

        if (strtoupper($template->category?->name ?? '') === 'MARKETING') {
            CampaignContact::where('campaign_id', $campaign->id)
                ->whereHas('contact', fn($query) => $query->where('is_marketing_opted_out', Status::YES))
                ->update([
                    'is_trigger'    => Status::YES,
                    'error_message' => json_encode('Promotional message not sent because the contact opted out of marketing messages.'),
                ]);
        }

        decrementFeature($user, 'campaign_limit');

        $notify[] = 'Campaign created successfully';
        return apiResponse('campaign_created', 'success', $notify);
    }

    public function report($id)
    {
        $user         = getParentUser();
        $pageTitle    = "Campaign Report";
        $campaign     = Campaign::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $baseQuery    = CampaignContact::where('campaign_id', $campaign->id)->with('contact');

        $campaignBaseContactQuery = CampaignContact::where('campaign_id', $campaign->id);

        $lastCampaign             = Campaign::where('id', '!=', $id)->where('user_id', $user->id)->first();
        $lastCampaignMessageCount = $lastCampaign ? CampaignContact::where('campaign_id', $lastCampaign->id)->count() : 0;


        if (request()->export) {
            if (request()->export == 'minimal') {
                return $this->downloadCsv($campaign);
            }

            if (request()->export == 'maximal') {
                return exportData($baseQuery, 'excel', "campaignContact", "A4 landscape");
            }
        }

        $campaignContacts = $baseQuery->paginate(getPaginate());

        $widget['total_message'] = (clone $campaignBaseContactQuery)->count();
        $widget['total_sent']    = Message::where('campaign_id', $campaign->id)
            ->count();

        $widget['total_delivered'] = Message::where('campaign_id', $campaign->id)
            ->whereIn('status', [Status::DELIVERED, Status::READ])
            ->count();

        $widget['total_read'] = Message::where('campaign_id', $campaign->id)
            ->where('status', Status::READ)
            ->count();

        $widget['total_failed'] = Message::where('campaign_id', $campaign->id)
            ->where('status', Status::FAILED)
            ->count();


        $messages = Message::where('campaign_id', $campaign->id)
            ->latest('id')
            ->filter(['status'])
            ->with('conversation.contact')
            ->paginate(getPaginate(10));

        return view('Template::user.campaign.report', compact('pageTitle', 'campaign', 'widget', 'campaignContacts', 'widget', 'messages', 'lastCampaignMessageCount'));
    }

    public function downloadCsv($campaign)
    {
        $fileName = 'CampaignReport.csv';
        $filePath = storage_path($fileName);

        $file = fopen($filePath, 'w');

        if ($file === false) {
            throw new \Exception("Error opening the file");
        }

        $headers = ['Total message', 'Sent message', 'Success message', 'Failed message', 'Send at'];
        $rows = [
            [$campaign->total_message, $campaign->total_send, $campaign->total_success, $campaign->total_failed, showDateTime($campaign->send_at)],
        ];

        fputcsv($file, $headers);

        foreach ($rows as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
