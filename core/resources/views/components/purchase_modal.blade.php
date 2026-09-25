@props(['is_dark' => false])
@php
    $user = auth()->user();
@endphp
<div class="modal fade custom--modal @if ($is_dark) dark-modal @endif" id="purchaseModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Plan Purchase Preview')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('user.purchase.plan.store') }}"
                    class="no-submit-loader purchase-form">
                    @csrf
                    <div class="plan-details mb-4 d-flex gap-1 flex-column">
                        <div>
                            <span>@lang('Plan') </span> <span class="plan_name"></span>
                        </div>
                        <div>
                            <span>@lang('Duration') </span> <span class="duration"></span>
                        </div>
                        <div>
                            <span>@lang('Price') </span> <span class="price "></span>
                        </div>
                        <div class="discount-price d-none">
                            <span>@lang('Discount Price') </span> <span class="discount-amount"></span>
                        </div>
                        <div class="user-balance d-none">
                            <span>@lang('Wallet Balance') </span> <span class="balance"></span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">@lang('Select Recurring Type')</label>
                        <select class="form--control select2" data-minimum-results-for-search="-1" name="plan_recurring"
                            required>
                            <option value="" disabled>@lang('Select Recurring Type')</option>
                            <option value="{{ Status::MONTHLY }}" selected>@lang('Monthly')</option>
                            <option value="{{ Status::YEARLY }}">@lang('Yearly')</option>
                        </select>
                    </div>

                    <div class="form-group mb-4 input-group">
                        <input type="text" class="form--control {{ request()->routeIs('user.subscription.*') ? 'form-two' : '' }}" name="coupon_code"
                            placeholder="@lang('Enter coupon if have one')">
                        <span class="input-group-text coupon-apply-button disable">
                             @lang('Apply')
                        </span>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">@lang('Payment Via')</label>
                        <input type="hidden" name="pricing_plan_id">
                        <div class="purchase-option__card gap-2 flex-wrap d-flex">
                            <div class="purchase-option-card__item  flex-fill text-center">
                                <div class="purchase-option-card__icon">
                                    <i class="las la-wallet"></i>
                                </div>
                                <input type="radio" class="method-input" name="purchase_payment_option"
                                    value="{{ Status::WALLET_PAYMENT }}" hidden>
                                <div class="purchase-option-card__content">
                                    <h5 class="purchase-option-card__title mb-0">
                                        @lang('Wallet Balance')
                                    </h5>
                                    @auth
                                        <span
                                            class="fs-12  balance text--light">{{ showAmount(auth()->user()->balance) }}</span>
                                    @endauth
                                </div>
                            </div>
                            <div class="purchase-option-card__item  flex-fill text-center">
                                <div class="purchase-option-card__icon">
                                    <i class="las la-credit-card"></i>
                                </div>
                                <input type="radio" class="method-input" name="purchase_payment_option"
                                    value="{{ Status::GATEWAY_PAYMENT }}" hidden>
                                <div class="purchase-option-card__content">
                                    <h5 class="purchase-option-card__title"> @lang('Payment Gateway') </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn--base w-100 purchaseSubmitBtn">
                        <i class="la la-telegram"></i> @lang('Pay Now')
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


@push('script')
    <script>
        "use strict";
        (function($) {
            const $purchaseModal = $('#purchaseModal');
            const $planRecurringSelect = $purchaseModal.find('select[name=plan_recurring]');
            let selectedPlan = null;
            const $recurringType = $('input[name="recurring_type"]');
            var discountAmount = null;

            const couponApplyButton = $('.coupon-apply-button');
            const couponInput = $('input[name="coupon_code"]');

            $('.purchaseBtn').on('click', function() {
                selectedPlan = $(this).data('plan');
                const activeSubscription = $(this).data('subscription');
                const isYearly = $recurringType.is(':checked');
                let planRecurring = isYearly ? '{{ Status::YEARLY }}' : '{{ Status::MONTHLY }}';

                if (activeSubscription) {
                    planRecurring = activeSubscription.recurring_type;
                }

                $planRecurringSelect.val(planRecurring).trigger('change');

                showPlanDetails(selectedPlan, planRecurring);
                $purchaseModal.modal('show');
            });

            $planRecurringSelect.on('change', function() {
                discountAmount = null;
                if (selectedPlan) {
                    showPlanDetails(selectedPlan, $(this).val());
                    couponInput.val('');
                    couponApplyButton.removeClass('remove-coupon remove').html(`<i class="lab la-telegram-plane"></i> @lang('Apply')`);
                    $purchaseModal.find('.discount-price').addClass('d-none');
                    $purchaseModal.find('.discount-amount').text('');
                }
                checkCouponBtn();
            });

            function showPlanDetails(plan, planRecurring) {
                $purchaseModal.find("input[name='pricing_plan_id']").val(plan.id);
                $purchaseModal.find('.plan_name').text(plan.name);

                $purchaseModal.find('.price').text(getPurchasePrice(plan, planRecurring) + " {{ gs('cur_text') }}");
                $purchaseModal.find('.duration').text(planRecurring == '{{ Status::MONTHLY }}' ?
                    '@lang('Monthly')' :
                    '@lang('Yearly')');
            }

            $('.purchase-option-card__item').on('click', function() {
                let methodInput = $(this).find('.method-input');
                methodInput.prop('checked', true);

                let selectedMethod = methodInput.val();
                if (selectedMethod == '{{ Status::WALLET_PAYMENT }}') {
                    $('.user-balance').removeClass('d-none');
                    $('.balance').text("{{ showAmount($user->balance ?? 0) }}");
                } else {
                    $('.user-balance').addClass('d-none');
                }
            });

            $('.purchase-form').on('submit', function(e) {
                let selectedMethod = $('input[name="purchase_payment_option"]:checked').val();
                if (!selectedMethod) {
                    e.preventDefault();
                    notify('error', "@lang('Please select a payment method.')");
                }
                if (selectedMethod == '{{ Status::WALLET_PAYMENT }}') {
                    let userBalance = parseFloat('{{ $user->balance ?? 0 }}');
                    let requiredBalance = getPurchasePrice(selectedPlan, $planRecurringSelect.val());

                    if (userBalance < requiredBalance) {
                        e.preventDefault();
                        notify('error', "@lang('Insufficient balance.')");
                    }
                }
            });

            $('input[name="recurring_type"]').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.monthly_price').addClass('d-none');
                    $('.yearly_price').removeClass('d-none');
                } else {
                    $('.yearly_price').addClass('d-none');
                    $('.monthly_price').removeClass('d-none');
                }
            });

            couponInput.on('input', function() {
                checkCouponBtn();
            });

            function checkCouponBtn()
            {
                let coupon = couponInput.val();
                if (coupon) {
                    couponApplyButton.removeClass('disable');
                } else {
                    couponApplyButton.addClass('disable');
                }
            }

            couponApplyButton.on('click', function() {

                if ($(this).hasClass('remove-coupon')) {
                    $purchaseModal.find('.discount-price').addClass('d-none');
                    $purchaseModal.find('.discount-amount').text('');
                    $purchaseModal.find('.price').html(
                        getPurchasePrice(selectedPlan, $planRecurringSelect.val(), false) +
                        " {{ gs('cur_text') }}"
                    );

                    $(this).removeClass('remove-coupon remove').text("@lang('Apply')");
                    couponInput.val('');
                    return;
                }

                let coupon = couponInput.val();
                if (!coupon) {
                    notify('error', "@lang('Please enter a coupon code to apply.')");
                    return;
                }

                couponApplyButton.addClass('disable');

                let data = {
                    'coupon': coupon,
                    'plan_id': selectedPlan.id,
                    'recurring_type': $planRecurringSelect.val(),
                    '_token': "{{ csrf_token() }}"
                };
                let route = "{{ route('user.purchase.plan.check.coupon') }}";

                $.post(route, data)
                    .done(function(data) {
                        if (!data.data.success) {
                            notify('error', data.message);
                        } else {
                            discountAmount = data.data.discount;
                            $purchaseModal.find('.price').html(
                                `<del class="text-danger">${getPurchasePrice(selectedPlan, $planRecurringSelect.val(),false) + " {{ gs('cur_text') }}"}</del>`
                            )
                            $purchaseModal.find('.discount-price').removeClass('d-none');
                            $purchaseModal.find('.discount-amount').text(parseFloat(data.data.after_discount)
                                .toFixed(2) + " {{ gs('cur_text') }}");

                            notify('success', data.message);
                            couponApplyButton.addClass('remove-coupon').addClass('remove').html(`<i class="la la-times"></i> @lang('Remove')`);
                        }
                    })
                    .fail(function() {
                        notify('error', "@lang('Something went wrong. Please try again.')");
                    })
                    .always(function() {
                        if (couponInput.val()) {
                            couponApplyButton.removeClass('disable');
                        }
                    });
            });

            function getPurchasePrice(plan, recurringType, allowDiscount = true) {
                if (!plan) return;
                let price = 0;
                if (recurringType == '{{ Status::YEARLY }}') {
                    price = parseFloat(plan.yearly_price).toFixed(2);
                } else {
                    price = parseFloat(plan.monthly_price).toFixed(2);
                }
                if (discountAmount && allowDiscount) {
                    price = parseFloat(price - discountAmount).toFixed(2);
                }
                return price;
            }

        })
        (jQuery);
    </script>
@endpush
