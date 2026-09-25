@extends('admin.layouts.app')
@section('panel')
    <form method="POST" enctype="multipart/form-data">
        <x-admin.ui.card>
            <x-admin.ui.card.body>
                @csrf
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label> @lang('Site Title')</label>
                            <input class="form-control" type="text" name="site_name" required value="{{ gs('site_name') }}">
                        </div>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="required"> @lang('Timezone')</label>
                        <select class="  form-control select2" name="timezone">
                            @foreach ($timezones as $key => $timezone)
                                <option value="{{ @$key }}" @selected(@$key == $currentTimezone)>
                                    {{ __($timezone) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="required"> @lang('Date Format')</label>
                        <select class="form-control select2" name="date_format" data-minimum-results-for-search="-1">
                            @foreach (supportedDateFormats() as $dateFormat)
                                <option value="{{ @$dateFormat }}" @selected(gs('date_format') == $dateFormat)>
                                    {{ $dateFormat }} ({{ date($dateFormat) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="required"> @lang('Time Format')</label>
                        <select class="form-control select2" name="time_format" data-minimum-results-for-search="-1">
                            @foreach (supportedTimeFormats() as $key => $timeFormat)
                                <option value="{{ @$timeFormat }}" @selected(gs('time_format') == $timeFormat)>
                                    {{ __($timeFormat) }} ({{ date($timeFormat) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-sm-4">
                        <label class="required"> @lang('Site Primary Color')</label>
                        <div class="input-group color-input">
                            <input type="text" class="form-control colorCode" name="base_color"
                                value="{{ gs('base_color') }}">
                            <span class="input-group-text">
                                <input type='text' class="form-control colorPicker" value="{{ gs('base_color') }}">
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>@lang('Currency')</label>
                            <input class="form-control" type="text" name="cur_text" required
                                value="{{ gs('cur_text') }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>@lang('Currency Symbol')</label>
                            <input class="form-control" type="text" name="cur_sym" required value="{{ gs('cur_sym') }}">
                        </div>
                    </div>
                    <div class="form-group col-sm-4 ">
                        <label class="required"> @lang('Currency Showing Format')</label>
                        <select class="select2 form-control" name="currency_format" data-minimum-results-for-search="-1">
                            <option value="1" @selected(gs('currency_format') == Status::CUR_BOTH)>
                                @lang('Show Currency Text and Symbol Both')({{ gs('cur_sym') }}{{ showAmount(100, currencyFormat: false) }}
                                {{ __(gs('cur_text')) }})
                            </option>
                            <option value="2" @selected(gs('currency_format') == Status::CUR_TEXT)>
                                @lang('Show Currency Text Only')({{ showAmount(100, currencyFormat: false) }} {{ __(gs('cur_text')) }})
                            </option>
                            <option value="3" @selected(gs('currency_format') == Status::CUR_SYM)>
                                @lang('Show Currency Symbol Only')({{ gs('cur_sym') }}{{ showAmount(100, currencyFormat: false) }})
                            </option>
                        </select>
                    </div>

                    <div class="form-group col-sm-4 ">
                        <label class="required"> @lang('Allow Precision')</label>
                        <select class="select2 form-control" name="allow_precision" data-minimum-results-for-search="-1">
                            @foreach (range(1, 8) as $digit)
                                <option value="{{ $digit }}" @selected(gs('allow_precision') == $digit)>
                                    {{ $digit }}
                                    @lang('Digit')({{ showAmount(100, currencyFormat: false, decimal: $digit) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-4 ">
                        <label class="required"> @lang('Thousand Separator')</label>
                        <select class="select2 form-control" name="thousand_separator" data-minimum-results-for-search="-1">
                            @foreach (supportedThousandSeparator() as $k => $supportedThousandSeparator)
                                <option value="{{ $k }}" @selected(gs('thousand_separator') == $k)>
                                    {{ __($supportedThousandSeparator) }}
                                    @if ($k == 'space')
                                        ({{ showAmount(1000, currencyFormat: false, separator: ' ') }})
                                    @elseif($k == 'none')
                                        (@lang('10000'))
                                    @else
                                        ({{ showAmount(1000, currencyFormat: false, separator: $k) }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-4">
                        <label> @lang('Record to Display Per Page')</label>
                        <select class="select2 form-control" name="paginate_number" data-minimum-results-for-search="-1">
                            <option value="20" @selected(gs('paginate_number') == 20)>@lang('20 items')</option>
                            <option value="50" @selected(gs('paginate_number') == 50)>@lang('50 items')</option>
                            <option value="100" @selected(gs('paginate_number') == 100)>@lang('100 items')</option>
                        </select>
                    </div>

                    <div class="form-group col-sm-4">
                        <label>@lang('Subscription Notify Before')</label>
                        <div class="input-group input--group">
                            <input type="number" name="subscription_notify_before" class="form-control"
                                value="{{ gs('subscription_notify_before') }}" min="1" max="7" required>
                            <span class="input-group-text"> @lang('Days')</span>
                        </div>
                    </div>
                    <div class="form-group col-sm-4">
                        <label>@lang('Webhook Verify Token') <span data-bs-toggle="tooltip" title="@lang('A secret token used to verify and validate that incoming webhook requests are from a trusted source.')"><i
                                    class="las la-info-circle"></i></span></label>
                        <input type="text" name="webhook_verify_token" class="form-control"
                            value="{{ gs('webhook_verify_token') }}">
                    </div>
                    <div class="form-group col-sm-4">
                        <label>@lang('Referral Percentage')</label>
                        <div class="input-group input--group">
                            <input type="number" name="referral_amount_percentage" class="form-control"
                                value="{{ getAmount(gs('referral_amount_percentage')) }}" min="0" max="100"
                                required>
                            <span class="input-group-text"> @lang('%')</span>
                        </div>
                    </div>
                    <div class="form-group col-sm-4">
                        <label>@lang('Google Maps API Key')</label>
                        <input type="text" name="google_maps_api" class="form-control" value="{{ gs('google_maps_api') }}">
                    </div>
                    <div class="col-12">
                        <div class="my-4">
                            <h6 class="divider-title">
                                @lang('EMBEDDED SIGN UP CONFIGURATION')
                                <a href="https://developers.facebook.com/apps/" class="text-primary" target="_blank"><i
                                        class="las la-external-link-alt"></i> @lang('Meta Dashboard')</a>
                            </h6>
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>
                            @lang('Meta App Id')
                            <span data-bs-toggle="tooltip" title="@lang('The meta app id from your meta dashboard.')">
                                <i class="las la-info-circle"></i>
                            </span>
                        </label>
                        <input type="text" name="meta_app_id" class="form-control" value="{{ gs('meta_app_id') }}">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>
                            @lang('Meta App Secret')
                            <span data-bs-toggle="tooltip" title="@lang('The meta app secret from your meta dashboard.')">
                                <i class="las la-info-circle"></i>
                            </span>
                        </label>
                        <input type="text" name="meta_app_secret" class="form-control"
                            value="{{ gs('meta_app_secret') }}">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>
                            @lang('Meta Configuration ID')
                            <span data-bs-toggle="tooltip" title="@lang('The meta configuration id from your meta business setting page.')">
                                <i class="las la-info-circle"></i>
                            </span>
                        </label>
                        <input type="text" name="meta_configuration_id" class="form-control"
                            value="{{ gs('meta_configuration_id') }}">
                    </div>
                    <div class="col-12">
                        <x-admin.ui.btn.submit />
                    </div>
                </div>
            </x-admin.ui.card.body>
        </x-admin.ui.card>
    </form>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel = "stylesheet" href = "{{ asset('assets/admin/css/spectrum.css') }}">
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.colorPicker').spectrum({
                color: $(this).data('color'),
                change: function(color) {
                    changeColor($(this), color.toHexString())
                }
            });

            $('.colorCode').on('input', function() {
                var clr = $(this).val();
                $(this).closest('.form-group').find('.colorPicker').spectrum({
                    color: clr,
                    change: function(color) {
                        changeColor($(this), color.toHexString());
                    }
                });
                changeColor($(this), `#${clr}`)
            });

            $.each($('.colorCode'), function(i, element) {
                const $element = $(element);
                const colorCode = `#${$element.val()}`;
                changeColor($element, colorCode);
            });

            function changeColor($this, colorCode) {
                const $parent = $this.closest('.form-group');
                $parent.find('.input-group-text').css('border-color', colorCode);
                $parent.find('.sp-replacer').css('background', colorCode);
                $parent.find('.colorCode').val(colorCode.replace(/^#?/, ''));
            }
        })(jQuery);
    </script>
@endpush
@push('style')
    <style>
        [data-theme=dark] .sp-picker-container {
            border-left: solid 1px hsl(var(--light));
            background: hsl(var(--light));
        }

        [data-theme=dark] .sp-container {
            border-color: hsl(var(--border-color)) !important;
            border: solid 1px hsl(var(--border-color));
        }

        .divider-title {
            position: relative;
            text-align: center;
            width: max-content;
            margin: 0 auto;
        }

        .divider-title::before {
            position: absolute;
            content: '';
            top: 14px;
            left: -90px;
            background: #6b6b6b65;
            height: 2px;
            width: 80px;
        }

        .divider-title::after {
            position: absolute;
            content: '';
            top: 14px;
            right: -90px;
            background: #6b6b6b65;
            height: 2px;
            width: 80px;
        }
    </style>
@endpush
