@php
    $admin = auth('admin')->user();
@endphp

<x-admin.other.header_search :menus=$menus :permissions=$permissions />

<header class="dashboard__header">
    <div class="dashboard__header-left">
        <span class="breadcrumb-icon navigation-bar"><i class="fa-solid fa-bars"></i></span>
        <div class="header-search__input">
            <label for="desktop-search-input" class="header-search__icon open-search">
                <x-admin.svg.search />
            </label>
            <label for="desktop-search-input">
                <input type="search" id="desktop-search-input" placeholder="@lang('Search')...."
                    class="desktop-search header-search-filed open-search" autocomplete="false">
                <span class="search-instruction flex-align gap-2">
                    <span class="instruction__icon esc-text fw-bold">@lang('Ctrl')</span>
                    <span class="instruction__icon esc-text fw-bold">@lang('K')</span>
                </span>
            </label>

        </div>
    </div>
    <div class="dashboard-info flex-align gap-sm-2 gap-1">
        <div class="header-dropdown">
            <a class="header-dropdown__icon" href="{{ route('home') }}" target="_blank" data-bs-toggle="tooltip"
                title="@lang('Go to Website')">
                <i class="las la-globe"></i>
            </a>
        </div>
        <div class="dashboard-quick-link header-dropdown">
            <button class="header-dropdown__icon dropdown-toggle " data-bs-toggle="dropdown" aria-expanded="false">
                <span data-bs-toggle="tooltip" title="@lang('Quick Link')">
                    <i class="las la-link"></i>
                </span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <div class="quick-link-list">
                    <a href="{{ route('admin.deposit.pending') }}" class="quick-link-item">
                        <span class="quick-link-item__icon">
                            <i class="las la-money-check-alt"></i>
                        </span>
                        <span class="quick-link-item__name">
                            @lang('Pending Deposit')
                            <span class=" text--info">({{ $pendingDepositsCount }})</span>
                        </span>
                    </a>
                    <a href="{{ route('admin.deposit.pending') }}" class="quick-link-item">
                        <span class="quick-link-item__icon">
                            <i class="las la-hand-holding-usd"></i>
                        </span>
                        <span class="quick-link-item__name">
                            @lang('Pending Withdrawals')
                            <span class="text--info">({{ $pendingWithdrawCount }})</span>
                        </span>
                    </a>
                    <a href="{{ route('admin.ticket.pending') }}" class="quick-link-item">
                        <span class="quick-link-item__icon">
                            <i class="la la-ticket"></i>
                        </span>
                        <span class="quick-link-item__name">
                            @lang('Pending Ticket')
                            <span class=" text--info">({{ $pendingTicketCount }})</span>
                        </span>
                    </a>
                    <a href="{{ route('admin.setting.general') }}" class="quick-link-item">
                        <span class="quick-link-item__icon">
                            <i class="las la-cogs"></i>
                        </span>
                        <span class="quick-link-item__name">@lang('General Setting')</span>
                    </a>
                    <a href="{{ route('admin.setting.system.configuration') }}" class="quick-link-item">
                        <span class="quick-link-item__icon">
                            <i class="las la-tools"></i>
                        </span>
                        <span class="quick-link-item__name">@lang('System Configuration')</span>
                    </a>
                    <a href="{{ route('admin.setting.notification.email') }}" class="quick-link-item">
                        <span class="quick-link-item__icon">
                            <i class="las la-bell"></i>
                        </span>
                        <span class="quick-link-item__name">@lang('Notification Setting')</span>
                    </a>
                    <a href="{{ route('admin.users.all') }}" class="quick-link-item">
                        <span class="quick-link-item__icon">
                            <i class="las la-users"></i>
                        </span>
                        <span class="quick-link-item__name">@lang('All User')</span>
                    </a>
                    <a href="{{ route('admin.users.active') }}" class="quick-link-item">
                        <span class="quick-link-item__icon">
                            <i class="las la-user-check"></i>
                        </span>
                        <span class="quick-link-item__name">@lang('Active User')</span>
                    </a>
                    <a href="{{ route('admin.users.banned') }}" class="quick-link-item">
                        <span class="quick-link-item__icon">
                            <i class="las la-user-slash"></i>
                        </span>
                        <span class="quick-link-item__name">@lang('Banned User')</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="language-dropdown header-dropdown">
            <button class="header-dropdown__icon dropdown-toggle " data-bs-toggle="dropdown">
                <span data-bs-toggle="tooltip" title="@lang('Language')">
                    <i class="las la-language"></i>
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                @php
                    $appLocal = strtoupper(config('app.locale')) ?? 'en';
                @endphp
                @foreach ($languages as $language)
                    <li class="dropdown-menu__item  align-items-center gap-2 justify-content-between langSel"
                        data-code="{{ $language->code }}">
                        <div class=" d-flex flex-wrap align-items-center gap-2">
                            <span class="language-dropdown__icon">
                                <img src="{{ @$language->image_src }}">
                            </span>
                            {{ ucfirst($language->name) }}
                        </div>
                        @if ($appLocal == strtoupper($language->code))
                            <span class="text--success">
                                <i class="las la-check-double"></i>
                            </span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="header-dropdown">
            <button class=" dropdown-toggle header-dropdown__icon" type='button' data-bs-toggle="tooltip"
                title="@lang('Theme')" id="switch-theme">
                <span class=" dark-show">
                    <i class="las la-moon"></i>
                </span>
                <span class=" light-show">
                    <i class="las la-sun"></i>
                </span>
            </button>
        </div>
        <div class="notification header-dropdown">
            <button class="dropdown-toggle header-dropdown__icon" data-bs-toggle="dropdown" aria-expanded="false"
                data-bs-auto-close="outside">
                <span data-bs-toggle="tooltip" title="@lang('Notification')">
                    <i class="las la-bell  @if ($adminNotificationCount) icon-left-right @endif"></i>
                </span>
            </button>
            <div class="dropdown-menu dropdown-menu-end notification__area">
                <div class="notification__header p-3">
                    <h4 class="notification__header-text">@lang('Notifications')</h4>
                    @if ($adminNotificationCount)
                        <div class="notification__header-info">
                            <span class="notification__header-info-count badge--primary badge">
                                {{ $adminNotificationCount }}
                                @lang('New notifications')
                            </span>
                        </div>
                    @endif
                </div>
                <div class="top-notification__body">
                    <ul class="notification__items">
                        @forelse ($adminNotifications as $notification)
                            <li class="notification__list">
                                <a href="{{ route('admin.notification.read', $notification->id) }}"
                                    class="notification__link px-3">
                                    <div class="notification__list-thumb">
                                        @if ($notification->user)
                                            @if ($notification->user->image)
                                                <img class="fit-image"
                                                    src="{{ getImage(getFilePath('userProfile') . '/' . @$notification->user->image, getFileSize('userProfile')) }}">
                                            @else
                                                <span class="name-short-form">
                                                    {{ __(@$user->full_name_short_form ?? 'N/A') }}
                                                </span>
                                            @endif
                                        @else
                                            <img class="fit-image" src="{{ siteFavicon() }}">
                                        @endif
                                    </div>
                                    <div class="notification__list-content">
                                        <p class="notification__list-title">
                                            @if (@$notification->user)
                                                {{ @$notification->$user->full_name }}
                                            @else
                                                @lang('Anonymous')
                                            @endif
                                        </p>
                                        <p class="notification__list-desc">
                                            {{ __($notification->title) }}
                                        </p>
                                    </div>
                                    <div class="notification__list-status">
                                        <span class="notification__list-time">
                                            {{ diffForHumans($notification->created_at) }}
                                        </span>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="p-3">
                                <div class="p-5 text-center">
                                    <img src="{{ asset('assets/images/empty_box.png') }}" class="empty-message">
                                    <span class="d-block">@lang('No unread notifications were found')</span>
                                    <span class="d-block fs-13 text-muted">@lang('There is no available data to display here at the moment')</span>
                                </div>
                            <li>
                        @endforelse
                    </ul>
                </div>
                @if ($hasNotification)
                    <div class="notification__footer p-3">
                        <a href="{{ route('admin.notifications') }}" class="btn btn--primary btn-large  w-100">
                            @lang('View All Notification')
                        </a>
                    </div>
                @endif
            </div>
        </div>
        <div class="dashboard-header-user">
            <button class="header-dropdown__icon" data-bs-toggle="dropdown" aria-expanded="false">
                <span data-bs-toggle="tooltip" title="@lang('Profile')">
                    <i class="las la-user"></i>
                </span>
            </button>
            <div class="dropdown-menu dropdown-menu-end user__area">
                <div class="user__header">
                    <a href="{{ route('admin.profile') }}" class="user__info">
                        <div class="user__thumb">
                            <img src="{{ $admin->image_src }}">
                        </div>
                        <div class="user__details">
                            <h6 class="user__name">{{ @$admin->name }}</h6>
                            <p class="user__roll">@lang('Admin')</p>
                        </div>
                    </a>
                </div>
                <div class="user__body">
                    <nav class="user__link">
                        <a href="{{ route('admin.profile') }}" class="user__link-item">
                            <span class="user__link-item-icon">
                                <i class="las la-user-alt"></i>
                            </span>
                            <span class="user__link-item-text">@lang('My Profile')</span>
                        </a>
                        <a href="{{ route('admin.password') }}" class="user__link-item">
                            <span class="user__link-item-icon">
                                <i class="las la-lock-open"></i>
                            </span>
                            <span class="user__link-item-text">@lang('Change Passsword')</span>
                        </a>
                    </nav>
                </div>
                <div class="user__footer">
                    <a href="{{ route('admin.logout') }}" class="btn btn--danger ">
                        <span class="btn--icon"><i class="fas fa-sign-out text--danger"></i></span>
                        @lang('Logout')
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>