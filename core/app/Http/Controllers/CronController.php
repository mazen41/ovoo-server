<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Http\Controllers\User\PurchasePlanController;
use App\Lib\CurlRequest;
use App\Lib\WhatsApp\WhatsAppLib;
use App\Models\Campaign;
use App\Models\CampaignContact;
use App\Models\Conversation;
use App\Models\Coupon;
use App\Models\CronJob;
use App\Models\CronJobLog;
use App\Models\FlowNodeMedia;
use App\Models\InstalledAddon;
use App\Models\Message;
use App\Models\PlanPurchase;
use App\Models\WhatsappAccount;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;

class CronController extends Controller
{
    public function cron()
    {
        $general            = gs();
        $general->last_cron = now();
        $general->save();

        $crons = CronJob::with('schedule');

        if (request()->alias) {
            $crons->where('alias', request()->alias);
        } else {
            $crons->where('next_run', '<', now())->where('is_running', Status::YES);
        }
        $crons = $crons->get();
        foreach ($crons as $cron) {
            $cronLog              = new CronJobLog();
            $cronLog->cron_job_id = $cron->id;
            $cronLog->start_at    = now();

            if ($cron->is_default) {
                $controller = new $cron->action[0];
                try {
                    $method = $cron->action[1];
                    $controller->$method();
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            } else {
                try {
                    CurlRequest::curlContent($cron->url);
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            }
            $cron->last_run = now();
            $cron->next_run = now()->addSeconds((int)$cron->schedule->interval);
            $cron->save();

            $cronLog->end_at = $cron->last_run;

            $startTime         = Carbon::parse($cronLog->start_at);
            $endTime           = Carbon::parse($cronLog->end_at);
            $diffInSeconds     = $startTime->diffInSeconds($endTime);
            $cronLog->duration = $diffInSeconds;
            $cronLog->save();
        }
        if (request()->target == 'all') {
            $notify[] = ['success', 'Cron executed successfully'];
            return back()->withNotify($notify);
        }
        if (request()->alias) {
            $notify[] = ['success', keyToTitle(request()->alias) . ' executed successfully'];
            return back()->withNotify($notify);
        }
    }

    public function subscriptionExpired()
    {
        $expiredSubscriptions = PlanPurchase::with(['user', 'plan'])->where('expired_at', '<=', Carbon::now())->where('is_sent_expired_notify', Status::NO)->get();

        foreach ($expiredSubscriptions as $subscription) {

            $user = $subscription->user;
            $plan = $subscription->plan;

            if (!$user || !$plan) continue;

            $subscription->is_sent_expired_notify = Status::YES;
            $subscription->save();

            if ($subscription->auto_renewal) {
                $purchasePrice = getPlanPurchasePrice($plan, $subscription->recurring_type);
                if ($purchasePrice <= 0) continue;

                if ($user->balance < $purchasePrice) {
                    notify($user, 'SUBSCRIPTION_EXPIRED', [
                        'subscription_type' => $subscription->billing_cycle,
                        'subscription_url'  => route('user.subscription.index'),
                        'plan_name'         => $plan->name,
                        'amount'            => showAmount($purchasePrice, currencyFormat: false),
                        'expired_at'        => showDateTime($subscription->expired_at),
                        'post_balance'      => showAmount($user->balance, currencyFormat: false),
                    ]);
                } else {
                    PurchasePlanController::updateUserSubscription($user, $plan, $subscription->recurring_type);
                }
                continue;
            }

            $user->account_limit        = 0;
            $user->agent_limit          = 0;
            $user->contact_limit        = 0;
            $user->template_limit       = 0;
            $user->flow_limit           = 0;
            $user->campaign_limit       = 0;
            $user->short_link_limit     = 0;
            $user->floater_limit        = 0;
            $user->welcome_message      = 0;
            $user->ai_assistance        = 0;
            $user->interactive_message  = 0;
            $user->ecommerce_available     = 0;
            $user->save();

            notify($user, 'SUBSCRIPTION_EXPIRED', [
                'subscription_type' => $subscription->billing_cycle,
                'subscription_url'  => route('user.subscription.index'),
                'plan_name'         => $plan->name,
                'amount'            => showAmount($subscription->amount, currencyFormat: false),
                'expired_at'        => showDateTime($subscription->expired_at),
                'post_balance'      => showAmount($user->balance, currencyFormat: false),
            ]);
        }
    }

    public function subscriptionNotify()
    {
        $targetDate    = Carbon::now()->addDays(gs('subscription_notify_before'))->startOfDay()->format('Y-m-d');
        $subscriptions = PlanPurchase::with(['user', 'plan'])
            ->whereDate('expired_at', $targetDate)
            ->where('is_sent_reminder_notify', Status::NO)
            ->get();

        foreach ($subscriptions as $subscription) {
            $user          = $subscription->user;
            $purchasePrice = getPlanPurchasePrice($subscription->plan, $subscription->recurring_type);

            notify($user, 'UPCOMING_EXPIRED_SUBSCRIPTION', [
                'subscription_type' => $subscription->billing_cycle,
                'subscription_url'  => route('user.subscription.index', ['tab' => 'current-plan']),
                'plan_name'         => $subscription->plan->name,
                'plan_price'        => showAmount($purchasePrice, currencyFormat: false),
                'next_billing'      => showDateTime($subscription->expired_at, 'd M Y'),
                'post_balance'      => showAmount($user->balance, currencyFormat: false),
            ]);
        }
    }

    public function campaignMessage()
    {
        $contacts = CampaignContact::whereHas('campaign')
            ->whereHas('contact')
            ->where('is_trigger', Status::NO)
            ->where('send_at', '<=', Carbon::now())
            ->with('contact', 'campaign', 'campaign.whatsappAccount')
            ->limit(40)
            ->orderBy('send_at')
            ->get();

        // Send timestamps of the current one second window, keyed by whatsapp account id.
        // Only populated for coexistence accounts, so Cloud API pacing is untouched.
        $coexWindow = [];

        foreach ($contacts as $contact) {
            try {

                $campaign          = $contact->campaign;
                $connectedWhatsapp = $campaign->whatsappAccount;

                if (!$connectedWhatsapp) continue;


                $accessToken   = $connectedWhatsapp->access_token;
                $phoneNumberId = $connectedWhatsapp->phone_number_id;

                if (!$accessToken || !$phoneNumberId) continue;


                $contact->is_trigger = Status::YES;
                $contact->save();

                $template = $campaign->template;
                $contactOriginal = $contact->contact;

                if (
                    strtoupper($template->category?->name ?? '') === 'MARKETING'
                    && $contactOriginal->is_marketing_opted_out == Status::YES
                ) {
                    $contact->error_message = 'Promotional message not sent because the contact opted out of marketing messages.';
                    $contact->save();
                    continue;
                }

                $url      = "https://graph.facebook.com/v22.0/{$phoneNumberId}/messages?access_token={$accessToken}";

                $templateHeaderParams = $campaign->template_header_params ?? [];
                $templateBodyParams   = $campaign->template_body_params ?? [];

                $headerParams = parseTemplateParams($templateHeaderParams, $contactOriginal);
                $bodyParams   = parseTemplateParams($templateBodyParams, $contactOriginal);

                $conversation    = Conversation::where('user_id', $campaign->user_id)->where('contact_id', $contactOriginal->id)->first();

                if (!$conversation) {
                    $conversation                      = new Conversation();
                    $conversation->user_id             = $campaign->user_id;
                    $conversation->whatsapp_account_id = $connectedWhatsapp->id;
                    $conversation->contact_id          = $contactOriginal->id;
                    $conversation->save();
                }

                $components = (new WhatsAppLib())->buildTemplateComponents(
                    $template,
                    $headerParams,
                    $bodyParams,
                    parseTemplateParams($campaign->template_button_params ?? [], $contactOriginal)
                );

                if (empty($components)) {
                    continue;
                }

                $data = [
                    'messaging_product' => 'whatsapp',
                    'to'                => '+' . $contactOriginal->mobileNumber,
                    'type'              => 'template',
                    'template'          => [
                        'name'     => trim($template->name),
                        'language' => [
                            'code' => $template->language->code,
                        ],
                        'components' => $components
                    ],
                ];

                if ($connectedWhatsapp->is_coexistence) {
                    // Meta caps coexistence numbers at 20 messages per second. Keep a per account
                    // sliding one second window and sleep off the remainder when it fills up.
                    $accountKey = $connectedWhatsapp->id;
                    $now        = microtime(true);

                    $coexWindow[$accountKey] = array_values(array_filter(
                        $coexWindow[$accountKey] ?? [],
                        fn($sentAt) => $sentAt > $now - 1
                    ));

                    if (count($coexWindow[$accountKey]) >= Status::COEXISTENCE_MPS_LIMIT) {
                        $waitFor = 1 - ($now - $coexWindow[$accountKey][0]);

                        if ($waitFor > 0) {
                            usleep((int) ($waitFor * 1000000));
                        }

                        $now                     = microtime(true);
                        $coexWindow[$accountKey] = array_values(array_filter(
                            $coexWindow[$accountKey],
                            fn($sentAt) => $sentAt > $now - 1
                        ));
                    }

                    $coexWindow[$accountKey][] = $now;
                }

                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$accessToken}",
                ])->post($url, $data);



                if ($response->failed()) {
                    $errorMessage           = $response->body();
                    $contact->error_message = $errorMessage;
                    $contact->save();
                    continue;
                } else {
                    $errorMessage = '';
                }

                $data = $response->json();

                $message                      = new Message();
                $message->whatsapp_account_id = $campaign->whatsapp_account_id;
                $message->user_id             = $campaign->user_id;
                $message->whatsapp_message_id = $data['messages'][0]['id'];
                $message->conversation_id     = $conversation->id;
                $message->template_id         = $template->id;
                $message->campaign_id         = $campaign->id;
                $message->type                = Status::MESSAGE_SENT;
                $message->ordering            = Carbon::now();
                $message->error_message       = $errorMessage;
                $message->status              = Status::SENT;
                $message->save();

                $conversation->last_message_at = Carbon::now();
                $conversation->save();
            } catch (Exception $ex) {
                $contact->error_message = $ex->getMessage();
                $contact->is_trigger   = Status::NO;
                $contact->save();
                continue;
            }
        }
    }


    public function clearTrashMedia()
    {
        $trashMedia = FlowNodeMedia::whereDoesntHave('node')->where('created_at', '<=', Carbon::now()->subHour())->get();

        foreach ($trashMedia as $media) {
            if ($media->media_path) {
                if (s3_configured()) {
                    s3_disk()->delete('flowBuilderMedia/' . $media->media_path);
                } else {
                    $filePath = getFilePath('flowBuilderMedia') . '/' . $media->media_path;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
            $media->delete();
        }
    }

    public function checkCampaignStatus()
    {
        $campaigns = Campaign::where('status', '!=', Status::CAMPAIGN_COMPLETED)
            ->withCount(['contacts as total_contacts', 'contacts as total_contact_trigger' => function ($q) {
                $q->where('is_trigger', Status::YES);
            }])
            ->take(50)
            ->get();

        foreach ($campaigns as $campaign) {

            if (Carbon::parse($campaign->send_at)->isFuture()) continue;

            if ($campaign->total_contacts <= $campaign->total_contact_trigger) {
                $campaign->status = Status::CAMPAIGN_COMPLETED;
            } elseif ($campaign->status != Status::CAMPAIGN_RUNNING) {
                $campaign->status = Status::CAMPAIGN_RUNNING;
            } else {
                continue;
            }

            $campaign->save();
        }
    }

    public function couponExpiration()
    {
        $expiredCoupons = Coupon::whereNot('status', Status::COUPON_EXPIRED)->where('end_date', '<', Carbon::now())->get();
        foreach ($expiredCoupons as $coupon) {
            $coupon->status = Status::COUPON_EXPIRED;
            $coupon->save();
        }
    }

    public function campaignMessageUpdate()
    {
        $campaigns = Campaign::where('send_at', '<', Carbon::now())->get();

        foreach ($campaigns as $campaign) {
            Message::where('whatsapp_account_id', $campaign->whatsapp_account_id)
                ->where('user_id', $campaign->user_id)
                ->orderBy('send_at')
                ->take($campaign->total_success)
                ->orderBy('send_at')
                ->where('campaign_id', 0)
                ->update(['campaign_id' => $campaign->id]);
        }

        CronJob::where('alias', 'update_campaign_message')->delete();
    }

    public function checkVersionUpdate()
    {
        $installedAddons = InstalledAddon::query()->installed()->get();

        $url = 'https://license.ovosolution.com/api/addon/check-updates';

        $installedAddons->each(function ($addon) use ($url) {

            $payload = [
                'addon_slug'      => $addon->slug,
                'current_version' => $addon->version,
                'main_product'    => systemDetails()['name']
            ];

            $response    = Http::post($url, $payload);
            $data        = $response->json();

            $nextVersion = $data['data']['need_to_update'] ?? null;

            if ($data['status'] == 'success' && !empty($data['data']['available_update']) && $nextVersion && version_compare($nextVersion, $addon->version, '>')) {
                $addon->update_available = $nextVersion;
                $addon->save();
            }
        });
    }


    /**
     * Downloads media that arrived on a coexistence echo.
     *
     * Echoes only carry an asset id, and fetching the binary inline in the webhook would risk Meta's
     * webhook timeout. Meta expires media asset ids after 14 days, so anything older is retired
     * without spending an API call on it.
     */
    public function coexistenceMediaSync()
    {
        $deadline = microtime(true) + 45;
        $lib      = new WhatsAppLib();

        $messages = Message::where('media_sync_status', Status::MEDIA_SYNC_PENDING)
            ->whereNotNull('media_id')
            ->whereNull('media_path')
            ->orderByDesc('ordering') // newest first: they expire soonest and are what users open
            ->limit(50)
            ->get();

        foreach ($messages as $message) {
            if (microtime(true) > $deadline) {
                break;
            }

            if ($message->ordering && Carbon::parse($message->ordering)->lt(now()->subDays(14))) {
                $message->media_sync_status = Status::MEDIA_SYNC_UNAVAILABLE;
                $message->save();
                continue;
            }

            $whatsappAccount = WhatsappAccount::find($message->whatsapp_account_id);

            if (!$whatsappAccount || !$whatsappAccount->access_token) {
                $message->media_sync_status = Status::MEDIA_SYNC_UNAVAILABLE;
                $message->save();
                continue;
            }

            try {
                $media = $lib->getMediaUrl($message->media_id, $whatsappAccount->access_token);

                $message->media_path        = $lib->storedMediaToLocal($media['url'], $message->media_id, $whatsappAccount->access_token, $message->user_id);
                $message->media_url         = $media['url'];
                $message->media_sync_status = Status::MEDIA_SYNC_DONE;
            } catch (Exception $ex) {
                $message->media_sync_status = Status::MEDIA_SYNC_UNAVAILABLE;
                $message->error_message     = strLimit($ex->getMessage(), 250);
            }

            $message->save();
        }
    }

}
