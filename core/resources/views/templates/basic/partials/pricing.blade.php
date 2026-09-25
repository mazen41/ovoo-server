@php
    @$pricingPlans = \App\Models\PricingPlan::active()->orderBy('monthly_price', 'asc')->get();
    @$user = auth()->user();
@endphp
@foreach ($pricingPlans ?? [] as $pricingPlan)
    <div class="col-lg-4 col-md-6 wow animationfadeUp" data-wow-delay="0.6s">
        <div
            class="pricing-card @if ($pricingPlan->is_popular) popular @endif  @isset($cardTwo) card-two @endisset">
            <div class="pricing-card__top">
                <h4 class="pricing-card__title">{{ __(@$pricingPlan->name) }}</h4>
                <p class="pricing-card__desc">{{ __(@$pricingPlan->description) }}</p>
            </div>
            <h2 class="pricing-card__number">
                <span class="monthly_price">
                    <span
                        class="currency-type">{{ gs('cur_sym') }}</span>{{ showAmount(@$pricingPlan->monthly_price, currencyFormat: false) }}
                </span>
                <span class="yearly_price d-none">
                    <span
                        class="currency-type">{{ gs('cur_sym') }}</span>{{ showAmount(@$pricingPlan->yearly_price, currencyFormat: false) }}
                </span>
            </h2>
            <ul class="pricing-list">
                <li class="pricing-list__item justify-content-between">
                    <span class="d-flex gap-2">
                        <span class="pricing-list__item-icon fs-16">
                            <i class="las la-user-friends"></i>
                        </span>
                        @lang('Whatsapp Account Limit')
                    </span>
                    <span>{{ printLimit($pricingPlan->account_limit) }}</span>
                </li>
                <li class="pricing-list__item justify-content-between">
                    <span class="d-flex gap-2">
                        <span class="pricing-list__item-icon fs-16">
                            <i class="las la-user-tie"></i>
                        </span>
                        @lang('Agent Limit')
                    </span>
                    <span>{{ printLimit($pricingPlan->agent_limit) }}</span>
                </li>

                <li class="pricing-list__item justify-content-between">
                    <span class="d-flex gap-2">
                        <span class="pricing-list__item-icon fs-16">
                            <i class="las la-address-book"></i>
                        </span>
                        @lang('Contact Limit')
                    </span>
                    <span>{{ printLimit($pricingPlan->contact_limit) }}</span>
                </li>

                <li class="pricing-list__item justify-content-between">
                    <span class="d-flex gap-2">
                        <span class="pricing-list__item-icon fs-16">
                            <i class="las la-copy"></i>
                        </span>
                        @lang('Template Limit')
                    </span>
                    <span>{{ printLimit($pricingPlan->template_limit) }}</span>
                </li>

                <li class="pricing-list__item justify-content-between">
                    <span class="d-flex gap-2">
                        <span class="pricing-list__item-icon fs-16">
                            <i class="lab la-rocketchat"></i>
                        </span>
                        @lang('Automation Flow Limit')
                    </span>
                    <span>{{ printLimit($pricingPlan->flow_limit) }}</span>
                </li>

                <li class="pricing-list__item justify-content-between">
                    <span class="d-flex gap-2">
                        <span class="pricing-list__item-icon fs-16">
                            <i class="las la-bullhorn"></i>
                        </span>
                        @lang('Campaign Limit')
                    </span>
                    <span>{{ printLimit($pricingPlan->campaign_limit) }}</span>
                </li>

                <li class="pricing-list__item justify-content-between">
                    <span class="d-flex gap-2">
                        <span class="pricing-list__item-icon fs-16">
                            <i class="las la-link"></i>
                        </span>
                        @lang('ShortLink Limit')
                    </span>
                    <span>{{ printLimit($pricingPlan->short_link_limit) }}</span>
                </li>

                <li class="pricing-list__item justify-content-between">
                    <span class="d-flex gap-2">
                        <span class="pricing-list__item-icon fs-16">
                            <i class="las la-paper-plane"></i>
                        </span>
                        @lang('Floater Limit')
                    </span>
                    <span>{{ printLimit($pricingPlan->floater_limit) }}</span>
                </li>

                <li class="pricing-list__item justify-content-between">
                    <span class="d-flex gap-2">
                        <span class="pricing-list__item-icon fs-16">
                            <i class="las la-smile"></i>
                        </span>
                        @lang('Welcome Message Available')
                    </span>
                    @if ($pricingPlan->welcome_message)
                        <span class="text--success">@lang('Yes')</span>
                    @else
                        <span class="text--danger">@lang('No')</span>
                    @endif
                </li>
                <li class="pricing-list__item justify-content-between">
                    <span class="d-flex gap-2">
                        <span class="pricing-list__item-icon fs-16">
                            <i class="las la-robot"></i>
                        </span>
                        @lang('AI Assistance')
                    </span>
                    @if ($pricingPlan->ai_assistance)
                        <span class="text--success">@lang('Yes')</span>
                    @else
                        <span class="text--danger">@lang('No')</span>
                    @endif
                </li>
                <li class="pricing-list__item justify-content-between">
                    <span class="d-flex gap-2">
                        <span class="pricing-list__item-icon fs-16">
                            <i class="las la-link"></i>
                        </span>
                        @lang('Interactive Message')
                    </span>
                    @if ($pricingPlan->interactive_message)
                        <span class="text--success">@lang('Yes')</span>
                    @else
                        <span class="text--danger">@lang('No')</span>
                    @endif
                </li>
                <li class="pricing-list__item justify-content-between">
                    <span class="d-flex gap-2">
                        <span class="pricing-list__item-icon fs-16">
                            <i class="las la-shopping-cart"></i>
                        </span>
                        @lang('Ecommerce Available')
                    </span>
                    @if ($pricingPlan->ecommerce_available)
                        <span class="text--success">@lang('Yes')</span>
                    @else
                        <span class="text--danger">@lang('No')</span>
                    @endif
                </li>
                <li class="pricing-list__item justify-content-between">
                    <span class="d-flex gap-2">
                        <span class="pricing-list__item-icon fs-16">
                            <i class="las la-code"></i>
                        </span>
                        @lang('API Available')
                    </span>
                    @if ($pricingPlan->api_available)
                        <span class="text--success">@lang('Yes')</span>
                    @else
                        <span class="text--danger">@lang('No')</span>
                    @endif
                </li>
            </ul>
            <div class="pricing-card__btn">
                @auth
                    <button class="btn btn--base w-100 purchaseBtn" data-plan='@json($pricingPlan)'>
                        @if (@$user->plan_id == $pricingPlan->id)
                            @lang('Renew Now')
                        @else
                            @lang('Buy Now')
                        @endif
                    </button>
                @else
                    <a href="{{ route('user.login') }}" class="btn btn--base w-100"> @lang('Buy Now') </a>
                @endauth
            </div>
        </div>
    </div>
@endforeach
