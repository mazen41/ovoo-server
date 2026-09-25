<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Language;

class AppController extends Controller
{
    public function generalSetting()
    {
        $notify[] = 'General setting data';
        return apiResponse("general_setting", "success", $notify, [
            'general_setting'       => gs(),
            'social_login_redirect' => route('user.social.login.callback', ''),
        ]);
    }

    public function getCountries()
    {
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $notify[]    = 'Country List';

        foreach ($countryData as $k => $country) {
            $countries[] = [
                'country'      => $country->country,
                'dial_code'    => $country->dial_code,
                'country_code' => $k,
            ];
        }
        return apiResponse("country_data", "success", $notify, [
            'countries' => $countries
        ]);
    }

    public function getLanguage($code)
    {
        $languages     = Language::get();
        $languageCodes = $languages->pluck('code')->toArray();

        if (!in_array($code, $languageCodes)) {
            $notify[] = 'Invalid code given';
            return apiResponse("invalid_code", "error", $notify);
        }

        $jsonFile = file_get_contents(resource_path('lang/' . $code . '.json'));
        $notify[] = 'Language';

        return apiResponse("language", "success", $notify, [
            'languages'  => $languages,
            'file'       => json_decode($jsonFile) ?? [],
            'image_path' => getFilePath('language')
        ]);
    }

    public function policies()
    {
        $policies = getContent('policy_pages.element', orderById: true);
        $notify[] = 'All policies';

        return apiResponse("policy_data", "success", $notify, [
            'policies' => $policies,
        ]);
    }


    public function faq()
    {
        $faq      = getContent('faq.element', orderById: true);
        $notify[] = 'FAQ';
        return apiResponse("faq", "success", $notify, [
            'faq' => $faq,
        ]);
    }
}
