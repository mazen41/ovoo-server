<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->name('api.')->group(function () {

    // Public media by-path (no auth — media_path contains UUIDs)
    Route::name('inbox.')->prefix('inbox')->controller('InboxController')->group(function () {
        Route::get('media/by-path/{path}', 'serveMediaByPath')->where('path', '.*');
    });

    // Public support attachment (no auth — filenames contain UUIDs)
    Route::prefix('attachment')->controller('TicketController')->group(function () {
        Route::get('support/{filename}', 'serveSupportAttachment')->name('attachment.support')->where('filename', '.*');
    });

    Route::controller('AppController')->group(function () {
        Route::get('general-setting', 'generalSetting');
        Route::get('get-countries', 'getCountries');
        Route::get('language/{key}', 'getLanguage');
        Route::get('policies', 'policies');
        Route::get('faq', 'faq');
    });

    Route::namespace('Auth')->group(function () {
        Route::controller('LoginController')->group(function () {
            Route::post('login', 'login');
            Route::post('check-token', 'checkToken');
            Route::post('social-login', 'socialLogin');
        });
        Route::post('register', 'RegisterController@register');

        Route::controller('ForgotPasswordController')->group(function () {
            Route::post('password/email', 'sendResetCodeEmail');
            Route::post('password/verify-code', 'verifyCode');
            Route::post('password/reset', 'reset');
        });
    });

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('user-data-submit', 'UserController@userDataSubmit');

        //authorization
        Route::middleware('registration.complete')->controller('AuthorizationController')->group(function () {
            Route::get('authorization', 'authorization');
            Route::get('resend-verify/{type}', 'sendVerifyCode');
            Route::post('verify-email', 'emailVerification');
            Route::post('verify-mobile', 'mobileVerification');
            Route::post('verify-g2fa', 'g2faVerification');
        });

        Route::middleware(['check.status'])->group(function () {

            Route::middleware('registration.complete')->group(function () {

                Route::controller('UserController')->group(function () {

                    Route::get('dashboard', 'dashboard');
                    Route::post('profile-setting', 'submitProfile');
                    Route::post('change-password', 'submitPassword');

                    Route::get('user-info', 'userInfo');
                    //KYC
                    Route::get('kyc-form', 'kycForm');
                    Route::post('kyc-submit', 'kycSubmit');

                    //Report
                    Route::middleware('parent.user')->group(function () {
                        Route::get('transactions', 'transactions');
                        Route::any('deposit/history', 'depositHistory');
                    });

                    Route::post('add-device-token', 'addDeviceToken');
                    Route::get('push-notifications', 'pushNotifications');
                    Route::post('push-notifications/read/{id}', 'pushNotificationsRead');

                    //2FA
                    Route::get('twofactor', 'show2faForm');
                    Route::post('twofactor/enable', 'create2fa');
                    Route::post('twofactor/disable', 'disable2fa');

                    Route::post('delete-account', 'deleteAccount');
                });

                // Withdraw
                Route::controller('WithdrawController')->middleware('parent.user')->group(function () {
                    Route::middleware('kyc')->group(function () {
                        Route::get('withdraw-method', 'withdrawMethod');
                        Route::post('withdraw-request', 'withdrawStore');
                        Route::post('withdraw-request/confirm', 'withdrawSubmit');
                    });
                    Route::get('withdraw/history', 'withdrawLog');
                });

                // Payment
                Route::controller('PaymentController')->group(function () {
                    Route::get('deposit/methods', 'methods');
                    Route::post('deposit/insert', 'depositInsert');
                });

                Route::controller('TicketController')->prefix('ticket')->group(function () {
                    Route::get('/', 'supportTicket');
                    Route::post('create', 'storeSupportTicket');
                    Route::get('view/{ticket}', 'viewTicket');
                    Route::post('reply/{id}', 'replyTicket');
                    Route::post('close/{id}', 'closeTicket');
                    Route::get('download/{attachment_id}', 'ticketDownload');
                });

                // Whatsapp account
                Route::controller('WhatsappAccountController')->prefix('whatsapp-account')->group(function () {
                    Route::get('/', 'whatsappAccounts');
                    Route::middleware('has.subscription')->group(function () {
                        Route::post('add-account/store', 'storeWhatsappAccount');
                        Route::get('check/{id}', 'whatsappAccountVerificationCheck');
                        Route::post('connect/{id}', 'whatsappAccountConnect');
                        Route::post('setting/{id}', 'whatsappAccountSettingConfirm');
                    });
                });

                // Inbox
                Route::name('inbox.')->prefix('inbox')->controller("InboxController")->group(function () {
                    Route::get('/', 'list')->name('list');
                    Route::get('conversation-list', 'conversationList');
                    Route::get('conversation-message/{id}', 'conversationMessages');
                    Route::get('conversation/details/{conversationId}', 'contactDetails');
                    Route::post('conversation/status/{conversationId}', 'changeConversationStatus');
                    Route::get('media/download/{mediaId}', 'downloadMedia');
                    Route::get('media/view/{mediaId}', 'viewMedia');
                    Route::post('note/store', 'storeNote');
                    Route::post('note/delete/{id}', 'deleteNote');

                    Route::middleware(['has.subscription', 'has.whatsapp'])->group(function () {
                        Route::post('chat/message/send', 'sendMessage')->middleware('agent.permission:send message');
                        Route::post('chat/message/resend', 'resendMessage')->middleware('agent.permission:send message');
                        Route::get('chat/message/status/{conversationId}', 'updateMessageStatus');
                    });
                });



                // Contact
                Route::controller('ContactController')->prefix('contact')->group(function () {
                    Route::get('list', 'list')->middleware('agent.permission:view contact');
                    Route::get('create', 'create')->middleware('agent.permission:add contact');
                    Route::post('save', 'saveContact')->middleware(['has.subscription', 'agent.permission:add contact']);
                    Route::post('update/{id}', 'saveContact')->middleware('agent.permission:edit contact');
                    Route::post('delete/{id}', 'deleteContact')->middleware('agent.permission:delete contact');
                    Route::get('search', 'searchContact');
                    Route::post('check-contact/{id?}', 'checkContact');
                    Route::get('download/csv', 'downloadCsv');
                    Route::post('import', 'importContact')->middleware(['agent.permission:add contact', 'has.subscription']);
                });

                //campaign
                Route::controller('CampaignController')->prefix('campaign')->name('campaign.')->group(function () {
                    Route::get('index', 'index')->name('index')->middleware('agent.permission:view campaign');
                    Route::get('create', 'create')->name('create')->middleware('agent.permission:add campaign');
                    Route::post('save', 'saveCampaign')->name('save')->middleware(['has.subscription', 'agent.permission:add campaign', 'has.whatsapp']);
                });

                // Contact List
                Route::controller('ContactListController')->prefix('contact-list')->group(function () {
                    Route::get('list', 'list')->middleware('agent.permission:view contact list');
                    Route::get('view/{id}', 'ContactListController@view')->name('view')->middleware('agent.permission:view list contact');
                    Route::middleware('has.subscription')->group(function () {
                        Route::post('save', 'save')->middleware('agent.permission:add contact list');
                        Route::post('update/{id}', 'save')->middleware('agent.permission:edit contact list');
                        Route::post('contact-add/{listId}', 'addContactToList')->middleware('agent.permission:add contact to list');
                    });
                    Route::post('contact-remove/{id}', 'removeFromList')->middleware('agent.permission:remove contact from list');
                    Route::post('delete/{id}', 'delete')->name('delete')->middleware('agent.permission:delete contact list');
                });

                // Contact Tags
                Route::controller('ContactTagController')->prefix('contact-tag')->group(function () {
                    Route::get('list', 'list')->middleware('agent.permission:view contact tag');
                    Route::post('save', 'save')->middleware('agent.permission:add contact tag');
                    Route::post('update/{id}', 'save')->middleware('agent.permission:edit contact tag');
                    Route::post('delete/{id}', 'deleteTag')->middleware('agent.permission:delete contact tag');
                });

                // Manage agent
                Route::controller('ManageAgentController')->prefix('agent')->group(function () {
                    Route::get('list', 'list')->middleware('agent.permission:view agent');
                    Route::post('save', 'save')->middleware(['agent.permission:add agent', 'has.subscription']);
                    Route::get('edit/{id}', 'edit')->middleware('agent.permission:edit agent');
                    Route::post('update/{id}', 'update')->middleware('agent.permission:edit agent');
                    Route::post('delete/{id}', 'delete')->middleware('agent.permission:delete agent');
                    Route::get('permissions/{id}', 'permissions')->middleware('agent.permission:view permission');
                    Route::post('permissions/save/{id}', 'updatePermissions')->middleware('agent.permission:assign agent');
                });
                
                // Subscription
                Route::controller('SubscriptionController')->prefix('subscription')->middleware('parent.user')->group(function () {
                    Route::get('index', 'index');
                    Route::get('purchase-history', 'purchaseHistory');
                    Route::get('invoice-download/{purchaseId}', 'downloadInvoice');
                    Route::post('purchase/{planId}', 'purchase');
                });
            });
        });
        Route::get('logout', 'Auth\LoginController@logout');

        Route::controller('PusherController')->group(function () {
            Route::post('pusher/auth', 'authenticationApp');
            Route::post('pusher/auth/{socketId}/{channelName}', 'authentication');
        });
    });
});
