<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Auth')->group(function () {
    Route::middleware('admin.guest')->group(function () {
        Route::controller('LoginController')->group(function () {
            Route::get('/', 'showLoginForm')->name('login');
            Route::post('/', 'login')->name('login');
            Route::get('logout', 'logout')->middleware('admin')->withoutMiddleware('admin.guest')->name('logout');
        });
        // Admin Password Reset
        Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
            Route::get('reset', 'showLinkRequestForm')->name('reset');
            Route::post('reset', 'sendResetCodeEmail');
            Route::get('code-verify', 'codeVerify')->name('code.verify');
            Route::post('verify-code', 'verifyCode')->name('verify.code');
        });

        Route::controller('ResetPasswordController')->group(function () {
            Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
            Route::post('password/reset/change', 'reset')->name('password.change');
        });
    });
});


Route::middleware('admin')->group(function () {

    // Users Manager
    Route::controller('ManageUsersController')->name('users.')->prefix('users')->group(function () {
        Route::middleware('permission:view users,admin')->group(function () {
            Route::get('/', 'allUsers')->name('all');
            Route::get('deleted-account', 'deletedUsers')->name('deleted');
            Route::get('subscribed', 'subscribeUsers')->name('subscribe');
            Route::get('subscribed-expired', 'subscriptionExpiredUsers')->name('subscribe.expired');
            Route::get('free', 'freeUsers')->name('free');
            Route::get('active', 'activeUsers')->name('active');
            Route::get('banned', 'bannedUsers')->name('banned');
            Route::get('email-verified', 'emailVerifiedUsers')->name('email.verified');
            Route::get('email-unverified', 'emailUnverifiedUsers')->name('email.unverified');
            Route::get('mobile-unverified', 'mobileUnverifiedUsers')->name('mobile.unverified');
            Route::get('kyc-unverified', 'kycUnverifiedUsers')->name('kyc.unverified');
            Route::get('kyc-pending', 'kycPendingUsers')->name('kyc.pending');
            Route::get('mobile-verified', 'mobileVerifiedUsers')->name('mobile.verified');
            Route::get('with-balance', 'usersWithBalance')->name('with.balance');

            Route::post('extend-limit/{id}', 'extendLimit')->name('extend.limit')->middleware('permission:update user,admin');
            Route::post('assign-subscription/{id}', 'assignSubscription')->name('assign.subscription')->middleware('permission:update user,admin');

            Route::get('detail/{id}', 'detail')->name('detail');
        });
        Route::get('agent', 'agentUsers')->name('agent')->middleware('permission:view user agents,admin');

        Route::post('kyc-approve/{id}', 'kycApprove')->name('kyc.approve')->middleware('permission:update user,admin');
        Route::get('kyc-data/{id}', 'kycDetails')->name('kyc.details')->middleware('permission:update user,admin');
        Route::post('kyc-reject/{id}', 'kycReject')->name('kyc.reject')->middleware('permission:update user,admin');
        Route::post('update/{id}', 'update')->name('update')->middleware('permission:update user,admin');
        Route::post('add-sub-balance/{id}', 'addSubBalance')->name('add.sub.balance')->middleware('permission:update user balance,admin');
        Route::get('send-notification/{id}', 'showNotificationSingleForm')->name('notification.single')->middleware('permission:send user notification,admin');
        Route::post('send-notification/{id}', 'sendNotificationSingle')->name('notification.single')->middleware('permission:send user notification,admin');
        Route::get('login/{id}', 'login')->name('login')->middleware('permission:login as user,admin');
        Route::post('status/{id}', 'status')->name('status')->middleware('permission:ban user,admin');

        Route::get('send-notification', 'showNotificationAllForm')->name('notification.all')->middleware('permission:send user notification,admin');
        Route::post('send-notification', 'sendNotificationAll')->name('notification.all.send')->middleware('permission:send user notification,admin');
        Route::get('list', 'list')->name('list')->middleware('permission:view users,admin');
        Route::get('count-by-segment/{methodName}', 'countBySegment')->name('segment.count');
        Route::get('notification-log/{id}', 'notificationLog')->name('notification.log')->middleware('permission:view user notifications,admin');
    });

    // Subscriber
    Route::controller('SubscriberController')->prefix('subscriber')->name('subscriber.')->middleware('permission:manage subscribers,admin')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('send-email', 'sendEmailForm')->name('send.email')->middleware('permission:send user notification,admin');
        Route::post('send-email', 'sendEmail')->name('send.email')->middleware('permission:send user notification,admin');
        Route::post('remove/{id}', 'remove')->name('remove');
    });

    // Report
    Route::controller('ReportController')->prefix('report')->name('report.')->group(function () {
        Route::get('transaction/', 'transaction')->name('transaction')->middleware('permission:view all transactions,admin');
        Route::get('login/history', 'loginHistory')->name('login.history')->middleware('permission:view login history,admin');
        Route::get('login/ipHistory/{ip}', 'loginIpHistory')->name('login.ipHistory')->middleware('permission:view login history,admin');
        Route::get('notification/history', 'notificationHistory')->name('notification.history')->middleware('permission:view all notifications,admin');
        Route::get('email/detail/{id}', 'emailDetails')->name('email.details');
    });


    // Role & Permission
    Route::controller('RoleController')->name('role.')->prefix('role')->group(function () {
        Route::get('list', 'list')->name('list');
        Route::post('create', 'save')->name('create');
        Route::post('update/{id}', 'save')->name('update');
        Route::get('permission/{id}', 'permission')->name('permission');
        Route::post('permission/update/{id}', 'permissionUpdate')->name('permission.update');
    });

    // Coupon
    Route::controller('CouponController')->prefix('coupon')->name('coupon.')->group(function () {
        Route::get('list', 'list')->name('list')->middleware('permission:view coupon,admin');
        Route::post('store', 'store')->name('store')->middleware('permission:add coupon,admin');
        Route::post('update/{id}', 'store')->name('update')->middleware('permission:edit coupon,admin');
        Route::post('status/{id}', 'status')->name('status')->middleware('permission:edit coupon,admin');
    });

    // Admin Support
    Route::controller('SupportTicketController')->prefix('ticket')->name('ticket.')->middleware('permission:view tickets,admin')->group(function () {
        Route::get('/', 'tickets')->name('index');
        Route::get('pending', 'pendingTicket')->name('pending');
        Route::get('closed', 'closedTicket')->name('closed');
        Route::get('answered', 'answeredTicket')->name('answered');
        Route::get('view/{id}', 'ticketReply')->name('view');
        Route::post('reply/{id}', 'replyTicket')->name('reply')->middleware('permission:answer tickets,admin');
        Route::post('close/{id}', 'closeTicket')->name('close')->middleware('permission:close tickets,admin');
        Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
        Route::post('delete/{id}', 'ticketDelete')->name('delete')->middleware('permission:close tickets,admin');
    });


    Route::controller('AdminController')->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard')->middleware('permission:view dashboard,admin');
        Route::get('chart/deposit-withdraw', 'depositAndWithdrawReport')->name('chart.deposit.withdraw');
        Route::get('chart/transaction', 'transactionReport')->name('chart.transaction');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'passwordUpdate')->name('password.update');

        //Notification
        Route::get('notifications', 'notifications')->name('notifications');
        Route::get('notification/read/{id}', 'notificationRead')->name('notification.read');
        Route::get('notifications/read-all', 'readAllNotification')->name('notifications.read.all');
        Route::post('notifications/delete-all', 'deleteAllNotification')->name('notifications.delete.all');
        Route::post('notifications/delete-single/{id}', 'deleteSingleNotification')->name('notifications.delete.single');

        // Subscriptions
        Route::get('/subscriptions', 'subscriptionLog')->name('user.subscriptions')->middleware('permission:view subscription history,admin');

        //Report Bugs
        Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');

        // Assign role
        Route::get('list', 'list')->name('list')->middleware('permission:view admin,admin');
        Route::post('store', 'save')->name('store')->middleware('permission:add admin,admin');
        Route::post('update/{id}', 'save')->name('update')->middleware('permission:edit admin,admin');
        Route::post('status-change/{id}', 'status')->name('status.change')->middleware('permission:edit admin,admin');
    });

    // extensions
    Route::controller('ExtensionController')->prefix('extensions')->name('extensions.')->middleware('permission:manage extensions,admin')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('status/{id}', 'status')->name('status');
    });

    // Language Manager
    Route::controller('LanguageController')->prefix('language')->name('language.')->middleware('permission:manage languages,admin')->group(function () {
        Route::get('/', 'langManage')->name('manage');
        Route::post('/', 'langStore')->name('manage.store');
        Route::post('delete/{id}', 'langDelete')->name('manage.delete');
        Route::post('update/{id}', 'langUpdate')->name('manage.update');
        Route::get('edit/{id}', 'langEdit')->name('key');
        Route::post('import', 'langImport')->name('import.lang');
        Route::post('store/key/{id}', 'storeLanguageJson')->name('store.key');
        Route::post('delete/key/{id}/{key}', 'deleteLanguageJson')->name('delete.key');
        Route::post('update/key/{id}', 'updateLanguageJson')->name('update.key');
        Route::get('get-keys', 'getKeys')->name('get.key');
    });

    //Notification Setting
    Route::name('setting.notification.')->controller('NotificationController')->middleware('permission:notification settings,admin')->prefix('notification')->group(function () {
        //Template Setting
        Route::get('global/email', 'globalEmail')->name('global.email');
        Route::post('global/email/update', 'globalEmailUpdate')->name('global.email.update');

        Route::get('global/sms', 'globalSms')->name('global.sms');
        Route::post('global/sms/update', 'globalSmsUpdate')->name('global.sms.update');

        Route::get('global/push', 'globalPush')->name('global.push');
        Route::post('global/push/update', 'globalPushUpdate')->name('global.push.update');

        Route::get('templates', 'templates')->name('templates');
        Route::get('template/edit/{type}/{id}', 'templateEdit')->name('template.edit');
        Route::post('template/update/{type}/{id}', 'templateUpdate')->name('template.update');

        //Email Setting
        Route::get('email/setting', 'emailSetting')->name('email');
        Route::post('email/setting', 'emailSettingUpdate');
        Route::post('email/test', 'emailTest')->name('email.test');

        //SMS Setting
        Route::get('sms/setting', 'smsSetting')->name('sms');
        Route::post('sms/setting', 'smsSettingUpdate');
        Route::post('sms/test', 'smsTest')->name('sms.test');

        Route::get('notification/push/setting', 'pushSetting')->name('push');
        Route::post('notification/push/setting', 'pushSettingUpdate');
        Route::post('notification/push/setting/upload', 'pushSettingUpload')->name('push.upload');
        Route::get('notification/push/setting/download', 'pushSettingDownload')->name('push.download');
    });

    //KYC setting
    Route::controller('KycController')->middleware('permission:kyc settings,admin')->group(function () {
        Route::get('kyc-setting', 'setting')->name('kyc.setting');
        Route::post('kyc-setting', 'settingUpdate')->name('kyc.setting.update');
    });

    // DEPOSIT SYSTEM
    Route::controller('DepositController')->prefix('deposit')->name('deposit.')->group(function () {
        Route::middleware('permission:view deposit,admin')->group(function () {
            Route::get('all', 'deposit')->name('list');
            Route::get('pending', 'pending')->name('pending');
            Route::get('rejected', 'rejected')->name('rejected');
            Route::get('approved', 'approved')->name('approved');
            Route::get('successful', 'successful')->name('successful');
            Route::get('initiated', 'initiated')->name('initiated');
            Route::get('details/{id}', 'details')->name('details');
        });
        Route::post('reject', 'reject')->name('reject')->middleware('permission:reject deposit,admin');
        Route::post('approve/{id}', 'approve')->name('approve')->middleware('permission:approve deposit,admin');
    });


    // WITHDRAW SYSTEM
    Route::name('withdraw.')->prefix('withdraw')->group(function () {

        Route::controller('WithdrawalController')->name('data.')->group(function () {
            Route::middleware('permission:view withdraw,admin')->group(function () {
                Route::get('pending/{user_id?}', 'pending')->name('pending');
                Route::get('approved/{user_id?}', 'approved')->name('approved');
                Route::get('rejected/{user_id?}', 'rejected')->name('rejected');
                Route::get('all/{user_id?}', 'all')->name('all');
                Route::get('details/{id}', 'details')->name('details');
            });
            Route::post('approve', 'approve')->name('approve')->middleware('permission:approve withdraw,admin');
            Route::post('reject', 'reject')->name('reject')->middleware('permission:reject withdraw,admin');
        });


        // Withdraw Method
        Route::controller('WithdrawMethodController')->prefix('method')->name('method.')->middleware('permission:manage withdraw methods,admin')->group(function () {
            Route::get('/', 'methods')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('create', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('edit/{id}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
        });
    });

    // SEO
    Route::get('seo', 'FrontendController@seoEdit')->name('seo')->middleware('permission:seo settings,admin');

    // Frontend
    Route::name('frontend.')->prefix('frontend')->group(function () {

        Route::controller('FrontendController')->middleware('permission:manage sections,admin')->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('templates', 'templates')->name('templates');
            Route::post('templates', 'templatesActive')->name('templates.active');
            Route::get('frontend-sections/{key?}', 'frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'frontendElement')->name('sections.element');
            Route::get('frontend-slug-check/{key}/{id?}', 'frontendElementSlugCheck')->name('sections.element.slug.check');
            Route::get('frontend-element-seo/{key}/{id}', 'frontendSeo')->name('sections.element.seo');
            Route::post('frontend-element-seo/{key}/{id}', 'frontendSeoUpdate');
            Route::post('remove/{id}', 'remove')->name('remove');
        });

        // Page Builder
        Route::controller('PageBuilderController')->middleware('permission:manage pages,admin')->group(function () {
            Route::get('manage-pages', 'managePages')->name('manage.pages');
            Route::get('manage-pages/check-slug/{id?}', 'checkSlug')->name('manage.pages.check.slug');
            Route::post('manage-pages', 'managePagesSave')->name('manage.pages.save');
            Route::post('manage-pages/update', 'managePagesUpdate')->name('manage.pages.update');
            Route::post('manage-pages/delete/{id}', 'managePagesDelete')->name('manage.pages.delete');
            Route::get('manage-section/{id}', 'manageSection')->name('manage.section');
            Route::post('manage-section/{id}', 'manageSectionUpdate')->name('manage.section.update');

            Route::get('manage-seo/{id}', 'manageSeo')->name('manage.pages.seo');
            Route::post('manage-seo/{id}', 'manageSeoStore');
        });
    });

    //System Information
    Route::controller('SystemController')->name('system.')->middleware('permission:view application info,admin')->prefix('system')->group(function () {
        Route::get('info', 'systemInfo')->name('info');
        Route::get('optimize-clear', 'optimizeClear')->name('optimize.clear');
    });

    Route::controller('GeneralSettingController')->group(function () {


        // General Setting
        Route::get('general-setting', 'general')->name('setting.general')->middleware('permission:update general settings,admin');
        Route::post('general-setting', 'generalUpdate')->middleware('permission:update general settings,admin');

        // Pusher Configuration
        Route::get('pusher-configuration', 'pusherConfiguration')->name('setting.pusher.configuration')->middleware('permission:pusher configuration,admin');
        Route::post('pusher-configuration', 'pusherConfigurationUpdate')->name('setting.pusher.configuration')->middleware('permission:pusher configuration,admin');


        Route::get('setting/social/credentials', 'socialiteCredentials')->name('setting.socialite.credentials')->middleware('permission:social login settings,admin');
        Route::post('setting/social/credentials/update/{key}', 'updateSocialiteCredential')->name('setting.socialite.credentials.update')->middleware('permission:social login settings,admin');
        Route::post('setting/social/credentials/status/{key}', 'updateSocialiteCredentialStatus')->name('setting.socialite.credentials.status.update')->middleware('permission:social login settings,admin');

        //configuration
        Route::get('setting/system-configuration', 'systemConfiguration')->name('setting.system.configuration')->middleware('permission:system configuration,admin');
        Route::get('setting/system-configuration/{key}', 'systemConfigurationUpdate')->name("setting.system.configuration.update")->middleware('permission:system configuration,admin');

        // Logo-Icon
        Route::get('setting/brand', 'logoIcon')->name('setting.brand')->middleware('permission:update brand settings,admin');
        Route::post('setting/brand', 'logoIconUpdate')->name('setting.brand')->middleware('permission:update brand settings,admin');

        //Custom CSS
        Route::get('custom-css', 'customCss')->name('setting.custom.css')->middleware('permission:custom css,admin');
        Route::post('custom-css', 'customCssSubmit')->middleware('permission:custom css,admin');

        Route::get('sitemap', 'sitemap')->name('setting.sitemap')->middleware('permission:sitemap xml,admin');
        Route::post('sitemap', 'sitemapSubmit')->middleware('permission:sitemap xml,admin');

        Route::get('robot', 'robot')->name('setting.robot')->middleware('permission:robots txt,admin');
        Route::post('robot', 'robotSubmit')->middleware('permission:robots txt,admin');

        //Cookie
        Route::get('cookie', 'cookie')->name('setting.cookie')->middleware('permission:cookie settings,admin');
        Route::post('cookie', 'cookieSubmit')->middleware('permission:cookie settings,admin');

        //maintenance_mode
        Route::get('maintenance-mode', 'maintenanceMode')->name('maintenance.mode')->middleware('permission:update maintenance mode,admin');
        Route::post('maintenance-mode', 'maintenanceModeSubmit')->middleware('permission:update maintenance mode,admin');

        //In app purchase
        Route::get('in-app-purchase', 'inAppPurchase')->name('setting.app.purchase')->middleware('permission:in app payment settings,admin');
        Route::post('in-app-purchase', 'inAppPurchaseConfigure')->middleware('permission:in app payment settings,admin');
        Route::get('in-app-purchase/file/download', 'inAppPurchaseFileDownload')->name('setting.app.purchase.file.download')->middleware('permission:in app payment settings,admin');


        Route::middleware("permission:pwa settings,admin")->group(function () {
            Route::get('setting/pwa', 'pwaIcon')->name('setting.pwa');
            Route::post('setting/pwa', 'pwaIconUpdate')->name('setting.pwa');
        });
    });
    // Deposit Gateway
    Route::name('gateway.')->prefix('gateway')->group(function () {

        // Manual Methods
        Route::controller('ManualGatewayController')->prefix('manual')->name('manual.')->group(function () {
            Route::get('new', 'create')->name('create');
            Route::post('new', 'store')->name('store');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
        });

        // Automatic Gateway
        Route::controller('AutomaticGatewayController')->name('automatic.')->middleware('permission:manage gateways,admin')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{code}', 'update')->name('update');
            Route::post('remove/{id}', 'remove')->name('remove');
            Route::post('status/{id}', 'status')->name('status');
        });
    });


    //cron
    Route::controller('CronConfigurationController')->name('cron.')->prefix('cron')->middleware('permission:manage cron job,admin')->group(function () {
        Route::get('index', 'cronJobs')->name('index');
        Route::post('store', 'cronJobStore')->name('store');
        Route::post('update/{id}', 'cronJobUpdate')->name('update');
        Route::post('delete/{id}', 'cronJobDelete')->name('delete');
        Route::get('schedule', 'schedule')->name('schedule');
        Route::post('schedule/store/{id?}', 'scheduleStore')->name('schedule.store');
        Route::post('schedule/status/{id}', 'scheduleStatus')->name('schedule.status');
        Route::get('schedule/pause/{id}', 'schedulePause')->name('schedule.pause');
        Route::get('schedule/logs/{id}', 'scheduleLogs')->name('schedule.logs');
        Route::post('schedule/log/resolved/{id}', 'scheduleLogResolved')->name('schedule.log.resolved');
        Route::post('schedule/log/flush/{id}', 'logFlush')->name('log.flush');
    });

    // Pricing Plan
    Route::controller('PricingPlanController')->prefix('pricing-plan')->name('pricing.plan.')->group(function () {
        Route::get('/index', 'index')->name('index')->middleware('permission:view pricing plans,admin');
        Route::post('/status/{id}', 'status')->name('status')->middleware('permission:edit pricing plan,admin');
        Route::post('/store/{id?}', 'store')->name('store')->middleware('permission:add pricing plan,admin');
        Route::post('/update/{id?}', 'store')->name('update')->middleware('permission:edit pricing plan,admin');
    });

    // User Data Management
    Route::controller('UserDataController')->prefix('user/data')->name('user.data.')->group(function () {
        Route::get('contact', 'contact')->name('contact')->middleware('permission:view contact,admin');
        Route::get('contact/list', 'contactList')->name('contact.list')->middleware('permission:view contact list,admin');
        Route::get('contact/tag', 'contactTag')->name('contact.tag')->middleware('permission:view contact tag,admin');
        Route::get('campaign', 'campaign')->name('campaign')->middleware('permission:view campaign,admin');
        Route::get('short/link', 'shortLink')->name('short.link')->middleware('permission:view short link,admin');
    });

    Route::controller("ExportController")->group(function () {
        Route::get('export/{model}/{type}', 'export')->name('export');
    });

    Route::controller('AiAssistantController')->middleware('permission:ai assistant settings,admin')->name('ai-assistant.')->prefix('ai-assistant')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('status/{id}', 'status')->name('status');
        Route::post('configure/{id}', 'configure')->name('configure');
    });
});
