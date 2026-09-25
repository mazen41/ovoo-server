<?php

namespace App\Providers;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class GlobalVariablesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $viewShare['emptyMessage'] = 'No data found';

        view()->composer(['admin.partials.topnav', "Template::partials.header", "Template::partials.auth_header"], function ($view) {
            $view->with([
                'languages' => Language::where('code', '!=', strtolower(getCurrentLang()))->get()
            ]);
        });

        view()->composer(['admin.partials.sidenav', 'admin.partials.topnav'], function ($view) {
            $view->with([
                'menus'                => json_decode(file_get_contents(resource_path('views/admin/partials/menu.json'))),
                'pendingTicketCount'   => SupportTicket::whereIn('status', [Status::TICKET_OPEN, Status::TICKET_REPLY])->count(),
                'pendingDepositsCount' => Deposit::pending()->count(),
                'pendingWithdrawCount' => Withdrawal::pending()->count(),
                'permissions'          => Auth::guard('admin')->user()->getAllPermissions()->pluck('name')->toArray() ?? [],
            ]);
        });

        view()->composer('admin.partials.topnav', function ($view) {
            $view->with([
                'adminNotifications'     => AdminNotification::where('is_read', Status::NO)->with('user')->orderBy('id', 'desc')->take(10)->get(),
                'adminNotificationCount' => AdminNotification::where('is_read', Status::NO)->count(),
                'hasNotification'        => AdminNotification::exists(),
            ]);
        });

        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'bannedUsersCount'           => User::banned()->count(),
                'emailUnverifiedUsersCount'  => User::emailUnverified()->count(),
                'mobileUnverifiedUsersCount' => User::mobileUnverified()->count(),
                'kycUnverifiedUsersCount'    => User::kycUnverified()->count(),
                'kycPendingUsersCount'       => User::kycPending()->count(),
            ]);
        });

        view()->composer('partials.seo', function ($view) {
            $seo = Frontend::where('data_keys', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_values : $seo,
            ]);
        });

        view()->composer('Template::partials.sidebar', function ($view) {
            $view->with([
                'user' => auth()->user(),
            ]);
        });

        view()->composer('components.permission_check', function ($view) {
            $view->with([
                'user' => auth()->user()
            ]);
        });

        view()->composer(['components.admin.permission_check', 'admin.partials.topnav',], function ($view) {
            $view->with([
                'admin' => Auth::guard('admin')->user()
            ]);
        });

        view()->share($viewShare);
    }
}
