<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignContact;
use App\Models\ContactList;
use App\Models\ContactTag;
use App\Models\Template;
use App\Models\WhatsappAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        $user      = getParentUser();
        $whatsappAccountId = getWhatsappAccountId($user);

        $campaigns = Campaign::where('user_id', $user->id)
            ->where('whatsapp_account_id', $whatsappAccountId)
            ->with('template')
            ->searchable(['title'])
            ->filter(['status'])
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());

        $templates = Template::where('user_id', getParentUser()->id)
            ->where('whatsapp_account_id', $whatsappAccountId)
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());

        return apiResponse('campaigns', 'success', ["all campaign"], [
            'campaigns' => $campaigns,
            'templates' => $templates
        ]);
    }
    public function create()
    {
        $user             = getParentUser();
        $whatsappAccounts = WhatsappAccount::where('user_id', $user->id)->get();
        $templates        = Template::where('user_id', getParentUser()->id)
            ->orderBy('id', 'desc')
            ->get();

        $contactLists = ContactList::where('user_id', $user->id)->orderBy('id', 'desc')->get();
        $contacTags = ContactTag::where('user_id', $user->id)->orderBy('id', 'desc')->get();

        return apiResponse('campaigns', 'success', ["Campaign data below"], [
            'templates'         => $templates,
            'whatsapp_accounts' => $whatsappAccounts,
            'contact_lists'     => $contactLists,
            'contact_tags'      => $contacTags
        ]);
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
            ->with('language')
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

        decrementFeature($user, 'campaign_limit');

        $notify[] = 'Campaign created successfully';
        return apiResponse('campaign_created', 'success', $notify);
    }
}
