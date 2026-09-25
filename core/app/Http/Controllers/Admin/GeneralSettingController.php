<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Frontend;
use App\Rules\FileTypeValidate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GeneralSettingController extends Controller
{
    public function general()
    {
        $pageTitle       = 'General Setting';
        $timezones       = timezone_identifiers_list();
        $currentTimezone = array_search(config('app.timezone'), $timezones);
        return view('admin.setting.general', compact('pageTitle', 'timezones', 'currentTimezone'));
    }

    public function generalUpdate(Request $request)
    {
        $request->validate([
            'site_name'                  => 'required|string|max:40',
            'cur_text'                   => 'required|string|max:40',
            'cur_sym'                    => 'required|string|max:40',
            'base_color'                 => 'nullable|regex:/^[a-f0-9]{6}$/i',
            'timezone'                   => 'required|integer',
            'currency_format'            => 'required|in:1,2,3',
            'paginate_number'            => 'required|integer',
            'time_format'                => ['required', Rule::in(supportedTimeFormats())],
            'date_format'                => ['required', Rule::in(supportedDateFormats())],
            'thousand_separator'         => ['required', Rule::in(array_keys(supportedThousandSeparator()))],
            'allow_precision'            => 'required|integer|gt:0|lte:8',
            'subscription_notify_before' => 'required|integer|gt:0|lte:7',
            'referral_amount_percentage' => 'required|numeric|gt:0|lt:100',
            'meta_app_id'                => 'nullable|string',
            'meta_app_secret'            => 'nullable|string',
            'meta_configuration_id'      => 'nullable|string',
            'webhook_verify_token'       => 'nullable|string',
        ]);

        $timezones = timezone_identifiers_list();
        $timezone  = @$timezones[$request->timezone] ?? 'UTC';

        $general                             = gs();
        $general->site_name                  = $request->site_name;
        $general->cur_text                   = $request->cur_text;
        $general->cur_sym                    = $request->cur_sym;
        $general->paginate_number            = $request->paginate_number;
        $general->base_color                 = str_replace('#', '', $request->base_color);
        $general->currency_format            = $request->currency_format;
        $general->time_format                = $request->time_format;
        $general->date_format                = $request->date_format;
        $general->allow_precision            = $request->allow_precision;
        $general->thousand_separator         = $request->thousand_separator;
        $general->subscription_notify_before = $request->subscription_notify_before;
        $general->referral_amount_percentage = $request->referral_amount_percentage;
        $general->meta_app_id                = $request->meta_app_id;
        $general->meta_app_secret            = $request->meta_app_secret;
        $general->meta_configuration_id      = $request->meta_configuration_id;
        $general->webhook_verify_token       = $request->webhook_verify_token;
        $general->google_maps_api            = $request->google_maps_api;

        $general->save();

        $timezoneFile = config_path('timezone.php');
        $content      = '<?php $timezone = "' . $timezone . '" ?>';
        file_put_contents($timezoneFile, $content);

        $notify[] = ['success', 'General setting updated successfully'];
        return back()->withNotify($notify);
    }



    public function pusherConfiguration()
    {
        $pageTitle = 'Pusher Configuration';
        $general   = gs();
        return view('admin.setting.pusher_configuration', compact('pageTitle', 'general'));
    }

    public function pusherConfigurationUpdate(Request $request)
    {
        //pusher config
        $pusherConfigFile = config_path('pusher.php');

        $pusherConfig = '<?php' . PHP_EOL .
            '$pusherAppId       = ' . '"' . $request->pusher_app_id . '"' . ";" . PHP_EOL .
            '$pusherAppKey      =' . '"' . $request->pusher_app_key . '"' . ";" . PHP_EOL .
            '$pusherAppSecret   =' . '"' . $request->pusher_app_secret . '"' . ';' . PHP_EOL .
            '$pusherAppCluster  =' . '"' . $request->pusher_app_cluster . '"' . ';' . PHP_EOL .
            "?>";

        file_put_contents($pusherConfigFile, $pusherConfig);

        $pusherConfig = [
            'pusher_app_id'       => $request->pusher_app_id,
            'pusher_app_key'      => $request->pusher_app_key,
            'pusher_app_secret'   => $request->pusher_app_secret,
            'pusher_app_cluster'  => $request->pusher_app_cluster,
        ];

        $general = gs();
        $general->pusher_config = $pusherConfig;
        $general->save();

        $notify[] = ['success', 'Pusher configuration updated successfully'];
        return back()->withNotify($notify);
    }



    public function systemConfiguration()
    {
        $pageTitle      = 'System Configuration';
        $configurations = json_decode(file_get_contents(resource_path('views/admin/setting/configuration.json')));
        return view('admin.setting.configuration', compact('pageTitle', 'configurations'));
    }


    public function systemConfigurationUpdate($key)
    {
        try {
            $general   = gs();
            $newStatus = !$general->$key;

            $general->$key = $newStatus;
            $general->save();

            return response()->json([
                'success'    => true,
                'new_status' => $newStatus
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ]);
        }
    }


    public function logoIcon()
    {
        $pageTitle = 'Brand Setting';
        return view('admin.setting.logo_icon', compact('pageTitle'));
    }

    public function logoIconUpdate(Request $request)
    {
        $request->validate([
            'logo'    => ['image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'favicon' => ['image', new FileTypeValidate(['png'])],
        ]);
        $path = getFilePath('logoIcon');

        if ($request->hasFile('logo')) {
            try {
                fileUploader($request->logo, $path, filename: 'logo.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the logo'];
                return back()->withNotify($notify);
            }
        }
        if ($request->hasFile('logo_dark')) {
            try {
                fileUploader($request->logo_dark, $path, filename: 'logo_dark.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the logo'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('favicon')) {
            try {
                fileUploader($request->favicon, $path, filename: 'favicon.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the favicon'];
                return back()->withNotify($notify);
            }
        }
        $notify[] = ['success', 'Brand setting updated successfully'];
        return back()->withNotify($notify);
    }

    public function customCss()
    {
        $pageTitle   = 'Custom CSS';
        $file        = activeTemplate(true) . 'css/custom.css';
        $fileContent = @file_get_contents($file);
        return view('admin.setting.custom_css', compact('pageTitle', 'fileContent'));
    }

    public function sitemap()
    {
        $pageTitle   = 'Sitemap XML';
        $file        = 'sitemap.xml';
        $fileContent = @file_get_contents($file);
        return view('admin.setting.sitemap', compact('pageTitle', 'fileContent'));
    }

    public function sitemapSubmit(Request $request)
    {
        $file = 'sitemap.xml';
        if (!file_exists($file)) {
            fopen($file, "w");
        }
        file_put_contents($file, $request->sitemap);
        $notify[] = ['success', 'Sitemap updated successfully'];
        return back()->withNotify($notify);
    }



    public function robot()
    {
        $pageTitle   = 'Robots TXT';
        $file        = 'robots.xml';
        $fileContent = @file_get_contents($file);
        return view('admin.setting.robots', compact('pageTitle', 'fileContent'));
    }

    public function robotSubmit(Request $request)
    {
        $file = 'robots.xml';
        if (!file_exists($file)) {
            fopen($file, "w");
        }
        file_put_contents($file, $request->robots);
        $notify[] = ['success', 'Robots txt updated successfully'];
        return back()->withNotify($notify);
    }


    public function customCssSubmit(Request $request)
    {
        $file = activeTemplate(true) . 'css/custom.css';
        if (!file_exists($file)) {
            fopen($file, "w");
        }
        file_put_contents($file, $request->css);
        $notify[] = ['success', 'CSS updated successfully'];
        return back()->withNotify($notify);
    }

    public function maintenanceMode()
    {
        $pageTitle   = 'Maintenance Mode';
        $maintenance = Frontend::where('data_keys', 'maintenance.data')->firstOrFail();
        return view('admin.setting.maintenance', compact('pageTitle', 'maintenance'));
    }

    public function maintenanceModeSubmit(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'image'       => ['nullable', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ]);
        $general                   = gs();
        $general->maintenance_mode = $request->status ? Status::ENABLE : Status::DISABLE;
        $general->save();

        $maintenance = Frontend::where('data_keys', 'maintenance.data')->firstOrFail();
        $image       = @$maintenance->data_values->image;
        if ($request->hasFile('image')) {
            try {
                $old   = $image;
                $image = fileUploader($request->image, getFilePath('maintenance'), getFileSize('maintenance'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $maintenance->data_values = [
            'description' => $request->description,
            'image'       => $image
        ];
        $maintenance->save();

        $notify[] = ['success', 'Maintenance mode updated successfully'];
        return back()->withNotify($notify);
    }

    public function cookie()
    {
        $pageTitle = 'GDPR Cookie';
        $cookie    = Frontend::where('data_keys', 'cookie.data')->firstOrFail();
        return view('admin.setting.cookie', compact('pageTitle', 'cookie'));
    }

    public function cookieSubmit(Request $request)
    {

        $request->validate([
            'short_desc'  => 'required|string|max:255',
            'description' => 'required',
        ]);
        $cookie              = Frontend::where('data_keys', 'cookie.data')->firstOrFail();
        $cookie->data_values = [
            'short_desc'  => $request->short_desc,
            'description' => $request->description,
            'status'      => $request->status ? Status::ENABLE : Status::DISABLE,
        ];
        $cookie->save();
        $notify[] = ['success', 'Cookie policy updated successfully'];
        return back()->withNotify($notify);
    }


    public function socialiteCredentials()
    {
        $pageTitle = 'Social Login Setting';
        return view('admin.setting.social_credential', compact('pageTitle'));
    }

    public function updateSocialiteCredentialStatus($key)
    {
        $general     = gs();
        $credentials = $general->socialite_credentials;
        try {
            $credentials->$key->status = $credentials->$key->status == Status::ENABLE ? Status::DISABLE : Status::ENABLE;
        } catch (\Throwable $th) {
            abort(404);
        }

        $general->socialite_credentials = $credentials;
        $general->save();

        $notify[] = ['success', 'Status changed successfully'];
        return back()->withNotify($notify);
    }

    public function updateSocialiteCredential(Request $request, $key)
    {
        $general     = gs();
        $credentials = $general->socialite_credentials;
        try {
            @$credentials->$key->client_id     = $request->client_id;
            @$credentials->$key->client_secret = $request->client_secret;
        } catch (\Throwable $th) {
            abort(404);
        }
        $general->socialite_credentials = $credentials;
        $general->save();

        $notify[] = ['success', ucfirst($key) . ' credential updated successfully'];
        return back()->withNotify($notify);
    }

    public function inAppPurchase()
    {
        $pageTitle  = 'In App Purchase Configuration - Google Play Store';
        $data       = null;
        $fileExists = file_exists(getFilePath('appPurchase') . '/google_pay.json');
        return view('admin.setting.in_app_purchase.google', compact('pageTitle', 'data', 'fileExists'));
    }

    public function inAppPurchaseConfigure(Request $request)
    {
        $request->validate([
            'file' => ['required', new FileTypeValidate(['json'])],
        ]);

        try {
            fileUploader($request->file, getFilePath('appPurchase'), filename: 'google_pay.json');
        } catch (\Exception $exp) {
            $notify[] = ['error', 'Couldn\'t upload your file'];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', 'Configuration file uploaded successfully'];
        return back()->withNotify($notify);
    }

    public function inAppPurchaseFileDownload()
    {
        $filePath = getFilePath('appPurchase') . '/google_pay.json';
        if (!file_exists(getFilePath('appPurchase') . '/google_pay.json')) {
            $notify[] = ['success', "File not found"];
            return back()->withNotify($notify);
        }
        return response()->download($filePath);
    }

    public function pwaIcon()
    {
        $pageTitle = 'PWA Setting';
        return view('admin.setting.pwa', compact('pageTitle'));
    }

    public function pwaIconUpdate(Request $request)
    {
        $request->validate([
            'pwa_small_icon' => ['image', new FileTypeValidate(['png']), "dimensions:width=192,height=192"],
            'pwa_large_icon' => ['image', new FileTypeValidate(['png']), "dimensions:width=512,height=512"],
        ]);

        $path = getFilePath('logoIcon');

        if ($request->hasFile('pwa_small_icon')) {
            try {
                fileUploader($request->pwa_small_icon, $path, filename: 'pwa_small_icon.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the PWA small icon'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('pwa_large_icon')) {
            try {
                fileUploader($request->pwa_large_icon, $path, filename: 'pwa_large_icon.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the PWA large icon'];
                return back()->withNotify($notify);
            }
        }
        $notify[] = ['success', 'PWA images updated successfully'];
        return back()->withNotify($notify);
    }
}
