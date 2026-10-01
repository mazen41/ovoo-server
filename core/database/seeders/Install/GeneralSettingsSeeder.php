<?php

namespace Database\Seeders\Install;

/**
 * Reference data for the general_settings table, generated from install/database.sql.
 *
 * Primary keys are written explicitly because other seeded tables reference them by id.
 */
class GeneralSettingsSeeder extends InstallSeeder
{
    public function run(): void
    {
        $this->insertRows('general_settings', [
            ['id' => '1', 'site_name' => 'OvoWpp', 'cur_text' => 'USD', 'cur_sym' => '$', 'email_from' => 'info@ovosolution.com', 'email_from_name' => '{{site_name}}', 'email_template' => '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Notification</title>
    <style>
        /* General Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: \'Open Sans\', Arial, sans-serif;
            background-color: #f4f4f4;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
            width: 100%;
        }

        img {
            display: block;
            border: 0;
            line-height: 0;
        }

        a {
            color: #ff600036;
            text-decoration: none;
        }

        .email-wrapper {
            width: 100%;
            background-color: #f4f4f4;
            padding: 30px 0;
        }

        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .email-header {
            background-color: #ff600036;
            color: #000;
            text-align: center;
            padding: 20px;
            font-size: 16px;
            font-weight: 600;
        }

        /* Logo */
        .email-logo {
            text-align: center;
            padding: 40px 0;
        }

        .email-logo img {
            max-width: 180px;
            margin: 0 auto;
        }

        /* Content */
        .email-content {
            padding: 0 30px 30px 30px;
            text-align: left;
        }

        .email-content h1 {
            font-size: 22px;
            color: #414a51;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .email-content p {
            font-size: 16px;
            color: #7f8c8d;
            line-height: 1.6;
            margin: 20px 0;
        }

        .email-divider {
            margin: 20px auto;
            width: 60px;
            border-bottom: 3px solid #ff600036;
        }

        /* Footer */
        .email-footer {
            background-color: #ff600036;
            color: #000;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            padding: 20px;
        }


        /* Responsive Design */
        @media only screen and (max-width: 480px) {
            .email-content {
                padding: 20px;
            }

            .email-header,
            .email-footer {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <table class="email-container" cellpadding="0" cellspacing="0">
            <tbody style="border: 1px solid #ffddc9">
                <tr>
                    <td>
                        <!-- Header -->
                        <div class="email-header">
                            System Generated Email
                        </div>

                        
                        <!-- Logo -->
                        <div class="email-logo">
                            <a href="#">
                                <img src="https://i.ibb.co.com/dLYyDXX/OVO-logo-for-Light-BG.png" alt="Company Logo">
                            </a>
                        </div>
                        <!-- Content -->
                        <div class="email-content">
                            <h1>Hello {{username}}</h1>
                            <p>{{message}}</p>
                        </div>

                        <!-- Footer -->
                        <div class="email-footer">
                            &copy; 2024 <a href="#" style="color: #0087ff;">{{site_name}}</a>. All Rights Reserved.
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>', 'sms_template' => 'hi {{fullname}} ({{username}}), {{message}}', 'referral_amount_percentage' => '5.00', 'subscription_notify_before' => '2', 'webhook_verify_token' => 'ovowpp', 'whatsapp_embedded_signup' => '0', 'whatsapp_coexistence_signup' => '0', 'meta_app_id' => null, 'meta_app_secret' => null, 'meta_configuration_id' => null, 'meta_coexistence_configuration_id' => null, 'google_maps_api' => null, 'sms_from' => '{{site_name}}', 'push_title' => '{{site_name}}', 'push_template' => 'hi {{fullname}} ({{username}}), {{message}}', 'base_color' => '25d466', 'mail_config' => '{"name":"php"}', 'sms_config' => '{"name":"infobip","clickatell":{"api_key":"----------------"},"infobip":{"username":"------------8888888","password":"-----------------"},"message_bird":{"api_key":"-------------------"},"nexmo":{"api_key":"----------------------","api_secret":"----------------------"},"sms_broadcast":{"username":"----------------------","password":"-----------------------------"},"twilio":{"account_sid":"-----------------------","auth_token":"---------------------------","from":"----------------------"},"text_magic":{"username":"-----------------------","apiv2_key":"-------------------------------"},"custom":{"method":"get","url":"https:\\/\\/hostname.com\\/demo-api-v1","headers":{"name":["api_key"],"value":["test_api 555"]},"body":{"name":["from_number"],"value":["5657545757"]}}}', 'firebase_config' => '{"apiKey":"AIzaSyCb6zm7_8kdStXjZMgLZpwjGDuTUg0e_qM","authDomain":"flutter-prime-df1c5.firebaseapp.com","projectId":"flutter-prime-df1c5","storageBucket":"flutter-prime-df1c5.appspot.com","messagingSenderId":"274514992002","appId":"1:274514992002:web:4d77660766f4797500cd9b","measurementId":"G-KFPM07RXRC"}', 'global_shortcodes' => '{
    "site_name":"Name of your site",
    "site_currency":"Currency of your site",
    "currency_symbol":"Symbol of currency"
}', 'kv' => '0', 'ev' => '0', 'en' => '1', 'sv' => '0', 'sn' => '1', 'pn' => '0', 'force_ssl' => '0', 'in_app_payment' => '1', 'maintenance_mode' => '0', 'secure_password' => '0', 'agree' => '1', 'multi_language' => '1', 'registration' => '1', 'active_template' => 'basic', 'socialite_credentials' => '{"google":{"client_id":"609720830799-cmdk7assb6j5i6l436ro72qdg05p080a.apps.googleusercontent.com","client_secret":"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","status":1,"info":"Quickly set up Google Login for easy and secure access to your website for all users"},"facebook":{"client_id":"------","client_secret":"sdfsdf","status":1,"info":"Set up Facebook Login for fast, secure user access and seamless integration with your website."},"linkedin":{"client_id":"78l4zid62xp3yb","client_secret":"WPL_AP1.kz1krlM9SsuZlWMS.XkPh9A==","status":1,"info":"Set up LinkedIn Login for professional, secure access and easy user authentication on your website."}}', 'last_cron' => '2025-05-30 18:27:03', 'available_version' => '1.0', 'system_customized' => '0', 'paginate_number' => '20', 'currency_format' => '2', 'time_format' => 'h:i A', 'date_format' => 'd/m/Y', 'allow_precision' => '2', 'thousand_separator' => ',', 'preloader_image' => '683ecdc7f35db1748946375.gif', 'pusher_config' => '{"pusher_app_id":"------------","pusher_app_key":"------------","pusher_app_secret":"------------","pusher_app_cluster":"------------"}', 's3_config' => null, 'created_at' => null, 'updated_at' => '2025-06-19 11:57:48'],
        ]);
    }
}
