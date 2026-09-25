@extends('admin.layouts.app')
@section('panel')
    <form action="{{ route('admin.gateway.automatic.update', $gateway->code) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row gy-4 mb-3">
            <div class="col-12">
                <x-admin.ui.card>
                    <x-admin.ui.card.body>
                        <div class="row justify-content-between gy-4">
                            <div class="col-xxl-4 col-xl-6">
                                <div class="payment-method-item">
                                    <div class="payment-method-item__left">
                                        <x-image-uploader
                                            imagePath="{{ getImage(getFilePath('gateway') . '/' . $gateway->image, getFileSize('gateway')) }}"
                                            type="gateway" :required=false />
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-8 col-xl-6">
                                <div class=" d-flex justify-content-between flex-wrap gap-3">
                                    <p class="payment-method-body-title fs-20 text--secondary">
                                        @lang('Global setting for')
                                        <strong>{{ __($gateway->name) }}</strong>
                                    </p>
                                    @if (count($supportedCurrencies) > 0)
                                        <div class="currency-add currency-add-option-wrapper">
                                            <div class="input-group">
                                                <select class="form-control form-control-sm form-select addCurrency">
                                                    <option value="">@lang('Select currency')</option>
                                                    @foreach ($supportedCurrencies as $currency => $symbol)
                                                        <option value="{{ $currency }}">
                                                            {{ __($currency) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="input-group-text add-currency-btn cursor-pointer">
                                                    @lang('Add Currency')
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    @if ($gateway->code < 1000 && $gateway->extra)
                                        @foreach ($gateway->extra as $key => $param)
                                            <div class="col-12 form-group">
                                                <label for="form-label">{{ __(@$param->title) }}</label>
                                                <div class="input-group input--group">
                                                    <input type="text" class="form-control"
                                                        value="{{ route($param->value) }}" readonly>
                                                    <span class="input-group-text cursor-pointer copyBtn"
                                                        data-copy="{{ route($param->value) }}">
                                                        <i class="fas fa-copy me-1"></i>@lang('Copy')</span>
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    @foreach ($parameters->where('global', true) as $key => $param)
                                        <div class="form-group col-12">
                                            <label>{{ __(@$param->title) }}</label>
                                            <input type="text" class="form-control" name="{{ $key }}"
                                                value="{{ @$param->value }}" required>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </x-admin.ui.card.body>
                </x-admin.ui.card>
            </div>
        </div>
        <div class="mb-3 row currency-list gy-4">
            @forelse ($gateway->currencies as $k => $gatewayCurrency)
                <div class="col-12 single-currency">
                    <x-admin.ui.card>
                        <x-admin.ui.card.body>
                            <div class="row gy-4 justify-content-center">
                                <div class="col-12">
                                    <div class="payment-method-header">
                                        <div class="content">
                                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                                <div>
                                                    <h5 class="mb-2">
                                                        {{ __($gateway->name) }} -
                                                        {{ __($gatewayCurrency->currency) }}
                                                    </h5>
                                                    <div class="form-group payment-method-title-input mb-0">
                                                        <input type="text" class="form-control"
                                                            name="currency[{{ $k }}][name]"
                                                            value="{{ $gatewayCurrency->name }}" required>
                                                    </div>
                                                </div>
                                                <div class="remove-btn">
                                                    <button type="button" class="btn btn--danger confirmationBtn"
                                                        data-question="@lang('Are you sure to delete this gateway currency?')"
                                                        data-action="{{ route('admin.gateway.automatic.remove', $gatewayCurrency->id) }}">
                                                        <i class="la la-trash-o"></i> @lang('Remove')
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-sm-6">
                                    <div class="card border border--gray h-100">
                                        <h5 class="card-header bg--gray fs-20">@lang('Range')</h5>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Minimum Amount')</label>
                                                <div class="input-group input--group">
                                                    <input type="number" step="any" class="form-control minAmount"
                                                        name="currency[{{ $k }}][min_amount]"
                                                        value="{{ getAmount($gatewayCurrency->min_amount) }}" required>
                                                    <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">@lang('Maximum Amount')</label>
                                                <div class="input-group input--group">
                                                    <input type="number" step="any" class="form-control maxAmount"
                                                        name="currency[{{ $k }}][max_amount]"
                                                        value="{{ getAmount($gatewayCurrency->max_amount) }}" required>
                                                    <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                                </div>
                                                <span class="max-amount-error-message text--danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-sm-6">
                                    <div class="card border border--gray h-100">
                                        <h5 class="card-header bg--gray fs-20">@lang('Charge')</h5>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Fixed Charge')</label>
                                                <div class="input-group input--group">
                                                    <input type="number" step="any" class="form-control"
                                                        name="currency[{{ $k }}][fixed_charge]"
                                                        value="{{ getAmount($gatewayCurrency->fixed_charge) }}" required>
                                                    <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">@lang('Percent Charge')</label>
                                                <div class="input-group input--group">
                                                    <input type="number" step="any" class="form-control"
                                                        name="currency[{{ $k }}][percent_charge]"
                                                        value="{{ getAmount($gatewayCurrency->percent_charge) }}" required>
                                                    <div class="input-group-text">%</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-sm-12">
                                    <div class="card border border--gray h-100">
                                        <h5 class="card-header bg--gray fs-20">@lang('Currency')</h5>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('Currency')</label>
                                                        <input type="text"
                                                            name="currency[{{ $k }}][currency]"
                                                            class="form-control "
                                                            value="{{ $gatewayCurrency->currency }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('Symbol')</label>
                                                        <input type="text"
                                                            name="currency[{{ $k }}][symbol]"
                                                            class="form-control symbol"
                                                            value="{{ $gatewayCurrency->symbol }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">@lang('Rate')</label>
                                                <div class="input-group input--group">
                                                    <div class="input-group-text">1 {{ __(gs('cur_text')) }} =</div>
                                                    <input type="number" step="any" class="form-control"
                                                        name="currency[{{ $k }}][rate]"
                                                        value="{{ getAmount($gatewayCurrency->rate, $gateway->crypto ? 8 : 2) }}"
                                                        required>
                                                    <div class="input-group-text">
                                                        <span class="currency_symbol">
                                                            {{ __($gatewayCurrency->baseSymbol()) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($parameters->where('global', false)->count() != 0)
                                    @php
                                        $globalParameters = json_decode($gatewayCurrency->gateway_parameter);
                                    @endphp
                                    <div class="col-lg-12">
                                        <div class="card border border--gray h-100">
                                            <h5 class="card-header bg--gray fs-20">@lang('Configuration')</h5>
                                            <div class="card-body">
                                                @foreach ($parameters->where('global', false) as $key => $param)
                                                    <div class="form-group">
                                                        <label>{{ __($param->title) }}</label>
                                                        <input type="text" class="form-control"
                                                            name="currency[{{ $k }}][{{ $key }}]"
                                                            value="{{ $globalParameters->$key }}" required>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </x-admin.ui.card.body>
                    </x-admin.ui.card>
                </div>
            @empty
                <div class="col-12 empty-message-wrapper">
                    <x-admin.ui.card>
                        <x-admin.ui.card.body>
                            <div class="p-5 text-center">
                                <img src="{{ asset('assets/images/empty_box.png') }}" class="empty-message">
                                <span class="d-block fs-13">@lang('No currency is added to this payment gateway')</span>
                            </div>
                        </x-admin.ui.card.body>
                    </x-admin.ui.card>
                </div>
            @endforelse
        </div>
        <div class="submit-btn-wrapper @if (!$gateway->currencies->count()) d-none @endif">
            <x-admin.ui.btn.submit />
        </div>
    </form>
    <x-confirmation-modal />
@endsection


@push('breadcrumb-plugins')
    <x-back_btn route="{{ route('admin.gateway.automatic.index') }}" />
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.add-currency-btn').on('click', function(e) {

                const currency = $(".addCurrency").val();
                
                if (!currency) {
                    notify("error", "@lang('Please select a currency')");
                    return;
                }

                $(".empty-message-wrapper").remove();
                $(".submit-btn-wrapper").removeClass('d-none');
                const length = $(".currency-list").find(".single-currency").length;
                $(".currency-list").append(`
                <div class="col-12 single-currency">
                    <x-admin.ui.card>
                        <x-admin.ui.card.body>
                            <div class="row gy-4 justify-content-center">
                                <div class="col-12">
                                    <div class="payment-method-header">
                                        <div class="content">
                                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                                <div>
                                                    <h5 class="mb-2">
                                                        {{ __($gateway->name) }} - ${currency}
                                                    </h5>
                                                    <div class="form-group payment-method-title-input">
                                                        <input type="text" class="form-control"
                                                            name="currency[${length}][name]" required>
                                                    </div>
                                                </div>
                                                <div class="remove-btn">
                                                    <button type="button" class="btn btn--danger removeBtn"
                                                        <i class="la la-trash-o"></i> @lang('Global setting for')
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-sm-6">
                                    <div class="card border border--gray h-100">
                                        <h5 class="card-header bg--gray fs-20">@lang('Select currency')</h5>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Add Currency')</label>
                                                <div class="input-group input--group">
                                                    <input type="number" step="any" class="form-control minAmount"
                                                        name="currency[${length}][min_amount]" required>
                                                    <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">@lang('Copy')</label>
                                                <div class="input-group input--group">
                                                    <input type="number" step="any" class="form-control maxAmount"
                                                        name="currency[${length}][max_amount]"  required>
                                                    <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                                </div>
                                                <span class="max-amount-error-message text--danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-sm-6">
                                    <div class="card border border--gray h-100">
                                        <h5 class="card-header bg--gray fs-20">@lang('Are you sure to delete this gateway currency?')</h5>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Remove')</label>
                                                <div class="input-group input--group">
                                                    <input type="number" step="any" class="form-control"
                                                        name="currency[${length}][fixed_charge]"  required>
                                                    <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">@lang('Range')</label>
                                                <div class="input-group input--group">
                                                    <input type="number" step="any" class="form-control"
                                                        name="currency[${length}][percent_charge]"  required>
                                                    <div class="input-group-text">%</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-sm-6">
                                    <div class="card border border--gray h-100">
                                        <h5 class="card-header bg--gray fs-20">@lang('Minimum Amount')</h5>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('Maximum Amount')</label>
                                                        <input type="text"
                                                            name="currency[${length}][currency]"
                                                            class="form-control " readonly value=${currency}>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('Charge')</label>
                                                        <input type="text" name="currency[${length}][symbol]"
                                                            class="form-control symbol"  required value="${currency}">
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">@lang('Fixed Charge')</label>
                                                <div class="input-group input--group">
                                                    <div class="input-group-text">1 {{ __(gs('cur_text')) }} =</div>
                                                    <input type="number" step="any" class="form-control"
                                                        name="currency[${length}][rate]" required>
                                                    <div class="input-group-text">
                                                        <span class="currency_symbol">${currency}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($parameters->where('global', false)->count() != 0)
                                    @php
                                        $globalParameters = json_decode($gatewayCurrency->gateway_parameter);
                                    @endphp
                                    <div class="col-lg-12">
                                        <div class="card border border--gray h-100">
                                            <h5 class="card-header bg--gray fs-20">@lang('Percent Charge')</h5>
                                            <div class="card-body">
                                                @foreach ($parameters->where('global', false) as $key => $param)
                                                    <div class="form-group">
                                                        <label>{{ __($param->title) }}</label>
                                                        <input type="text" class="form-control"
                                                            name="currency[${length}][{{ $key }}]"  required>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </x-admin.ui.card.body>
                    </x-admin.ui.card>
                </div>
                `);

                currerncyAdOptionCheck();
                $('html, body').animate({
                    scrollTop: $(document).height()
                }, 1000);

                currerncyAdOptionCheck();
            });

            $(".currency-list").on(`input`, ".symbol", function(e) {
                const currency = $(this).val()
                $(this).closest(".single-currency").find(".currency_symbol").html(currency || '&nbsp;');
            });

            function currerncyAdOptionCheck() {
                const totalSupportCurrency = Number("{{ collect($gateway->supported_currencies)->count() }}");
                const totalAddCurrency = $(".currency-list").find('.single-currency').length;
                if (totalSupportCurrency == totalAddCurrency) {
                    $(".currency-add-option-wrapper").addClass('d-none');
                } else {
                    $(".currency-add-option-wrapper").removeClass('d-none');
                }
            }

            $(".currency-list").on('click', '.removeBtn', function(e) {
                $(this).closest('.single-currency').remove();
                currerncyAdOptionCheck();
            });
        })(jQuery);
    </script>
@endpush