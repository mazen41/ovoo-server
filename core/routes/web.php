<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('cron', 'CronController@cron')->name('cron');
Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');

Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{id}', 'replyTicket')->name('reply');
    Route::post('close/{id}', 'closeTicket')->name('close');

    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});

Route::controller('WebhookController')->group(function () {
    Route::get('/webhook', 'webhookConnect')->name('webhook');
    Route::post('/webhook', 'webhookResponse');
});

Route::controller('PusherController')->group(function () {
    Route::post('pusher/auth', 'authenticationApp');
    Route::post('pusher/auth/{socketId}/{channelName}', 'authentication');
});


Route::controller('SiteController')->group(function () {

    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');

    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

    Route::get('blogs', 'blogs')->name('blogs');
    Route::get('blog/{slug}', 'blogDetails')->name('blog.details');

    Route::get('/pricing', 'pricing')->name('pricing');

    Route::get('/features', 'features')->name('features');

    Route::get('wl/{code}', 'redirectShortLink')->name('short.link.redirect');

    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');
    Route::get('/pwa-manifest', 'pwaManifest')->name('pwa.manifest');
    Route::get('external-api-documentation', 'externalApiDocumentation')->name('external.api.documentation');

    Route::get('placeholder-image/{size}', 'placeholderImage')->withoutMiddleware('maintenance')->name('placeholder.image');
    Route::get('maintenance-mode', 'maintenance')->withoutMiddleware('maintenance')->name('maintenance');

    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');

    Route::post('subscribe', 'subscribe')->name('subscribe');
});
