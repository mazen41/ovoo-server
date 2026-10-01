<?php

use App\Http\Middleware\RequireServerRequirements;
use Illuminate\Support\Facades\Route;

/*
 * Setup wizard. The whole group is closed by RedirectIfInstalled once installation finishes.
 */
Route::controller('InstallController')->prefix('install')->name('install.')->group(function () {
    Route::get('/', 'requirements')->name('requirements');

    /*
     * Everything past the first step is gated on the checks actually passing, so the wizard cannot
     * be walked through by URL on a server that fails them.
     */
    Route::middleware(RequireServerRequirements::class)->group(function () {
        Route::get('purchase', 'purchase')->name('purchase');
        Route::post('purchase', 'verifyPurchase')->name('purchase.verify');

        Route::get('database', 'database')->name('database');
        Route::post('database', 'saveDatabase')->name('database.save');

        Route::get('account', 'account')->name('account');
        Route::post('account', 'finish')->name('finish');
    });
});
