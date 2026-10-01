<?php

namespace Database\Seeders\Install;

/**
 * Reference data for the extensions table, generated from install/database.sql.
 *
 * Primary keys are written explicitly because other seeded tables reference them by id.
 */
class ExtensionsSeeder extends InstallSeeder
{
    public function run(): void
    {
        $this->insertRows('extensions', [
            ['id' => '1', 'act' => 'tawk-chat', 'name' => 'Tawk.to', 'description' => 'Key location is shown bellow', 'info' => 'Tawk.to offers live chat support, helping you communicate with visitors and boost customer satisfaction', 'image' => 'tawky_big.png', 'script' => '<script>
                        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
                        (function(){
                        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                        s1.async=true;
                        s1.src="https://embed.tawk.to/{{app_key}}";
                        s1.charset="UTF-8";
                        s1.setAttribute("crossorigin","*");
                        s0.parentNode.insertBefore(s1,s0);
                        })();
                    </script>', 'shortcode' => '{"app_key":{"title":"App Key","value":"121"}}', 'support' => 'twak.png', 'status' => '0', 'created_at' => '2019-10-18 11:16:05', 'updated_at' => '2025-05-07 10:35:27'],
            ['id' => '2', 'act' => 'google-recaptcha2', 'name' => 'Google Recaptcha 2', 'description' => 'Key location is shown bellow', 'info' => 'Google reCAPTCHA v2 blocks bots, reducing spam and enhancing website security', 'image' => 'recaptcha3.png', 'script' => '
<script src="https://www.google.com/recaptcha/api.js"></script>
<div class="g-recaptcha" data-sitekey="{{site_key}}" data-callback="verifyCaptcha"></div>
<div id="g-recaptcha-error"></div>', 'shortcode' => '{"site_key":{"title":"Site Key","value":"6LdPC88fAAAAADQlUf_DV6Hrvgm-pZuLJFSLDOWV"},"secret_key":{"title":"Secret Key","value":"6LdPC88fAAAAAG5SVaRYDnV2NpCrptLg2XLYKRKB"}}', 'support' => 'recaptcha.png', 'status' => '0', 'created_at' => '2019-10-18 11:16:05', 'updated_at' => '2025-06-01 08:39:13'],
            ['id' => '3', 'act' => 'custom-captcha', 'name' => 'Custom Captcha', 'description' => 'Just put any random string', 'info' => 'Custom Captcha checks users with simple challenges, stopping spam and keeping your site safe', 'image' => 'customcaptcha.png', 'script' => null, 'shortcode' => '{"random_key":{"title":"Random String","value":"SecureString"}}', 'support' => 'na', 'status' => '0', 'created_at' => '2019-10-18 11:16:05', 'updated_at' => '2025-06-18 02:54:02'],
            ['id' => '4', 'act' => 'google-analytics', 'name' => 'Google Analytics', 'description' => 'Key location is shown bellow', 'info' => '
Google Analytics tracks website traffic and user behavior, helping you improve performance and understand your audience', 'image' => 'google_analytics.png', 'script' => '<script async src="https://www.googletagmanager.com/gtag/js?id={{measurement_id}}"></script>
                <script>
                  window.dataLayer = window.dataLayer || [];
                  function gtag(){dataLayer.push(arguments);}
                  gtag("js", new Date());
                
                  gtag("config", "{{measurement_id}}");
                </script>', 'shortcode' => '{"measurement_id":{"title":"Measurement ID","value":"------"}}', 'support' => 'ganalytics.png', 'status' => '0', 'created_at' => null, 'updated_at' => '2021-05-03 22:19:12'],
            ['id' => '5', 'act' => 'fb-comment', 'name' => 'Facebook Comment ', 'description' => 'Key location is shown bellow', 'info' => 'Facebook Comment lets users share feedback on your site, increasing engagement and social interaction', 'image' => 'Facebook.png', 'script' => '<div id="fb-root"></div><script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v4.0&appId={{app_key}}&autoLogAppEvents=1"></script>', 'shortcode' => '{"app_key":{"title":"App Key","value":"----"}}', 'support' => 'fb_com.png', 'status' => '0', 'created_at' => null, 'updated_at' => '2022-03-21 17:18:36'],
        ]);
    }
}
