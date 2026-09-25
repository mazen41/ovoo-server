<?php

use Illuminate\Support\Facades\Route;

Route::namespace('User\Auth')->name('user.')->middleware('guest')->group(function () {
    Route::controller('LoginController')->group(function () {
        Route::get('login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->middleware('auth')->withoutMiddleware('guest')->name('logout');
    });

    Route::controller('RegisterController')->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register');
        Route::post('check-user', 'checkUser')->name('checkUser')->withoutMiddleware('guest');
    });

    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
        Route::post('password/reset', 'reset')->name('password.update');
    });

    Route::controller('SocialiteController')->group(function () {
        Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
        Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
    });
});

Route::middleware('auth')->name('user.')->group(function () {

    Route::get('user-data', 'User\UserController@userData')->name('data');
    Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');

    //authorization
    Route::middleware('registration.complete')->namespace('User')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('2fa.verify');
    });

    Route::middleware(['check.status', 'registration.complete'])->group(function () {

        Route::namespace('User')->group(function () {

            Route::controller('UserController')->group(function () {
                Route::get('dashboard', 'home')->name('home')->middleware('agent.permission:view dashboard');
                Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');

                // 2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                // KYC
                Route::get('kyc-form', 'kycForm')->name('kyc.form');
                Route::get('kyc-data', 'kycData')->name('kyc.data');
                Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                // Our Developer
                Route::get('development-credential', 'userDevelopmentCredential')->name('development.credential')->middleware('parent.user');

                // Report
                Route::any('deposit/history', 'depositHistory')->name('deposit.history')->middleware('parent.user');
                Route::get('transactions', 'transactions')->name('transactions')->middleware('parent.user');

                Route::post('add-device-token', 'addDeviceToken')->name('add.device.token');

                Route::get('notification/settings', 'notificationSetting')->name('notification.setting');
                Route::post('notification/settings', 'notificationSettingsUpdate')->name('notification.setting');

                // External api

            });

            // Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
            });

            // Withdraw
            Route::controller('WithdrawController')->prefix('withdraw')->middleware('parent.user')->name('withdraw')->group(function () {
                Route::middleware('kyc')->group(function () {
                    Route::get('', 'withdrawMoney');
                    Route::post('/', 'withdrawStore')->name('.money');
                    Route::get('preview', 'withdrawPreview')->name('.preview');
                    Route::post('preview', 'withdrawSubmit')->name('.submit');
                });
                Route::get('history', 'withdrawLog')->name('.history');
            });

            //whatsapp account
            Route::controller('WhatsappAccountController')->prefix('whatsapp-account')->middleware('parent.user')->name('whatsapp.account.')->group(function () {
                Route::get('/', 'whatsappAccounts')->name('index');
                Route::get('add-account', 'addWhatsappAccount')->name('add');

                Route::post('embedded-signup', 'embeddedSignup')->name('embedded.signup');
                Route::post('access-token', 'accessToken')->name('access.token');
                Route::post('pin', 'whatsappPin')->name('whatsapp.pin');

                Route::post('add-account/store', 'storeWhatsappAccount')->name('store')->middleware("has.subscription");
                Route::get('check/{id}', 'whatsappAccountVerificationCheck')->name('verification.check');
                Route::get('phone-number/verification-check/{id}', 'whatsappPhoneNumberVerificationCheck')->name('phone.number.verification.check');
                Route::get('connect/{id}', 'whatsappAccountConnect')->name('connect');
                Route::get('setting/{id}', 'whatsappAccountSetting')->name('setting');
                Route::post('setting/{id}', 'whatsappAccountSettingConfirm')->name('setting.confirm');
                Route::post('send/test/message/{id}', 'sendTestMessage')->name('send.test.message');
                Route::post('delete/{id}', 'accountDelete')->name('delete');
                Route::get('connect-webhook/{id}', 'connectWebhook')->name('connect.webhook');
            });

            // Whatsapp
            Route::controller('WhatsappController')->prefix('whatsapp')->name('whatsapp.')->group(function () {
                Route::get('config/webhook', 'whatsappWebhook')->name('webhook.config');
            });

            // Inbox
            Route::name('inbox.')->prefix('inbox')->controller("InboxController")->middleware('agent.permission:view inbox')->group(function () {
                Route::get('', 'list')->name('list');
                Route::get('conversation-list', 'conversationList')->name('conversation.list');
                Route::get('conversation-message/{id}', 'conversationMessages')->name('conversation.message');
                Route::post('conversation/status/{conversationId}', 'changeConversationStatus')->name('conversation.status');
                Route::post('conversation/ai-reply/{conversationId}', 'changeConversationAIReplyStatus')->name('conversation.ai.reply');
                Route::get('conversation/assign/{conversationId}/{agentId}', 'assignConversation')->name('conversation.assign');
                Route::get('conversation/details/{conversationId}', 'contactDetails')->name('contact.details');
                Route::post('note/store', 'storeNote')->name('note.store');
                Route::post('note/delete/{id}', 'deleteNote')->name('note.delete');
                Route::post('conversation/clear/{conversationId}', 'clearConversationMessage')->name('conversation.clear');
                Route::get('conversation-options/{id}', 'conversationOptions')->name('conversation.options');

                Route::middleware('has.subscription', 'has.whatsapp')->group(function () {
                    Route::prefix('chat/message')->name('message.')->group(function () {
                        Route::post('send', 'sendMessage')->name('send')->middleware('agent.permission:send message');
                        Route::post('template/send', 'sendTemplateMessage')->name('template.send')->middleware('agent.permission:send message');
                        Route::post('resend', 'resendMessage')->name('resend')->middleware('agent.permission:send message');
                        Route::get('status/{conversationId}', 'updateMessageStatus')->name('status');
                        Route::post('generate', 'generateAiMessage')->name('generate')->middleware('agent.permission:send message');
                        Route::post('translate', 'translateAiMessage')->name('translate')->middleware('agent.permission:send message');
                    });
                    Route::get('media/download/{mediaId}', 'downloadMedia')->name('media.download');
                });
            });

            // Subscription
            Route::controller('SubscriptionController')->prefix('subscription')->middleware('parent.user')->name('subscription.')->group(function () {
                Route::get('index', 'index')->name('index');
                Route::get('auto-renewal', 'autoRenewal')->name('auto.renewal');
                Route::get('invoice/{subscriptionId}', 'invoice')->name('invoice');
                Route::get('invoice/print/{subscriptionId}', 'printInvoice')->name('invoice.print');
                Route::get('invoice/download/{subscriptionId}', 'downloadInvoice')->name('invoice.download');
            });
            // Short link
            Route::controller('ShortLinkController')->prefix('shortlink')->name('shortlink.')->group(function () {
                Route::get('/index', 'index')->name('index')->middleware('agent.permission:view shortlink');
                Route::get('/create', 'create')->name('create')->middleware('agent.permission:add shortlink');
                Route::get('/edit/{id}', 'edit')->name('edit')->middleware('agent.permission:edit shortlink');
                Route::post('/generate', 'storeShortLink')->name('generate')->middleware('has.subscription')->middleware('agent.permission:add shortlink');
                Route::post('/update/{id?}', 'storeShortLink')->name('update')->middleware('agent.permission:edit shortlink');
                Route::post('/delete/{id}', 'delete')->name('delete')->middleware('agent.permission:delete shortlink');
                Route::post('/check-code', 'checkCode')->name('check.code');
                Route::post('/generate-code', 'generateRandomCode')->name('generate.code');
            });

            //Floater
            Route::controller('FloaterController')->prefix('floater')->name('floater.')->group(function () {
                Route::get('/index', 'index')->name('index')->middleware('agent.permission:view floater');
                Route::get('/create', 'create')->name('create')->middleware('agent.permission:add floater');
                Route::post('/generate', 'floaterGenerate')->name('generate')->middleware('agent.permission:add floater,has.subscription');
                Route::post('/store/{id?}', 'storeFloater')->name('store')->middleware('agent.permission:add floater');
                Route::post('/delete/{id}', 'deleteFloater')->name('delete')->middleware('agent.permission:delete floater');
                Route::get('script/{id}', 'getScript')->name('script')->middleware('agent.permission:view floater');
            });

            // Cta URL
            Route::controller('CTAUrlController')->prefix('cta-url')->name('cta-url.')->group(function () {
                Route::get('index', 'index')->name('index')->middleware('agent.permission:view cta url');
                Route::get('create', 'create')->name('create')->middleware('agent.permission:add cta url');
                Route::post('store', 'store')->name('store')->middleware('agent.permission:add cta url');
                Route::post('delete/{id}', 'delete')->name('delete')->middleware('agent.permission:delete cta url');

                Route::get('get-list', 'getList');
            });

            // Interactive List
            Route::controller('InteractiveListController')->prefix('interactive-list')->name('interactive-list.')->group(function () {
                Route::get('index', 'index')->name('index')->middleware('agent.permission:view interactive list');
                Route::get('create', 'create')->name('create')->middleware('agent.permission:add interactive list');
                Route::post('store', 'store')->name('store')->middleware('agent.permission:add interactive list');
                Route::post('delete/{id}', 'delete')->name('delete')->middleware('agent.permission:delete interactive list');

                Route::get('get-list', 'getList');
            });

            // E-commerce Configuration
            Route::controller('EcommerceConfigurationController')->prefix('ecommerce')->name('ecommerce.')->group(function () {
                // Woo Commerce Configuration
                Route::prefix('woo-commerce')->name('woocommerce.')->group(function () {
                    Route::get('config', 'wooCommerceConfig')->name('config')->middleware('agent.permission:update ecommerce configuration');
                    Route::post('config', 'wooCommerceConfigStore')->name('config.store')->middleware('agent.permission:update ecommerce configuration');
                    Route::get('products', 'wooCommerceProducts')->name('products')->middleware('agent.permission:show ecommerce products');
                    Route::get('clear-cache', 'wooCommerceClearCache')->name('clear.cache')->middleware('agent.permission:show ecommerce products');
                    Route::post('create-order', 'createWooOrder')->name('create.order')->middleware('agent.permission:order products');
                });

                Route::get('fetch-products', 'fetchProducts')->name('fetch.products')->middleware('agent.permission:show ecommerce products');
            });

            // Automation
            Route::controller('AutomationController')->prefix('automation')->name('automation.')->group(function () {

                Route::get('ai-assistant/setting', 'aiAssistant')->name('ai.assistant')->middleware('agent.permission:ai assistant settings');
                Route::get('welcome-message', 'welcomeMessage')->name('welcome.message')->middleware('agent.permission:view welcome message');

                Route::middleware('has.subscription')->group(function () {
                    Route::post('ai-assistant/setting/store', 'aiAssistantStore')->name('ai.assistant.store')->middleware('agent.permission:ai assistant settings');
                    Route::middleware('has.whatsapp')->group(function () {
                        Route::post('welcome-message/{id?}', 'welcomeMessageStore')->name('welcome.message.store')->middleware('agent.permission:add welcome message');
                        Route::post('welcome-message/status/{id}', 'welcomeMessageStatus')->name('welcome.message.status')->middleware('agent.permission:edit welcome message');
                    });
                });
            });

            Route::controller('FlowBuilderController')->prefix('flow-builder')->name('flow.builder.')->group(function () {
                Route::get('/', 'index')->name('index')->middleware('agent.permission:view flow builder');
                Route::get('/create', 'create')->name('create')->middleware('agent.permission:add flow builder');
                Route::get('/edit/{id}', 'edit')->name('edit')->middleware('agent.permission:edit flow builder');
                Route::post('/upload-media', 'mediaUpload')->middleware('has.subscription');
                Route::post('/status/{id}', 'status')->name('status')->middleware(['has.subscription', 'agent.permission:edit flow builder']);
                Route::post('/store', 'store')->name('store')->middleware(['has.subscription', 'agent.permission:add flow builder']);
                Route::post('/update/{id}', 'update')->name('update')->middleware(['has.subscription', 'agent.permission:edit flow builder']);
                Route::post('/delete/{id}', 'delete')->name('delete')->middleware('agent.permission:delete flow builder');
            });

            // Referral
            Route::controller('ReferralController')->prefix('referral')->middleware('parent.user')->name('referral.')->group(function () {
                Route::get('index', 'index')->name('index');
            });

            // Contacts
            Route::controller('ContactController')->prefix('contact')->name('contact.')->group(function () {
                Route::get('list', 'list')->name('list')->middleware('agent.permission:view contact');
                Route::get('create', 'create')->name('create')->middleware('agent.permission:add contact');
                Route::get('edit/{id}', 'edit')->name('edit')->middleware('agent.permission:edit contact');
                Route::post('save', 'saveContact')->name('store')->middleware(['has.subscription', 'agent.permission:add contact']);
                Route::post('update/{id}', 'saveContact')->name('update')->middleware('agent.permission:edit contact');
                Route::post('delete/{id}', 'deleteContact')->name('delete')->middleware('agent.permission:delete contact');
                Route::get('search', 'searchContact')->name('search');
                Route::post('check-contact/{id?}', 'checkContact')->name('check');

                Route::post('block/{id}', 'contactStatus')->name('block')->middleware('agent.permission:block contact');
                Route::post('unblock/{id}', 'contactStatus')->name('unblock')->middleware('agent.permission:unblock contact');

                Route::get('download/csv', 'downloadCsv')->name('csv.download');
                Route::post('import', 'importContact')->name('import')->middleware(['agent.permission:add contact', 'has.subscription']);
                Route::get('template/download', 'templateDownload')->name('template.download');


                // Route::post('bulk-delete', 'bulkDelete')->name('bulk.delete')->middleware('agent.permission:delete contact');
                Route::post('bulk-delete', 'bulkDelete')->name('bulk.delete');
            });

            // Customer
            Route::controller('CustomerController')->prefix('customer')->name('customer.')->group(function () {
                Route::get('list', 'list')->name('list')->middleware('agent.permission:view customer');
                Route::get('create', 'create')->name('create')->middleware('agent.permission:add customer');
                Route::get('edit/{id}', 'edit')->name('edit')->middleware('agent.permission:edit customer');
                Route::post('save', 'saveContact')->name('store')->middleware(['has.subscription', 'agent.permission:add customer']);
                Route::post('update/{id}', 'saveContact')->name('update')->middleware('agent.permission:edit customer');
                Route::post('delete/{id}', 'deleteContact')->name('delete')->middleware('agent.permission:delete customer');
                Route::get('search', 'searchContact')->name('search');
            });

            // ContactList
            Route::controller('ContactListController')->prefix('contactlist')->name('contactlist.')->group(function () {
                Route::get('list', 'list')->name('list')->middleware('agent.permission:view contact list');
                Route::get('view/{id}', 'view')->name('view')->middleware('agent.permission:view list contact');
                Route::post('save', 'save')->name('save')->middleware('agent.permission:add contact list');
                Route::post('update/{id?}', 'save')->name('update')->middleware('agent.permission:edit contact list');
                Route::post('contact-add/{listId}', 'addContactToList')->name('contact.add')->middleware('agent.permission:add contact to list');
                Route::post('contact-remove/{id}', 'removeFromList')->name('contact.remove')->middleware('agent.permission:remove contact from list');
                Route::post('delete/{id}', 'delete')->name('delete')->middleware('agent.permission:delete contact list');
            });

            // Contact Tags
            Route::controller('ContactTagController')->prefix('contact-tag')->name('contacttag.')->group(function () {
                Route::get('list', 'list')->name('list')->middleware('agent.permission:view contact tag');
                Route::post('save', 'save')->name('save')->middleware('agent.permission:add contact tag');
                Route::post('update/{id}', 'save')->name('update')->middleware('agent.permission:edit contact tag');
                Route::post('delete/{id}', 'deleteTag')->name('delete')->middleware('agent.permission:delete contact tag');
            });

            // Templates
            Route::controller('TemplateController')->prefix('template')->name('template.')->group(function () {
                Route::get('index', 'index')->name('index')->middleware('agent.permission:view template');
                Route::get('create', 'createTemplate')->name('create')->middleware('agent.permission:add template');
                Route::get('create/carousel', 'createCarouselTemplate')->name('create.carousel')->middleware('agent.permission:add template');
                Route::post('create/carousel', 'storeCarouselTemplate')->name('create.carousel.store')->middleware('agent.permission:add template');
                Route::middleware('has.subscription')->group(function () {
                    Route::post('store', 'storeTemplate')->name('store')->middleware('agent.permission:add template');
                    Route::get('check/{id}', 'checkTemplateStatus')->name('verification.check');
                });
                Route::post('delete/{id}', 'deleteTemplate')->name('delete')->middleware('agent.permission:delete template');
                Route::any('/get-list', 'getTemplates')->name('get'); // get all templates
                Route::post('fetch-templates', 'fetchTemplates')->name('fetch');
            });

            // Campaign
            Route::controller('CampaignController')->prefix('campaign')->name('campaign.')->group(function () {
                Route::get('index', 'index')->name('index')->middleware('agent.permission:view campaign');
                Route::get('create', 'createCampaign')->name('create')->middleware('agent.permission:add campaign');
                Route::post('save', 'saveCampaign')->name('save')->middleware(['has.subscription', 'agent.permission:add campaign', 'has.whatsapp']);
                Route::get('report/{id}', 'report')->name('report')->middleware('agent.permission:view campaign');
            });

            // Purchase Plan
            Route::controller('PurchasePlanController')->prefix('purchase-plan')->name('purchase.plan.')->group(function () {
                Route::post('check-coupon', 'checkCoupon')->name('check.coupon');
                Route::post('store', 'store')->name('store');
            });

            // Manage agent
            Route::controller('ManageAgentController')->prefix('agent')->name('agent.')->group(function () {
                Route::get('list', 'list')->name('list')->middleware('agent.permission:view agent');
                Route::get('create', 'create')->name('create')->middleware('agent.permission:add agent');
                Route::get('edit/{id}', 'edit')->name('edit')->middleware('agent.permission:edit agent');
                Route::post('save', 'save')->name('save')->middleware('agent.permission:add agent')->middleware('has.subscription');
                Route::post('update/{id}', 'update')->name('update')->middleware('agent.permission:edit agent')->middleware('has.subscription');
                Route::post('delete/{id}', 'delete')->name('delete')->middleware('agent.permission:delete agent');
                Route::get('permissions/{id}', 'permissions')->name('permissions')->middleware('agent.permission:view permission');
                Route::post('permissions/save/{id}', 'updatePermissions')->name('permissions.update')->middleware('agent.permission:assign permission');
            });

            // Ip White List For External Api Service
            Route::controller('IpWhiteListController')->middleware('parent.user')->prefix('ip-white-list')->name('ip.white.list.')->group(function () {
                Route::get('index', 'index')->name('index');
                Route::post('store/{id?}', 'store')->name('store');
                Route::post('delete/{id}', 'delete')->name('delete');
            });
        });

        // Payment
        Route::prefix('deposit')->name('deposit.')->middleware('parent.user')->controller('Gateway\PaymentController')->group(function () {
            Route::any('/', 'deposit')->name('index');
            Route::post('insert', 'depositInsert')->name('insert');
            Route::get('confirm', 'depositConfirm')->name('confirm');
            Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
            Route::post('manual', 'manualDepositUpdate')->name('manual.update');
        });
    });
});
