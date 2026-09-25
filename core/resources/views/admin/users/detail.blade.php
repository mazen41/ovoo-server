@extends('admin.layouts.app')
@section('panel')
    <div class="row responsive-row">
        <div class="@if ($user->parent_id == Status::YES) col-xxl-12 @else col-xxl-6 @endif">
            <div class="card h-100 ">
                <div class="card-body">
                    <div class="user-detail">
                        <div class="user-detail__user">
                            <div class="user-detail__thumb">
                                <img class="fit-image" src="{{ $user->image_src }}" alt="user">
                            </div>
                            <div class="user-detail__user-info">
                                <h5 class="user-detail__name mb-1">{{ __($user->fullname) }}</h5>
                                <p class="user-detail__username">{{ '@' . $user->username }}</p>
                            </div>
                            <x-admin.permission_check permission="login as user">
                                <div class="login-user">
                                    <a target="_blank" href="{{ route('admin.users.login', $user->id) }}"
                                        class="btn btn--primary">
                                        <i class="fas fa-sign-in-alt me-1"></i>
                                        @if ($user->parent_id == Status::NO)
                                            <span>@lang('Login as User')</span>
                                        @else
                                            <span>@lang('Login as Agent')</span>
                                        @endif
                                    </a>
                                </div>
                            </x-admin.permission_check>
                        </div>
                        <div class="row gy-4 align-items-center">
                            <div class="col-md-6">
                                <ul class="user-detail__contact">
                                    <li class="item">
                                        <span>@lang('Email'): </span>
                                        <span>{{ $user->email }}</span>
                                    </li>
                                    <li class="item">
                                        <span>@lang('Mobile number'): </span>
                                        <span>{{ $user->mobileNumber }}</span>
                                    </li>
                                    <li class="item">
                                        <span>@lang('Country'): </span>
                                        <span>{{ __($user->country_name) }}</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-6">
                                <ul class="user-detail__verification">
                                    <li class="item">
                                        <span>@lang('Email Verification')</span>
                                        <span>
                                            @if ($user->ev)
                                                <i class="fas fa-check-circle text--success"></i>
                                            @else
                                                <i class="fas fa-times-circle text--danger"></i>
                                            @endif
                                        </span>
                                    </li>
                                    <li class="item">
                                        <span>@lang('Mobile Verification')</span>
                                        <span>
                                            @if ($user->sv)
                                                <i class="fas fa-check-circle text--success"></i>
                                            @else
                                                <i class="fas fa-times-circle text--danger"></i>
                                            @endif
                                        </span>
                                    </li>
                                    <li class="item">
                                        <span>@lang('KYC Verification')</span>
                                        @if ($user->kv)
                                            <i class="fas fa-check-circle text--success"></i>
                                        @else
                                            <i class="fas fa-times-circle text--danger"></i>
                                        @endif
                                    </li>
                                    <li class="item">
                                        <span>@lang('2FA Verification')</span>
                                        <span>
                                            @if ($user->ts)
                                                <i class="fas fa-check-circle text--success"></i>
                                            @else
                                                <i class="fas fa-times-circle text--danger"></i>
                                            @endif
                                        </span>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($user->parent_id == Status::NO)
            <div class="col-xxl-6">
                <div class="card shadow-none ">
                    <div class="card-header border-0">
                        <h5 class="card-title">@lang('Financial Overview')</h5>
                    </div>
                    <div class="card-body">
                        <div class="widget-card-wrapper custom-widget-wrapper">
                            <div class="row g-0">
                                <div class="col-sm-6">
                                    <div class="widget-card widget--success">
                                        <a href="{{ route('admin.report.transaction') }}?user_id={{ $user->id }}"
                                            class="widget-card-link"></a>
                                        <div class="widget-card-left">
                                            <div class="widget-icon">
                                                <i class="fas fa-credit-card"></i>
                                            </div>
                                            <div class="widget-card-content">
                                                <p class="widget-title">@lang('Balance')</p>
                                                <h6 class="widget-amount">
                                                    {{ gs('cur_sym') }}{{ showAmount($user->balance, currencyFormat: false) }}
                                                    <span class="currency">
                                                        {{ __(gs('cur_text')) }}
                                                    </span>
                                                </h6>
                                            </div>
                                        </div>
                                        <span class="widget-card-arrow">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="widget-card widget--success">
                                        <a href="{{ route('admin.deposit.list') }}?user_id={{ $user->id }}"
                                            class="widget-card-link"></a>
                                        <div class="widget-card-left">
                                            <div class="widget-icon">
                                                <i class="fas fa-arrow-alt-circle-down"></i>
                                            </div>
                                            <div class="widget-card-content">
                                                <p class="widget-title">@lang('Total Deposits')</p>
                                                <h6 class="widget-amount">
                                                    {{ gs('cur_sym') }}{{ showAmount($widget['total_deposit'], currencyFormat: false) }}
                                                    <span class="currency">
                                                        {{ __(gs('cur_text')) }}
                                                    </span>
                                                </h6>
                                            </div>
                                        </div>
                                        <span class="widget-card-arrow">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="widget-card widget--warning">
                                        <a href="{{ route('admin.withdraw.data.all') }}?user_id={{ $user->id }}"
                                            class="widget-card-link"></a>
                                        <div class="widget-card-left">
                                            <div class="widget-icon">
                                                <i class="fas fas fa-arrow-alt-circle-up"></i>
                                            </div>
                                            <div class="widget-content">
                                                <p class="widget-title">@lang('Total Withdrawals')</p>
                                                <h6 class="widget-amount">
                                                    {{ gs('cur_sym') }}{{ showAmount($widget['total_withdraw'], currencyFormat: false) }}
                                                    <span class="currency">
                                                        {{ __(gs('cur_text')) }}
                                                    </span>
                                                </h6>
                                            </div>
                                        </div>
                                        <span class="widget-card-arrow">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="widget-card widget--primary">
                                        <a href="{{ route('admin.report.transaction') }}?user_id={{ $user->id }}"
                                            class="widget-card-link"></a>
                                        <div class="widget-card-left">
                                            <div class="widget-icon">
                                                <i class="fas fa-sync"></i>
                                            </div>
                                            <div class="widget-card-content">
                                                <p class="widget-title">@lang('Total Transactions')</p>
                                                <h6 class="widget-amount">
                                                    {{ gs('cur_sym') }}{{ showAmount($widget['total_transaction'], currencyFormat: false) }}
                                                    <span class="currency">
                                                        {{ __(gs('cur_text')) }}
                                                    </span>
                                                </h6>
                                            </div>
                                        </div>
                                        <span class="widget-card-arrow">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="row responsive-row">
        <div class="col-xxl-8">
            <form action="{{ route('admin.users.update', [$user->id]) }}" method="POST" enctype="multipart/form-data"
                class="user-form">
                @csrf
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-3">
                        <h5 class="card-title mb-0">@lang('Full Information')</h5>
                        <x-admin.permission_check permission="update user">
                            <div class=" d-none d-md-block">
                                <button type="submit" class="btn btn--primary fw-500 disabled" disabled>
                                    <i class="fa-regular fa-paper-plane me-1"></i><span>@lang('Update')</span>
                                </button>
                            </div>
                        </x-admin.permission_check>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label>@lang('First Name')</label>
                                        <input class="form-control" type="text" name="firstname" required
                                            value="{{ $user->firstname }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">@lang('Last Name')</label>
                                    <input class="form-control" type="text" name="lastname" required
                                        value="{{ $user->lastname }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label>@lang('Email')</label>
                                    <input class="form-control" type="email" name="email"
                                        value="{{ $user->email }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label>@lang('Mobile Number')</label>
                                    <div class="input-group input--group ">
                                        <span class="input-group-text mobile-code">+{{ $user->dial_code }}</span>
                                        <input type="number" name="mobile" value="{{ $user->mobile }}" id="mobile"
                                            class="form-control checkUser" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>@lang('Address')</label>
                                <input class="form-control" type="text" name="address"
                                    value="{{ @$user->address }}">
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label>@lang('City')</label>
                                    <input class="form-control" type="text" name="city"
                                        value="{{ @$user->city }}">
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label>@lang('State')</label>
                                    <input class="form-control" type="text" name="state"
                                        value="{{ @$user->state }}">
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label>@lang('Zip/Postal')</label>
                                    <input class="form-control" type="text" name="zip"
                                        value="{{ @$user->zip }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label>@lang('Country') <span class="text--danger">*</span></label>
                                    <select name="country" class="form-control select2">
                                        @foreach ($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}"
                                                value="{{ $key }}" @selected($user->country_code == $key)>
                                                {{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="verification-switch">
                            <div class="verification-switch__item d-flex justify-content-between align-items-center gap-2">
                                <label class="form-check-label fw-500" for="email_verification">@lang('Email Verification')</label>
                                <div class="form-check form-switch form-switch-success form--switch pl-0">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        id="email_verification" name="ev" @checked($user->ev)>
                                </div>
                            </div>
                            <div class="verification-switch__item d-flex justify-content-between align-items-center gap-2">
                                <label class="form-check-label fw-500" for="mobile_berification">
                                    @lang('Mobile Verification')
                                </label>
                                <div class="form-check form-switch form-switch-success form--switch pl-0">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        id="mobile_berification" name="sv" @checked($user->sv)>
                                </div>
                            </div>

                            <div class="verification-switch__item d-flex justify-content-between align-items-center gap-2">
                                <label class="form-check-label fw-500" for="kyc_verification">@lang('KYC Verification')</label>
                                <div class="form-check form-switch form-switch-success form--switch pl-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="kyc_verification"
                                        name="kv" @checked($user->kv)>
                                </div>
                            </div>
                            <div class="verification-switch__item d-flex justify-content-between align-items-center gap-2">
                                <label class="form-check-label fw-500" for="2fa_verification">@lang('2FA Verification')</label>
                                <div class="form-check form-switch form-switch-success form--switch pl-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="2fa_verification"
                                        name="ts" @checked($user->ts)>
                                </div>
                            </div>
                        </div>
                        <div class="d-block d-md-none mt-3">
                            <x-admin.ui.btn.submit disabled="disabled" class="disabled" text="Update" />
                        </div>
                    </div>
                </div>

            </form>
        </div>
        <div class="col-xxl-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center gap-3">
                    <h5 class="card-title mb-0">@lang('Login History')</h5>
                    <x-admin.permission_check permission="view login history">
                        <a href="{{ route('admin.report.login.history') }}?user_id={{ $user->id }}"
                            class="btn btn--primary fw-500 @if (!$loginLogs->count()) disabled @endif">
                            <span>@lang('View All')</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </x-admin.permission_check>
                </div>
                <div class="card-body">
                    <div class="login-history h-100">
                        @forelse ($loginLogs as $loginLog)
                            <div class="login-history__item d-flex justify-content-between align-items-center">
                                <div class="login-history__item-content d-flex align-items-center gap-2">
                                    <div class="login-history__item__icon">
                                        @if (in_array(strtolower($loginLog->os), os()))
                                            <i class="fab fa-{{ strtolower($loginLog->os) }}"></i>
                                        @else
                                            <i class="fa fa-desktop"></i>
                                        @endif
                                    </div>
                                    <div class="login-history__info">
                                        <p class="login-history__item-title">{{ __($loginLog->os) }}</p>
                                        <p class="login-history__item-desc text--secondary">
                                            {{ __($loginLog->browser) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="login-history__item-time">
                                    <p>{{ __($loginLog->user_ip) }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 d-flex justify-content-center align-items-center flex-column h-100">
                                <img src="{{ asset('assets/images/empty_box.png') }}" class="empty-message">
                                <span class="d-block fs-14 text-muted">{{ __($emptyMessage) }}</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-admin.ui.modal id="userStatusModal">
        <x-admin.ui.modal.header>
            <div>
                <h4 class="modal-title">
                    @if ($user->status == Status::USER_ACTIVE)
                        @lang('Ban User')
                    @else
                        @lang('Unban User')
                    @endif
                </h4>
                @if ($user->status == Status::USER_ACTIVE)
                    <small>@lang('If this user is banned, they will no longer have access to their dashboard.')</small>
                @else
                    <small>
                        <span class=" text--info">@lang('Ban reason was'):</span>
                        <span>{{ __($user->ban_reason) }}</span>
                    </small>
                @endif
            </div>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form action="{{ route('admin.users.status', $user->id) }}" method="POST">
                @csrf
                @if ($user->status == Status::USER_ACTIVE)
                    <div class="form-group">
                        <label>@lang('Reason')</label>
                        <textarea class="form-control" name="reason" rows="4" required></textarea>
                    </div>
                @else
                    <h4 class="mt-3 text-center text--warning">@lang('Are you sure to unban this user?')</h4>
                @endif
                <div class="form-group">
                    @if ($user->status == Status::USER_ACTIVE)
                        <x-admin.ui.btn.modal />
                    @else
                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                            <button type="button" class="btn btn--secondary btn-large" data-bs-dismiss="modal">
                                <i class="las la-times"></i> @lang('No')
                            </button>
                            <button type="submit" class="btn btn--primary btn-large">
                                <i class=" las la-check-circle"></i> @lang('Yes')
                            </button>
                        </div>
                    @endif
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>

    <x-admin.ui.modal id="addSubModal">
        <x-admin.ui.modal.header>
            <div>
                <h4 class="modal-title">@lang('Add Balance')</h4>
                <small class="modal-subtitle">@lang('Add funds to user accounts by entering the desired amount below')</small>
            </div>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form method="POST" action="{{ route('admin.users.add.sub.balance', $user->id) }}">
                @csrf
                <input type="hidden" name="act">
                <div class="form-group">
                    <label class="form-label">@lang('Amount')</label>
                    <div class="input-group input--group">
                        <input type="number" step="any" min="0" name="amount" class="form-control"
                            placeholder="@lang('Enter amount')" required>
                        <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">@lang('Remark')</label>
                    <textarea class="form-control" placeholder="@lang('Enter remark')" name="remark" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>

    <x-admin.ui.modal id="subscriptionAssignModal">
        <x-admin.ui.modal.header>
            <div>
                <h4 class="modal-title">@lang('Assign Subscription Plan')</h4>
                <small class="modal-subtitle">@lang('This subscription will be assigned without charging the user.')</small>
            </div>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>

            <div class="alert alert--warning mb-3">
                <p>
                    @lang('This is a manual subscription plan or plan limit extension feature. All financial records related to plan assignment or plan limit extensions will be not recorded in the system. However, the entire process is managed manually by the administrator.')
                </p>
            </div>

            @if ($user->plan)
                <div class="alert alert--info mb-2">
                    <p>@lang('The user has an active subscription plan. You can assign a new subscription plan without changing or extend the current one') <button class="text--primary planExtendBtn ms-1"> <i class="las la-edit"></i>
                            @lang('Edit')</button></p>
                </div>
            @endif
            <form method="POST" action="{{ route('admin.users.assign.subscription', $user->id) }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">@lang('Pricing Plan')</label>
                    <select name="plan_id" class="form-control select2" required>
                        <option value="">@lang('Select Pricing Plan')</option>
                        @foreach ($pricingPlans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">@lang('Select Recurring Type')</label>
                    <select class="form-control select2" data-minimum-results-for-search="-1" name="plan_recurring"
                        required>
                        <option value="{{ Status::MONTHLY }}" selected>@lang('Monthly')</option>
                        <option value="{{ Status::YEARLY }}">@lang('Yearly')</option>
                    </select>
                </div>
                <div class="form-group">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>

    <x-admin.ui.modal id="planModal">
        <x-admin.ui.modal.header>
            <div>
                <h4 class="modal-title mb-2">@lang('Extend Subscription Limits')</h4>
                <span class="d-block fs-14 modal-subtitle">
                    @lang('Update the users active subscription plan limits. Use -1 in any limit field to allow unlimited access')
                </span>
            </div>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form method="POST" action="{{ route('admin.users.extend.limit', $user->id) }}" class="row">
                @csrf
                <div class="col-12">
                    <div class="alert alert--warning mb-3">
                        <p>
                            @lang('This is a manual subscription plan or plan limit extension feature. All financial records related to plan assignment or plan limit extensions will be not recorded in the system. However, the entire process is managed manually by the administrator.')
                        </p>
                    </div>
                </div>
                <div class="form-group col-lg-6">
                    <label>@lang('Whatsapp Account Limit')</label>
                    <input class="form-control" type="number" name="account_limit" min="-1"
                        value="{{ old('account_limit') }}" required>
                </div>
                <div class="form-group col-lg-6">
                    <label>@lang('Agent Limit')</label>
                    <input class="form-control" type="number" name="agent_limit" min="-1"
                        value="{{ old('agent_limit') }}" required>
                </div>
                <div class="form-group col-lg-6">
                    <label>@lang('Contact Limit')</label>
                    <input class="form-control" type="number" name="contact_limit" min="-1"
                        value="{{ old('contact_limit') }}" required>
                </div>
                <div class="form-group col-lg-6">
                    <label>@lang('Template Limit')</label>
                    <input class="form-control" type="number" name="template_limit"
                        min="-1"value="{{ old('template_limit') }}" required>
                </div>
                <div class="form-group col-lg-6">
                    <label>@lang('Automation Flow Limit')</label>
                    <input class="form-control" type="number" name="flow_limit" min="-1"
                        value="{{ old('flow_limit') }}" required>
                </div>
                <div class="form-group col-lg-6">
                    <label>@lang('Campaign Limit')</label>
                    <input class="form-control" type="number" name="campaign_limit" min="-1"
                        value="{{ old('campaign_limit') }}" required>
                </div>
                <div class="form-group col-lg-6">
                    <label>@lang('ShortLink Limit')</label>
                    <input class="form-control" type="number" name="short_link_limit" min="-1"
                        value="{{ old('short_link_limit') }}" required>
                </div>
                <div class="form-group col-lg-6">
                    <label>@lang('Floater Limit')</label>
                    <input class="form-control" type="number" name="floater_limit" min="-1"
                        value="{{ old('floater_limit') }}" required>
                </div>
                <div class="form-group col-12">
                    <label>@lang('Expiration Date')</label>
                    <input class="form-control date-picker" type="date" name="expiration_date"
                        value="{{ $user->plan_expired_at ?? '' }}" required>
                </div>
                <div class="form-group col-lg-4">
                    <div class="verification-switch">
                        <div class="verification-switch__item">
                            <label class="form-check-label fw-500" for="welcome_message">@lang('Welcome Message')</label>
                            <div class="form-check form-switch form-switch-success form--switch pl-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="welcome_message"
                                    name="welcome_message">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-4">
                    <div class="verification-switch">
                        <div class="verification-switch__item">
                            <label class="form-check-label fw-500" for="ai_assistance">@lang('AI Assistance')</label>
                            <div class="form-check form-switch form-switch-success form--switch pl-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="ai_assistance"
                                    name="ai_assistance">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-4">
                    <div class="verification-switch">
                        <div class="verification-switch__item">
                            <label class="form-check-label fw-500" for="interactive_message">@lang('Interactive Message')</label>
                            <div class="form-check form-switch form-switch-success form--switch pl-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="interactive_message"
                                    name="interactive_message">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-4">
                    <div class="verification-switch">
                        <div class="verification-switch__item">
                            <label class="form-check-label fw-500" for="ecommerce_available">@lang('E-commerce Available')</label>
                            <div class="form-check form-switch form-switch-success form--switch pl-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="ecommerce_available"
                                    name="ecommerce_available">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-4">
                    <div class="verification-switch">
                        <div class="verification-switch__item">
                            <label class="form-check-label fw-500" for="api_available">@lang('API Available')</label>
                            <div class="form-check form-switch form-switch-success form--switch pl-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="api_available"
                                    name="api_available">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex gap-2 flex-wrap">
        @if ($user->is_agent == Status::NO)
            <x-admin.permission_check permission="update user">
                <button type="button" class="flex-fill btn btn--info subscriptionAssignBtn">
                    <i class="las la-clipboard-check me-1"></i>@lang('Assign Subscription')
                </button>
                @if ($user->plan)
                    <button type="button" class="flex-fill btn btn--primary planExtendBtn">
                        <i class="las la-sliders-h me-1"></i>@lang('Extend Plan Limits')
                    </button>
                @endif
            </x-admin.permission_check>
            <x-admin.permission_check permission="update user balance">
                <button type="button" class=" flex-fill btn  btn--success balance-adjust" data-act="add">
                    <i class="las la-plus me-1"></i>@lang('Balance')
                </button>
                <button type="button" class="flex-fill btn  btn--danger balance-adjust" data-act="sub">
                    <i class="las la-minus-circle me-1"></i>@lang('Balance')
                </button>
            </x-admin.permission_check>
        @endif
        <x-admin.permission_check permission="ban user">
            @if ($user->status == Status::USER_ACTIVE)
                <button type="button" class="flex-fill btn  btn--warning" data-bs-toggle="modal"
                    data-bs-target="#userStatusModal">
                    <i class="las la-ban me-1"></i>@lang('Ban User')
                </button>
            @else
                <button type="button" class="flex-fill btn  btn--info" data-bs-toggle="modal"
                    data-bs-target="#userStatusModal">
                    <i class="las la-ban me-1"></i>@lang('Unban User')
                </button>
            @endif
        </x-admin.permission_check>
        <x-admin.permission_check permission="view user notifications">
            <a href="{{ route('admin.users.notification.log', $user->id) }}" class="flex-fill btn  btn--secondary">
                <i class="las la-bell me-1"></i>@lang('Notifications')
            </a>
        </x-admin.permission_check>
        <x-admin.permission_check permission="update user">
            @if ($user->kyc_data)
                <a href="{{ route('admin.users.kyc.details', $user->id) }}" target="_blank"
                    class="flex-fill btn  btn--info">
                    <i class="las la-user-check me-1"></i>@lang('KYC Data')
                </a>
            @endif
        </x-admin.permission_check>
    </div>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/flatpickr.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/global/css/flatpickr.min.css') }}">
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {

            const $extendModal = $('#planModal');
            const $subscriptionModal = $('#subscriptionAssignModal');
            const user = @json($user);

            // Date picker
            $(".date-picker").flatpickr({
                mode: 'single',
                minDate: new Date(),
                dateFormat: "Y-m-d",
                defaultDate: user.plan_expired_at ? new Date(user.plan_expired_at) : null,
            });

            $('.subscriptionAssignBtn').on('click', function(e) {
                $subscriptionModal.modal('show');
            });

            $('.planExtendBtn').on('click', function(e) {

                $subscriptionModal.modal('hide');

                $extendModal.find('input[name=account_limit]').val(user.account_limit);
                $extendModal.find('input[name=agent_limit]').val(user.agent_limit);
                $extendModal.find('input[name=contact_limit]').val(user.contact_limit);
                $extendModal.find('input[name=template_limit]').val(user.template_limit);
                $extendModal.find('input[name=campaign_limit]').val(user.campaign_limit);
                $extendModal.find('input[name=short_link_limit]').val(user.short_link_limit);
                $extendModal.find('input[name=floater_limit]').val(user.floater_limit);
                $extendModal.find('input[name=flow_limit]').val(user.flow_limit);
                $extendModal.find('input[name=welcome_message]').prop('checked', user.welcome_message);
                $extendModal.find('input[name=ai_assistance]').prop('checked', user.ai_assistance);
                $extendModal.find('input[name=interactive_message]').prop('checked', user.interactive_message);
                $extendModal.find('input[name=ecommerce_available]').prop('checked', user.ecommerce_available);
                $extendModal.find('input[name=api_available]').prop('checked', user.api_available);

                $extendModal.modal('show');
            });

            $(".balance-adjust").on('click', function(e) {
                const modal = $('#addSubModal');
                const act = $(this).data('act');
                const id = $(this).data('id');

                if (act == 'add') {
                    modal.find(".modal-title").text("@lang('Add Balance')");
                    modal.find(".modal-subtitle").text("@lang('Add funds to user accounts by entering the desired amount below')");
                } else {
                    modal.find(".modal-title").text("@lang('Subtract Balance')");
                    modal.find(".modal-subtitle").text("@lang('Subtract funds to user accounts by entering the desired amount below')");
                }
                modal.find('input[name=act]').val(act);
                modal.modal('show');
            });

            const inputValues = {};
            const $formElements = $('.user-form input, .user-form select').not("[name=_token]");
            const $submitButton = $(".user-form").find('button[type=submit]');

            $formElements.each(function(i, element) {
                const $element = $(element);
                const name = $element.attr('name');
                const type = $element.attr('type');
                var value = $element.val();

                if (type == 'checkbox') {
                    value = $element.is(":checked");
                }
                const inputValue = {
                    inittial_value: value,
                    new_value: value,
                }
                inputValues[name] = inputValue;
            });

            $(".user-form").on('input change', 'input,select', function(e) {
                const name = $(this).attr('name');
                const type = $(this).attr('type');
                var value = $(this).val();

                if (type == 'checkbox') {
                    value = $(this).is(":checked");
                }

                const oldInputValue = inputValues[name];
                const newInputValue = {
                    inittial_value: oldInputValue.inittial_value,
                    new_value: value,
                };
                inputValues[name] = newInputValue;

                btnEnableDisable();
            });

            // submit btn disable/enable depend on input values
            function btnEnableDisable() {
                var isDisabled = true;
                $.each(inputValues, function(i, element) {
                    if (element.inittial_value != element.new_value) {
                        isDisabled = false;
                        return;
                    }
                });
                if (isDisabled) {
                    $submitButton.addClass("disabled").attr('disabled', true);
                } else {
                    $submitButton.removeClass("disabled").attr('disabled', false);
                }
            }

            let mobileElement = $('.mobile-code');
            $('select[name=country]').on('change', function() {
                mobileElement.text(`+${$('select[name=country] :selected').data('mobile_code')}`);
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .modal .verification-switch {
            grid-template-columns: unset;
        }

        .modal .list-group-item {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding-left: 0;
        }
    </style>
@endpush
