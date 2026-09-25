@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__wrapper">
                <div class="container-top__left">
                    <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                    <p class="container-top__desc">@lang('Manage your subscription plans, billing preferences, and renewal settings.')</p>
                </div>
            </div>
            <ul class="nav nav-pills custom--tab tab-three" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#subscription" type="button"
                        role="tab" data-tab-id="subscription">
                        @lang('Subscription Info')
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-plans" type="button"
                        role="tab" data-tab-id="pricing-plans">
                        @lang('Pricing Plans')
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#purchase-history" type="button"
                        role="tab" data-tab-id="purchase-history">
                        @lang('Purchase History')
                    </button>
                </li>
            </ul>
        </div>
        <div class="dashboard-container__body">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="subscription" role="tabpanel">
                    @if ($plan && userSubscriptionExpiredCheck() && @$activeSubscription)
                        <div class="plan-wrapper">
                            <div class="active-card">
                                <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                                    <span class="active-card__badge">
                                        @lang('MY ACTIVE PLAN')
                                    </span>
                                    <div class="form-group">
                                        <label>@lang('Auto Renewal')</label>
                                        <div class="form--switch two">
                                            <input class="form-check-input autoRenewal" type="checkbox" role="switch"
                                                @checked(@$activeSubscription->auto_renewal)>
                                        </div>
                                    </div>
                                </div>
                                <div class="active-card__top">
                                    <h4 class="active-card__title"> {{ __(@$plan->name) }}</h4>
                                    <p class="active-card__desc">
                                        {{ __(@$plan->description) }}
                                    </p>
                                </div>
                                <div class="active-card__content">
                                    <ul class="text-list flex-column">
                                        <li class="text-list__item  justify-content-between gap-1 flex-wrap">
                                            <span class="active-plan-title">@lang('Total')</span>
                                            <span class="text--base fs-14">
                                                {{ showAmount($activeSubscription->amount) }}
                                            </span>
                                        </li>
                                        <li class="text-list__item justify-content-between gap-1 flex-wrap">
                                            <span class="active-plan-title">@lang('Billing Cycle')</span>
                                            <span class="text--base fs-14">
                                                {{ $activeSubscription->billing_cycle }}
                                            </span>
                                        </li>
                                        <li class="text-list__item justify-content-between gap-1 flex-wrap">
                                            <span class="active-plan-title">@lang('Purchase At')</span>
                                            <span class="text--base fs-14">
                                                {{ showDateTime($activeSubscription->created_at) }}
                                            </span>
                                        </li>
                                        <li class="text-list__item justify-content-between gap-1 flex-wrap">
                                            <span class="active-plan-title">@lang('Activated On')</span>
                                            <span class="text--base fs-14">
                                                {{ showDateTime($activeSubscription->created_at) }}
                                            </span>
                                        </li>
                                        <li class="text-list__item justify-content-between gap-1 flex-wrap">
                                            @lang('Next Billing Date') <span class="text--base fs-14">
                                                {{ showDateTime($user->plan_expired_at) }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="active-card__bottom">
                                    <button class="btn btn--base btn-shadow purchaseBtn"
                                        data-plan='@json($plan)'
                                        data-subscription='@json($activeSubscription)'>
                                        @lang('Renew Now') </button>
                                    <p>
                                        <a class="link">
                                            {{ diffForHumans($user->plan_expired_at) }}
                                            @lang(' until renewal.')
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="plan-wrapper__right">
                                <div class="plan-wrapper__top mb-4">
                                    <h5 class="title mb-1"> @lang('Feature Remaining Information') </h5>
                                    <p class="plan-wrapper__desc">@lang('Stay informed about upcoming capabilities and progress')</p>
                                </div>
                                <div class="plan-details">
                                    <div class="plan-details__item">
                                        <span class="item-title">@lang('WhatsApp Account')</span>
                                        {{ printLimit($user->account_limit) }}
                                    </div>
                                    <div class="plan-details__item">
                                        <span class="item-title">@lang('Agent Limit')</span>
                                        {{ printLimit(@$user->agent_limit) }}
                                    </div>
                                    <div class="plan-details__item">
                                        <span class="item-title">@lang('Contact Limit')</span>
                                        {{ printLimit(@$user->contact_limit) }}
                                    </div>
                                    <div class="plan-details__item">
                                        <span class="item-title">@lang('Template Limit')</span>
                                        {{ printLimit(@$user->template_limit) }}
                                    </div>
                                    <div class="plan-details__item">
                                        <span class="item-title">@lang('Automation Flow Limit')</span>
                                        {{ printLimit(@$user->flow_limit) }}
                                    </div>
                                    <div class="plan-details__item">
                                        <span class="item-title">@lang('Campaign Limit')</span>
                                        {{ printLimit(@$user->campaign_limit) }}
                                    </div>
                                    <div class="plan-details__item">
                                        <span class="item-title">@lang('ShortLink Limit')</span>
                                        {{ printLimit(@$user->short_link_limit) }}
                                    </div>
                                    <div class="plan-details__item">
                                        <span class="item-title">@lang('Floater Limit')</span>
                                        {{ printLimit(@$user->floater_limit) }}
                                    </div>
                                    <div class="plan-details__item">
                                        <span class="item-title">@lang('Welcome Message Available')</span>
                                        @if ($user->welcome_message)
                                            <span class="text--success">@lang('Yes')</span>
                                        @else
                                            <span class="text--danger">@lang('No')</span>
                                        @endif
                                    </div>
                                    <div class="plan-details__item">
                                        <span class="item-title">@lang('AI Assistance')</span>
                                        @if ($user->ai_assistance)
                                            <span class="text--success">@lang('Yes')</span>
                                        @else
                                            <span class="text--danger">@lang('No')</span>
                                        @endif
                                    </div>
                                    <div class="plan-details__item">
                                        <span class="item-title">@lang('Interactive Message')</span>
                                        @if ($user->interactive_message)
                                            <span class="text--success">@lang('Yes')</span>
                                        @else
                                            <span class="text--danger">@lang('No')</span>
                                        @endif
                                    </div>
                                    <div class="plan-details__item">
                                        <span class="item-title">@lang('Ecommerce Available')</span>
                                        @if ($user->ecommerce_available)
                                            <span class="text--success">@lang('Yes')</span>
                                        @else
                                            <span class="text--danger">@lang('No')</span>
                                        @endif
                                    </div>
                                    <div class="plan-details__item">
                                        <span class="item-title">@lang('API Available')</span>
                                        @if ($user->api_available)
                                            <span class="text--success">@lang('Yes')</span>
                                        @else
                                            <span class="text--danger">@lang('No')</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <img src="{{ asset('assets/images/no-data.gif') }}" class="empty-message">
                            <span class="d-block">@lang('You not have any active subscription')</span>
                            <span class="d-block fs-13 text-muted">@lang('There are no available data to display on this table at the moment.')</span>
                            <a class="btn btn--base btn-shadow mt-3 "
                                href="{{ route('user.subscription.index') }}?tab=pricing-plans">
                                <i class="fa-solid fa-paper-plane"></i> @lang('Purchase Now')
                            </a>
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade" id="pills-plans" role="tabpanel">
                    <div class="pricing-card-top">
                        <p class="pricing-card-top__text">@lang('Monthly')</p>
                        <div class="form--switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="recurring_type" />
                        </div>
                        <p class="pricing-card-top__text">
                            @lang('Yearly')</span>
                        </p>
                    </div>
                    <div class="row gy-4 justify-content-center align-items-center">
                        @include('Template::partials.pricing', ['cardTwo' => 'card-two'])
                    </div>
                </div>
                <div class="tab-pane fade" id="purchase-history" role="tabpanel">
                    <div class="dashboard-table">
                        <div class="body-top">
                            <div class="body-top__left">
                                <form class="search-form">
                                    <input type="search" class="form--control" name="search"
                                        value="{{ request()->search }}" placeholder="Search with plan name..."
                                        autocomplete="off">
                                    <span class="search-form__icon"> <i class="fa-solid fa-magnifying-glass"></i>
                                    </span>
                                </form>
                            </div>
                            <div class="body-top__right">
                                <span class="text"> @lang('Filter by') : </span>
                                <form class="select-group filter-form">
                                    <select class="form-select form--control select2" data-minimum-results-for-search="-1"
                                        name="payment_method">
                                        <option value="">@lang('Payment Method')</option>
                                        <option value="{{ Status::WALLET_PAYMENT }}" @selected(request()->status === Status::WALLET_PAYMENT)>
                                            @lang('Wallet Payment')
                                        </option>
                                        <option value="{{ Status::GATEWAY_PAYMENT }}" @selected(request()->status === Status::GATEWAY_PAYMENT)>
                                            @lang('Gateway Payment')
                                        </option>
                                    </select>
                                </form>
                            </div>
                        </div>
                        <table class="table table--responsive--lg">
                            <thead>
                                <tr>
                                    <th>@lang('Plan Name')</th>
                                    <th>@lang('Purchase Price')</th>
                                    <th>@lang('Discount Coupon')</th>
                                    <th>@lang('Purchase Date')</th>
                                    <th>@lang('Expiration Date')</th>
                                    <th>@lang('Payment Method')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse (@$subscriptions as $subscription)
                                    <tr>
                                        <td>{{ __(@$subscription->plan->name) }}</td>
                                        <td>{{ showAmount(@$subscription->amount) }}</td>
                                        <td>
                                            @if (@$subscription->coupon)
                                                <span class="copy-coupon ms-1" title="Copy">
                                                    <span class="coupon-code">{{ $subscription->coupon->code }}</span>
                                                    <i class="las la-copy"></i>
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ showDateTime(@$subscription->created_at) }}</td>
                                        <td>{{ showDateTime(@$subscription->expired_at) }}</td>
                                        <td>{{ __(@$subscription->get_payment_method) }}</td>
                                        <td>
                                            <a href="{{ route('user.subscription.invoice', $subscription->id) }}"
                                                class="btn btn--base btn-shadow"><i class="las la-eye"></i>
                                                @lang('View Invoice')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    @include('Template::partials.empty_message')
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ paginateLinks($subscriptions) }}
                </div>
            </div>
        </div>
    </div>
    <x-purchase_modal />
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('topbar_tabs')
    @include('Template::partials.profile_tab')
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {

            $('.filter-form').find('select').on('change', function() {
                $('.filter-form').submit();
            });

            // Auto renewal
            $('.autoRenewal').on('change', function() {
                let route = "{{ route('user.subscription.auto.renewal') }}";
                $.get(route).done(function(response) {
                    notify(response.status, response.message);
                });
            });

            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get("tab");

            if (tabParam) {
                const $tab = $(`[data-tab-id="${tabParam}"]`);
                if ($tab.length) {
                    const tab = new bootstrap.Tab($tab[0]);
                    tab.show();
                }
            }

            $('[data-tab-id]').on('click', function(e) {
                const tabId = $(e.target).data('tab-id');
                const url = new URL(window.location);
                url.searchParams.set('tab', tabId);
                history.replaceState(null, '', url);
            });

            $(document).on('click', '.copy-coupon', function() {
                let code = $(this).find('.coupon-code').text().trim();

                navigator.clipboard.writeText(code).then(function() {
                    notify('success', "@lang('Coupon code copied!')");
                }).catch(function() {
                    notify('error', "@lang('Failed to copy coupon code.')");
                });
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .plan-details__item {
            border-bottom: 1px solid hsl(var(--black)/.05);
            padding-bottom: 7px;
            margin-bottom: 7px;
            color: hsl(var(--black));
            font-size: 15px;
        }

        .plan-details__item .item-title {
            color: hsl(var(--black));
            font-weight: 400;
            font-size: 15px;
        }

        .active-plan-title {
            color: hsl(var(--body-color)) !important;
        }

        .copy-coupon {
            cursor: pointer !important;
        }
    </style>
@endpush
