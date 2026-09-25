<?php

use App\Http\Middleware\ExternalAPI;
use Illuminate\Support\Facades\Route;



Route::middleware(ExternalAPI::class)->group(function () {

    // Contact
    Route::controller('ContactController')->prefix('contact')->group(function () {
        Route::get('list', 'list');
        Route::post('store', 'save');
        Route::post('update/{id}', 'save');
        Route::delete('delete/{id}', 'delete');
    });

    // Inbox
    Route::controller('InboxController')->prefix('inbox')->group(function () {
        Route::get('conversation-list', 'list');
        Route::get('template-list', 'templateList');
        Route::post('change-conversation-status/{id}', 'changeConversationStatus');
        Route::get('conversation-details/{id}', 'conversationDetails');
        Route::post('send-message', 'sendMessage');
        Route::post('send-template-message', 'sendTemplateMessage');
    });
});
