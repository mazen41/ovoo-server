@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="dashboard-body__top">
                <div class="left">
                    <p class="text">@lang('Here’s your overview of your account.')</p>
                </div>
                <x-permission_check :permission="['add contact', 'add campaign']">
                    <div class="right">
                        <x-permission_check permission="add contact">
                            <a href="{{ route('user.contact.create') }}" class="btn btn--info btn-shadow">
                                <span class="btn-icon">
                                    <i class="fas fa-user"></i>
                                </span> @lang('Add Contacts')
                            </a>
                        </x-permission_check>
                        <x-permission_check permission="add campaign">
                            <a href="{{ route('user.campaign.create') }}" class="btn btn--base btn-shadow">
                                <span class="btn-icon">
                                    <i class="fa-solid fa-bullhorn"></i>
                                </span>@lang('Create Campaign')
                            </a>
                        </x-permission_check>
                    </div>
                </x-permission_check>
            </div>
        </div>
    </div>
    @php
        $kyc = getContent('kyc.content', true);
    @endphp

    @if (auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason)
        <div class="alert alert--danger mb-3" role="alert">
            <span class="alert__icon">
                <i class="las la-info-circle"></i>
            </span>
            <div class="alert__content">
                <div class="flex-between mb-2 gap-2 align-items-start">
                    <h5 class="alert__title mb-0">@lang('KYC Documents Rejected')</h5>
                </div>
                <p class="mb-0">
                    {{ __(@$kyc->data_values->reject) }}
                    <a class="text--info" href="{{ route('user.kyc.form') }}">@lang('Click Here to Re-submit Documents')</a>,
                    <a class="text--info" href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a>,
                    <button type="button" class="text--danger" data-bs-toggle="modal"
                        data-bs-target="#kycRejectionReason">@lang('Show Reject Reason')
                    </button>
                </p>
            </div>
        </div>
    @elseif(auth()->user()->kv == Status::KYC_UNVERIFIED)
        <div class="alert alert--info mb-3" role="alert">
            <span class="alert__icon">
                <i class="las la-info-circle"></i>
            </span>
            <div class="alert__content">
                <h5 class="alert__title">@lang('KYC Verification required')</h5>
                <p class="mb-0">
                    {{ __(@$kyc->data_values->required) }}
                    <a href="{{ route('user.kyc.form') }}">@lang('Click Here to Submit Documents')</a>
                </p>
            </div>
        </div>
    @elseif(auth()->user()->kv == Status::KYC_PENDING)
        <div class="alert alert--warning mb-3" role="alert">
            <span class="alert__icon">
                <i class="las la-info-circle"></i>
            </span>
            <div class="alert__content">
                <h4 class="alert__title">@lang('KYC Verification pending')</h4>
                <p class="mb-0">
                    {{ __(@$kyc->data_values->pending) }}
                    <a href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a>
                </p>
            </div>
        </div>
    @endif

    <div class="row gy-4">
        <x-permission_check permission="view wallet">
            <div class="col-xxl-3 col-sm-6">
                <a href="{{ route('user.transactions') }}" class="dashboard-widget widget-two widget-blue">
                    <div class="dashboard-widget__icon-box">
                        <div class="dashboard-widget__icon flex-center">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M2 7C2 5.89543 2.89543 5 4 5H20C20.5523 5 21 5.44772 21 6V8C21 8.55228 20.5523 9 20 9H5C3.89543 9 3 9.89543 3 11V18C3 19.1046 3.89543 20 5 20H19C20.1046 20 21 19.1046 21 18V14C21 13.4477 20.5523 13 20 13H16C15.4477 13 15 13.4477 15 14V16C15 16.5523 15.4477 17 16 17H19"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                    <div class="dashboard-widget__content">
                        <h6 class="dashboard-widget__text">@lang('Wallet Balance')</h6>
                        <p class="dashboard-widget__number">
                            {{ showAmount(@$user->balance) }}
                        </p>
                    </div>
                </a>
            </div>
        </x-permission_check>
        <x-permission_check permission="view contact">
            <div class="col-xxl-3 col-sm-6">
                <a href="{{ route('user.contact.list') }}" class="dashboard-widget widget-two widget-blue">
                    <div class="dashboard-widget__icon-box">
                        <div class="dashboard-widget__icon flex-center">
                            <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M21.3334 2.66669H10.6667C6.25469 2.66669 2.66669 6.25469 2.66669 10.6667V28C2.66669 28.3536 2.80716 28.6928 3.05721 28.9428C3.30726 29.1929 3.6464 29.3334 4.00002 29.3334H21.3334C25.7454 29.3334 29.3334 25.7454 29.3334 21.3334V10.6667C29.3334 6.25469 25.7454 2.66669 21.3334 2.66669ZM26.6667 21.3334C26.6667 24.2747 24.2747 26.6667 21.3334 26.6667H5.33335V10.6667C5.33335 7.72535 7.72535 5.33335 10.6667 5.33335H21.3334C24.2747 5.33335 26.6667 7.72535 26.6667 10.6667V21.3334Z"
                                    fill="currentColor" />
                                <path
                                    d="M9.33331 12H22.6666V14.6667H9.33331V12ZM9.33331 17.3333H18.6666V20H9.33331V17.3333Z"
                                    fill="currentColor" />
                            </svg>
                        </div>
                    </div>
                    <div class="dashboard-widget__content">
                        <h6 class="dashboard-widget__text">@lang('Total Contact')</h6>
                        <p class="dashboard-widget__number">
                            {{ @$widget['total_contact'] }}
                        </p>
                    </div>
                </a>
            </div>
        </x-permission_check>
        <x-permission_check permission="view contact tag">
            <div class="col-xxl-3 col-sm-6">
                <a href="{{ route('user.contacttag.list') }}" class="dashboard-widget widget-two widget-blue">
                    <div class="dashboard-widget__icon-box">
                        <div class="dashboard-widget__icon flex-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="CurrentColor" fill="none">
                                <circle cx="1.5" cy="1.5" r="1.5" transform="matrix(1 0 0 -1 16 8.00024)"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                </circle>
                                <path
                                    d="M2.77423 11.1439C1.77108 12.2643 1.7495 13.9546 2.67016 15.1437C4.49711 17.5033 6.49674 19.5029 8.85633 21.3298C10.0454 22.2505 11.7357 22.2289 12.8561 21.2258C15.8979 18.5022 18.6835 15.6559 21.3719 12.5279C21.6377 12.2187 21.8039 11.8397 21.8412 11.4336C22.0062 9.63798 22.3452 4.46467 20.9403 3.05974C19.5353 1.65481 14.362 1.99377 12.5664 2.15876C12.1603 2.19608 11.7813 2.36233 11.472 2.62811C8.34412 5.31646 5.49781 8.10211 2.77423 11.1439Z"
                                    stroke="CurrentColor" stroke-width="1.5"></path>
                                <path d="M7.00002 14.0002L10 17.0002" stroke="CurrentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="dashboard-widget__content">
                        <h6 class="dashboard-widget__text">@lang('Total Contact Tag')</h6>
                        <p class="dashboard-widget__number">
                            {{ @$widget['total_tag'] }}
                        </p>
                    </div>
                </a>
            </div>
        </x-permission_check>
        <x-permission_check permission="view contact list">
            <div class="col-xxl-3 col-sm-6">
                <a href="{{ route('user.contactlist.list') }}" class="dashboard-widget widget-two widget-blue">
                    <div class="dashboard-widget__icon-box">
                        <div class="dashboard-widget__icon flex-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="CurrentColor"
                                fill="none">
                                <path
                                    d="M7.5 19.5C7.5 18.5344 7.82853 17.5576 8.63092 17.0204C9.59321 16.3761 10.7524 16 12 16C13.2476 16 14.4068 16.3761 15.3691 17.0204C16.1715 17.5576 16.5 18.5344 16.5 19.5"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                                <circle cx="12" cy="11" r="2.5" stroke="CurrentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></circle>
                                <path
                                    d="M17.5 11C18.6101 11 19.6415 11.3769 20.4974 12.0224C21.2229 12.5696 21.5 13.4951 21.5 14.4038V14.5"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                                <circle cx="17.5" cy="6.5" r="2" stroke="CurrentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></circle>
                                <path
                                    d="M6.5 11C5.38987 11 4.35846 11.3769 3.50256 12.0224C2.77706 12.5696 2.5 13.4951 2.5 14.4038V14.5"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                                <circle cx="6.5" cy="6.5" r="2" stroke="CurrentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></circle>
                            </svg>
                        </div>
                    </div>
                    <div class="dashboard-widget__content">
                        <h6 class="dashboard-widget__text">@lang('Total Contact List')</h6>
                        <p class="dashboard-widget__number">
                            {{ @$widget['total_list'] }}
                        </p>
                    </div>
                </a>
            </div>
        </x-permission_check>
        <x-permission_check permission="view campaign">
            <div class="col-xxl-3 col-sm-6">
                <a href="{{ route('user.campaign.index') }}" class="dashboard-widget widget-two widget-purple">
                    <div class="dashboard-widget__icon-box">
                        <div class="dashboard-widget__icon flex-center">
                            <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M24 14.6666V17.3333H29.3333V14.6666H24ZM21.3333 23.48C22.6133 24.4266 24.28 25.68 25.6 26.6666C26.1333 25.96 26.6667 25.24 27.2 24.5333C25.88 23.5466 24.2133 22.2933 22.9333 21.3333C22.4 22.0533 21.8667 22.7733 21.3333 23.48ZM27.2 7.46665C26.6667 6.75998 26.1333 6.03998 25.6 5.33331C24.28 6.31998 22.6133 7.57331 21.3333 8.53331C21.8667 9.23998 22.4 9.95998 22.9333 10.6666C24.2133 9.70665 25.88 8.46665 27.2 7.46665ZM5.33332 12C3.86666 12 2.66666 13.2 2.66666 14.6666V17.3333C2.66666 18.8 3.86666 20 5.33332 20H6.66666V25.3333H9.33332V20H10.6667L17.3333 24V7.99998L10.6667 12H5.33332ZM20.6667 16C20.6667 14.2266 19.8933 12.6266 18.6667 11.5333V20.4533C19.8933 19.3733 20.6667 17.7733 20.6667 16Z"
                                    fill="currentColor" />
                            </svg>
                        </div>
                    </div>
                    <div class="dashboard-widget__content">
                        <h6 class="dashboard-widget__text">@lang('Total Campaign')</h6>
                        <p class="dashboard-widget__number">
                            {{ @$widget['total_campaign'] }}
                        </p>
                    </div>
                </a>
            </div>
        </x-permission_check>
        <x-permission_check permission="view campaign">
            <div class="col-xxl-3 col-sm-6">
                <a href="{{ route('user.campaign.index') }}" class="dashboard-widget  widget-two widget-purple"
                    data-bs-toggle="tooltip" title="@lang('Total Campaigns Message')">
                    <div class="dashboard-widget__icon-box">
                        <div class="dashboard-widget__icon flex-center">
                            <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M24 14.6666V17.3333H29.3333V14.6666H24ZM21.3333 23.48C22.6133 24.4266 24.28 25.68 25.6 26.6666C26.1333 25.96 26.6667 25.24 27.2 24.5333C25.88 23.5466 24.2133 22.2933 22.9333 21.3333C22.4 22.0533 21.8667 22.7733 21.3333 23.48ZM27.2 7.46665C26.6667 6.75998 26.1333 6.03998 25.6 5.33331C24.28 6.31998 22.6133 7.57331 21.3333 8.53331C21.8667 9.23998 22.4 9.95998 22.9333 10.6666C24.2133 9.70665 25.88 8.46665 27.2 7.46665ZM5.33332 12C3.86666 12 2.66666 13.2 2.66666 14.6666V17.3333C2.66666 18.8 3.86666 20 5.33332 20H6.66666V25.3333H9.33332V20H10.6667L17.3333 24V7.99998L10.6667 12H5.33332ZM20.6667 16C20.6667 14.2266 19.8933 12.6266 18.6667 11.5333V20.4533C19.8933 19.3733 20.6667 17.7733 20.6667 16Z"
                                    fill="currentColor" />
                            </svg>
                        </div>
                    </div>
                    <div class="dashboard-widget__content">
                        <h6 class="dashboard-widget__text">@lang('Total Campaign Message')</h6>
                        <p class="dashboard-widget__number">
                            {{ @$widget['total_campaign_message'] }}
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <a href="{{ route('user.campaign.index') }}" class="dashboard-widget  widget-two widget-purple"
                    data-bs-toggle="tooltip" title="@lang('Total Campaign Delivered Message')">
                    <div class="dashboard-widget__icon-box">
                        <div class="dashboard-widget__icon flex-center">
                            <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M24 14.6666V17.3333H29.3333V14.6666H24ZM21.3333 23.48C22.6133 24.4266 24.28 25.68 25.6 26.6666C26.1333 25.96 26.6667 25.24 27.2 24.5333C25.88 23.5466 24.2133 22.2933 22.9333 21.3333C22.4 22.0533 21.8667 22.7733 21.3333 23.48ZM27.2 7.46665C26.6667 6.75998 26.1333 6.03998 25.6 5.33331C24.28 6.31998 22.6133 7.57331 21.3333 8.53331C21.8667 9.23998 22.4 9.95998 22.9333 10.6666C24.2133 9.70665 25.88 8.46665 27.2 7.46665ZM5.33332 12C3.86666 12 2.66666 13.2 2.66666 14.6666V17.3333C2.66666 18.8 3.86666 20 5.33332 20H6.66666V25.3333H9.33332V20H10.6667L17.3333 24V7.99998L10.6667 12H5.33332ZM20.6667 16C20.6667 14.2266 19.8933 12.6266 18.6667 11.5333V20.4533C19.8933 19.3733 20.6667 17.7733 20.6667 16Z"
                                    fill="currentColor" />
                            </svg>
                        </div>
                    </div>
                    <div class="dashboard-widget__content">
                        <h6 class="dashboard-widget__text">@lang('Total Delivered Message')</h6>
                        <p class="dashboard-widget__number">
                            {{ @$widget['total_campaign_message_delivered'] }}
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <a href="{{ route('user.contact.list') }}" class="dashboard-widget widget-two widget-purple"
                    data-bs-toggle="tooltip" title="@lang('Total Campaign Failed Message')">
                    <div class="dashboard-widget__icon-box">
                        <div class="dashboard-widget__icon flex-center">
                            <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M24 14.6666V17.3333H29.3333V14.6666H24ZM21.3333 23.48C22.6133 24.4266 24.28 25.68 25.6 26.6666C26.1333 25.96 26.6667 25.24 27.2 24.5333C25.88 23.5466 24.2133 22.2933 22.9333 21.3333C22.4 22.0533 21.8667 22.7733 21.3333 23.48ZM27.2 7.46665C26.6667 6.75998 26.1333 6.03998 25.6 5.33331C24.28 6.31998 22.6133 7.57331 21.3333 8.53331C21.8667 9.23998 22.4 9.95998 22.9333 10.6666C24.2133 9.70665 25.88 8.46665 27.2 7.46665ZM5.33332 12C3.86666 12 2.66666 13.2 2.66666 14.6666V17.3333C2.66666 18.8 3.86666 20 5.33332 20H6.66666V25.3333H9.33332V20H10.6667L17.3333 24V7.99998L10.6667 12H5.33332ZM20.6667 16C20.6667 14.2266 19.8933 12.6266 18.6667 11.5333V20.4533C19.8933 19.3733 20.6667 17.7733 20.6667 16Z"
                                    fill="currentColor" />
                            </svg>
                        </div>
                    </div>
                    <div class="dashboard-widget__content">
                        <h6 class="dashboard-widget__text">@lang('Total Failed Message')</h6>
                        <p class="dashboard-widget__number">
                            {{ @$widget['total_campaign_message_failed'] }}
                        </p>
                    </div>
                </a>
            </div>
        </x-permission_check>
        <x-permission_check permission="view flow builder">
            <div class="col-xxl-3 col-sm-6">
                <a href="{{ route('user.flow.builder.index') }}" class="dashboard-widget widget-two widget-red">
                    <div class="dashboard-widget__icon-box">
                        <div class="dashboard-widget__icon flex-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="CurrentColor"
                                fill="none">
                                <path
                                    d="M11 8H13C15.8284 8 17.2426 8 18.1213 8.87868C19 9.75736 19 11.1716 19 14C19 16.8284 19 18.2426 18.1213 19.1213C17.2426 20 15.8284 20 13 20H12C12 20 11.5 22 8 22C8 22 9 20.9913 9 19.9827C7.44655 19.9359 6.51998 19.7626 5.87868 19.1213C5 18.2426 5 16.8284 5 14C5 11.1716 5 9.75736 5.87868 8.87868C6.75736 8 8.17157 8 11 8Z"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linejoin="round"></path>
                                <path
                                    d="M19 11.5H19.5C20.4346 11.5 20.9019 11.5 21.25 11.701C21.478 11.8326 21.6674 12.022 21.799 12.25C22 12.5981 22 13.0654 22 14C22 14.9346 22 15.4019 21.799 15.75C21.6674 15.978 21.478 16.1674 21.25 16.299C20.9019 16.5 20.4346 16.5 19.5 16.5H19"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linejoin="round"></path>
                                <path
                                    d="M5 11.5H4.5C3.56538 11.5 3.09808 11.5 2.75 11.701C2.52197 11.8326 2.33261 12.022 2.20096 12.25C2 12.5981 2 13.0654 2 14C2 14.9346 2 15.4019 2.20096 15.75C2.33261 15.978 2.52197 16.1674 2.75 16.299C3.09808 16.5 3.56538 16.5 4.5 16.5H5"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linejoin="round"></path>
                                <path
                                    d="M13.5 3.5C13.5 4.32843 12.8284 5 12 5C11.1716 5 10.5 4.32843 10.5 3.5C10.5 2.67157 11.1716 2 12 2C12.8284 2 13.5 2.67157 13.5 3.5Z"
                                    stroke="CurrentColor" stroke-width="1.5"></path>
                                <path d="M12 5V8" stroke="CurrentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path d="M9 12V13M15 12V13" stroke="CurrentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M10 16.5C10 16.5 10.6667 17 12 17C13.3333 17 14 16.5 14 16.5"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="dashboard-widget__content">
                        <h6 class="dashboard-widget__text">@lang('Total Flow')</h6>
                        <p class="dashboard-widget__number">
                            {{ @$widget['total_flow'] }}
                        </p>
                    </div>
                </a>
            </div>
        </x-permission_check>
        <x-permission_check permission="view shortlink">
            <div class="col-xxl-3 col-sm-6">
                <a href="{{ route('user.shortlink.index') }}" class="dashboard-widget widget-two widget-red">
                    <div class="dashboard-widget__icon-box">
                        <div class="dashboard-widget__icon flex-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="CurrentColor"
                                fill="none">
                                <path
                                    d="M14 21H12C7.28595 21 4.92893 21 3.46447 19.5355C2 18.0711 2 15.714 2 11V7.94427C2 6.1278 2 5.21956 2.38032 4.53806C2.65142 4.05227 3.05227 3.65142 3.53806 3.38032C4.21956 3 5.1278 3 6.94427 3C8.10802 3 8.6899 3 9.19926 3.19101C10.3622 3.62712 10.8418 4.68358 11.3666 5.73313L12 7M8 7H16.75C18.8567 7 19.91 7 20.6667 7.50559C20.9943 7.72447 21.2755 8.00572 21.4944 8.33329C22 9.08996 22 10.1433 22 12.25C22 12.8889 22 13.4697 21.9949 14"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                <path
                                    d="M17.6863 20.4315C18.4444 21.1895 19.6734 21.1895 20.4315 20.4315C21.1895 19.6734 21.1895 18.4444 20.4315 17.6863L18.7157 15.9705C17.9922 15.247 16.8396 15.2141 16.077 15.8717M16.3137 13.5685C15.5556 12.8105 14.3266 12.8105 13.5685 13.5685C12.8105 14.3266 12.8105 15.5557 13.5685 16.3137L15.2843 18.0294C16.0078 18.753 17.1604 18.7859 17.9231 18.1282"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="dashboard-widget__content">
                        <h6 class="dashboard-widget__text">@lang('Total ShortLink')</h6>
                        <p class="dashboard-widget__number">
                            {{ @$widget['total_shortlink'] }}
                        </p>
                    </div>
                </a>
            </div>
        </x-permission_check>
        <x-permission_check permission="view floater">
            <div class="col-xxl-3 col-sm-6">
                <a href="{{ route('user.floater.index') }}" class="dashboard-widget widget-two widget-red">
                    <div class="dashboard-widget__icon-box">
                        <div class="dashboard-widget__icon flex-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="CurrentColor"
                                fill="none">
                                <path
                                    d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 13.3789 2.27907 14.6926 2.78382 15.8877C3.06278 16.5481 3.20226 16.8784 3.21953 17.128C3.2368 17.3776 3.16334 17.6521 3.01642 18.2012L2 22L5.79877 20.9836C6.34788 20.8367 6.62244 20.7632 6.87202 20.7805C7.12161 20.7977 7.45185 20.9372 8.11235 21.2162C9.30745 21.7209 10.6211 22 12 22Z"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linejoin="round"></path>
                                <path
                                    d="M8.58815 12.3773L9.45909 11.2956C9.82616 10.8397 10.2799 10.4153 10.3155 9.80826C10.3244 9.65494 10.2166 8.96657 10.0008 7.58986C9.91601 7.04881 9.41086 7 8.97332 7C8.40314 7 8.11805 7 7.83495 7.12931C7.47714 7.29275 7.10979 7.75231 7.02917 8.13733C6.96539 8.44196 7.01279 8.65187 7.10759 9.07169C7.51023 10.8548 8.45481 12.6158 9.91948 14.0805C11.3842 15.5452 13.1452 16.4898 14.9283 16.8924C15.3481 16.9872 15.558 17.0346 15.8627 16.9708C16.2477 16.8902 16.7072 16.5229 16.8707 16.165C17 15.8819 17 15.5969 17 15.0267C17 14.5891 16.9512 14.084 16.4101 13.9992C15.0334 13.7834 14.3451 13.6756 14.1917 13.6845C13.5847 13.7201 13.1603 14.1738 12.7044 14.5409L11.6227 15.4118"
                                    stroke="CurrentColor" stroke-width="1.5"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="dashboard-widget__content">
                        <h6 class="dashboard-widget__text">@lang('Total Floater')</h6>
                        <p class="dashboard-widget__number">
                            {{ @$widget['total_floater'] }}
                        </p>
                    </div>
                </a>
            </div>
        </x-permission_check>
        @if (isParentUser())
            <div class="col-xxl-3 col-sm-6">
                <a href="{{ route('user.deposit.history') }}" class="dashboard-widget widget-two widget-red">

                    <div class="dashboard-widget__icon-box">
                        <div class="dashboard-widget__icon flex-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="CurrentColor"
                                fill="none">
                                <path
                                    d="M20.9427 16.8354C20.2864 12.8866 18.2432 9.94613 16.467 8.219C15.9501 7.71642 15.6917 7.46513 15.1208 7.23257C14.5499 7 14.0592 7 13.0778 7H10.9222C9.94081 7 9.4501 7 8.87922 7.23257C8.30834 7.46513 8.04991 7.71642 7.53304 8.219C5.75682 9.94613 3.71361 12.8866 3.05727 16.8354C2.56893 19.7734 5.27927 22 8.30832 22H15.6917C18.7207 22 21.4311 19.7734 20.9427 16.8354Z"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                                <path
                                    d="M7.25662 4.44287C7.05031 4.14258 6.75128 3.73499 7.36899 3.64205C8.00392 3.54651 8.66321 3.98114 9.30855 3.97221C9.89237 3.96413 10.1898 3.70519 10.5089 3.33548C10.8449 2.94617 11.3652 2 12 2C12.6348 2 13.1551 2.94617 13.4911 3.33548C13.8102 3.70519 14.1076 3.96413 14.6914 3.97221C15.3368 3.98114 15.9961 3.54651 16.631 3.64205C17.2487 3.73499 16.9497 4.14258 16.7434 4.44287L15.8105 5.80064C15.4115 6.38146 15.212 6.67187 14.7944 6.83594C14.3769 7 13.8373 7 12.7582 7H11.2418C10.1627 7 9.6231 7 9.20556 6.83594C8.78802 6.67187 8.5885 6.38146 8.18945 5.80064L7.25662 4.44287Z"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linejoin="round"></path>
                                <path
                                    d="M13.6267 12.9186C13.4105 12.1205 12.3101 11.4003 10.9892 11.9391C9.66829 12.4778 9.45847 14.2113 11.4565 14.3955C12.3595 14.4787 12.9483 14.2989 13.4873 14.8076C14.0264 15.3162 14.1265 16.7308 12.7485 17.112C11.3705 17.4932 10.006 16.8976 9.85742 16.0517M11.8417 10.9927V11.7531M11.8417 17.2293V17.9927"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="dashboard-widget__content">
                        <h6 class="dashboard-widget__text">@lang('Total Deposit')</h6>
                        <p class="dashboard-widget__number">
                            {{ showAmount($widget['total_deposit_amount']) }}
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <a href="{{ route('user.withdraw.history') }}" class="dashboard-widget widget-two widget-green">
                    <div class="dashboard-widget__icon-box">
                        <div class="dashboard-widget__icon flex-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="CurrentColor"
                                fill="none">
                                <path
                                    d="M19.7453 13C20.5362 11.8662 21 10.4872 21 9C21 5.13401 17.866 2 14 2C10.134 2 7 5.134 7 9C7 10.0736 7.24169 11.0907 7.67363 12"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                                <path
                                    d="M12.4375 11.6667L12.4375 6.33333M14 6.33333V5M14 13V11.6667M12.4375 9H15.5625M15.5625 9C16.0803 9 16.5 9.44772 16.5 10V10.6667C16.5 11.219 16.0803 11.6667 15.5625 11.6667H11.5M15.5625 9C16.0803 9 16.5 8.55228 16.5 8V7.33333C16.5 6.78105 16.0803 6.33333 15.5625 6.33333H11.5"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                                <path
                                    d="M3 14H5.39482C5.68897 14 5.97908 14.0663 6.24217 14.1936L8.28415 15.1816C8.54724 15.3089 8.83735 15.3751 9.1315 15.3751H10.1741C11.1825 15.3751 12 16.1662 12 17.142C12 17.1814 11.973 17.2161 11.9338 17.2269L9.39287 17.9295C8.93707 18.0555 8.449 18.0116 8.025 17.8064L5.84211 16.7503M12 16.5L16.5928 15.0889C17.407 14.8352 18.2871 15.136 18.7971 15.8423C19.1659 16.3529 19.0157 17.0842 18.4785 17.3942L10.9629 21.7305C10.4849 22.0063 9.92094 22.0736 9.39516 21.9176L3 20.0199"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="dashboard-widget__content">
                        <h6 class="dashboard-widget__text">@lang('Total Withdraw')</h6>
                        <p class="dashboard-widget__number">
                            {{ showAmount($widget['total_withdraw_amount']) }}
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <a href="{{ route('user.referral.index') }}" class="dashboard-widget widget-two widget-green">
                    <div class="dashboard-widget__icon-box">
                        <div class="dashboard-widget__icon flex-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="CurrentColor"
                                fill="none">
                                <path d="M19.9999 17L3.99994 17" stroke="CurrentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M17 14C17 14 19.9999 16.2095 19.9999 17C19.9999 17.7906 16.9999 20 16.9999 20"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                                <path d="M4.99994 7L19.9999 7" stroke="CurrentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                <path
                                    d="M6.99991 4C6.99991 4 3.99994 6.20947 3.99994 7.00002C3.99993 7.79058 6.99994 10 6.99994 10"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="dashboard-widget__content">
                        <h6 class="dashboard-widget__text">@lang('Total Refer User')</h6>
                        <p class="dashboard-widget__number">
                            {{ $widget['total_referrer'] }}
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <a href="{{ route('user.transactions') }}" class="dashboard-widget widget-two widget-green">
                    <div class="dashboard-widget__icon-box">
                        <div class="dashboard-widget__icon flex-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="CurrentColor"
                                fill="none">
                                <path d="M19.9999 17L3.99994 17" stroke="CurrentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M17 14C17 14 19.9999 16.2095 19.9999 17C19.9999 17.7906 16.9999 20 16.9999 20"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                                <path d="M4.99994 7L19.9999 7" stroke="CurrentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                <path
                                    d="M6.99991 4C6.99991 4 3.99994 6.20947 3.99994 7.00002C3.99993 7.79058 6.99994 10 6.99994 10"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="dashboard-widget__content">
                        <h6 class="dashboard-widget__text">@lang('Total Transaction')</h6>
                        <p class="dashboard-widget__number">
                            {{ $widget['total_transaction'] }}
                        </p>
                    </div>
                </a>
            </div>
        @endif
        <x-permission_check permission="view subscription">
            <div class="col-xxl-3 col-sm-6">
                <a href="{{ route('user.subscription.index') }}" class="dashboard-widget widget-two widget-green">
                    <div class="dashboard-widget__icon-box">
                        <div class="dashboard-widget__icon flex-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="CurrentColor"
                                fill="none">
                                <path d="M9 13C9 13 10 13 11 15C11 15 14.1765 10 17 9" stroke="CurrentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path
                                    d="M21 11.1833V8.28029C21 6.64029 21 5.82028 20.5959 5.28529C20.1918 4.75029 19.2781 4.49056 17.4507 3.9711C16.2022 3.6162 15.1016 3.18863 14.2223 2.79829C13.0234 2.2661 12.424 2 12 2C11.576 2 10.9766 2.2661 9.77771 2.79829C8.89839 3.18863 7.79784 3.61619 6.54933 3.9711C4.72193 4.49056 3.80822 4.75029 3.40411 5.28529C3 5.82028 3 6.64029 3 8.28029V11.1833C3 16.8085 8.06277 20.1835 10.594 21.5194C11.2011 21.8398 11.5046 22 12 22C12.4954 22 12.7989 21.8398 13.406 21.5194C15.9372 20.1835 21 16.8085 21 11.1833Z"
                                    stroke="CurrentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="dashboard-widget__content">
                        <h6 class="dashboard-widget__text">@lang('Plan Status')</h6>
                        <p class="dashboard-widget__number">
                            <span class="text">
                                @if (getParentUser()->plan)
                                    {{ __(getParentUser()->plan->name) }}
                                    @if (now()->parse(getParentUser()->plan_expired_at)->isPast())
                                        - <span class="text--danger">@lang('Expired')</span>
                                    @endif
                                @else
                                    @lang('N/A')
                                @endif
                            </span>
                        </p>
                    </div>
                </a>
            </div>
        </x-permission_check>

        @if (isParentUser())
            <div class="col-lg-6">
                <div class="transaction-table-inner h-100 d-flex flex-column">
                    <h6>@lang('Latest Transaction')</h6>
                    <div class="dashboard-table transaction-table flex-fill">
                        <table class="table table--responsive--lg">
                            <thead>
                                <tr>
                                    <th>@lang('Transaction ID')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Amount')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td> {{ $transaction->trx }} </td>
                                        <td class="text-lg-center">
                                            {{ showDateTime($transaction->created_at) }}<br>{{ diffForHumans($transaction->created_at) }}
                                        </td>
                                        <td class="text-end">
                                            <div>
                                                {{ showAmount($transaction->amount) }} + <span class="text--danger"
                                                    data-bs-toggle="tooltip"
                                                    title="@lang('Processing Charge')">{{ showAmount($transaction->charge) }}
                                                </span>
                                                <br>
                                                <strong data-bs-toggle="tooltip" title="@lang('Amount with charge')">
                                                    {{ showAmount($transaction->amount + $transaction->charge) }}
                                                </strong>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    @include('Template::partials.empty_message')
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        @endif
        <x-permission_check permission="view subscription">
            <div class="col-lg-6">
                <h6>@lang('Latest Contact')</h6>
                <div class="dashboard-table ">
                    <table class="table table--responsive--lg">
                        <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Mobile')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($contacts as $contact)
                                <tr>
                                    <td>
                                        <div
                                            class="d-flex align-items-center gap-2 flex-wrap justify-content-end justify-content-md-start">
                                            @include('Template::user.contact.thumb')
                                            {{ __(@$contact->fullName) }}
                                        </div>
                                    </td>
                                    <td>+{{ @$contact->mobileNumber }}</td>
                                </tr>
                            @empty
                                @include('Template::partials.empty_message')
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-permission_check>
    </div>

    @if (auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason)
        <div class="modal fade custom--modal" id="kycRejectionReason">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('KYC Document Rejection Reason')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span class="icon">
                                <i class="fas fa-times"></i>
                            </span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __(auth()->user()->kyc_rejection_reason) }}</p>
                    </div>
                </div>
            </div>
        </div>
        </div>
    @endif
@endsection
