@extends('admin.layouts.app')
@section('panel')
    <form action="{{ route('admin.gateway.manual.store') }}" class="disableSubmission" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="row gy-4">
            <div class="col-lg-12">
                <x-admin.ui.card>
                    <x-admin.ui.card.body>
                        <div class="row gy-4">
                            <div class="col-xl-4 col-sm-6">
                                <x-image-uploader image="" class="w-100" type="gateway" :required=false />
                            </div>
                            <div class="col-xl-8 copl-sm-6">
                                <div class="form-group">
                                    <label>@lang('Gateway Name')</label>
                                    <input type="text" class="form-control " name="name" value="{{ old('name') }}"
                                        required />
                                </div>
                                <div class="form-group">
                                    <label>@lang('Currency')</label>
                                    <input type="text" name="currency" class="form-control border-radius-5" required
                                        value="{{ old('currency') }}">
                                </div>
                                <div class="form-group">
                                    <label>@lang('Rate')</label>
                                    <div class="input-group input--group">
                                        <div class="input-group-text">1 {{ __(gs('cur_text')) }} =</div>
                                        <input type="number" step="any" class="form-control" name="rate" required
                                            value="{{ old('rate') }}">
                                        <div class="input-group-text"><span class="currency_symbol">&nbsp;</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-admin.ui.card.body>
                </x-admin.ui.card>
            </div>
            <div class="col-sm-6">
                <div class="card border border--gray h-100">
                    <h5 class="card-header bg--gray fs-20">@lang('Range')</h5>
                    <div class="card-body">
                        <div class="form-group">
                            <label>@lang('Minimum Amount')</label>
                            <div class="input-group input--group">
                                <input type="number" step="any" class="form-control" name="min_limit" required
                                    value="{{ old('min_limit') }}">
                                <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                            </div>
                            <span class="text--danger fs-13 d-none minimum-error">@lang('The minimum amount must be greater than the fixed charge')</span>
                        </div>
                        <div class="form-group">
                            <label>@lang('Maximum Amount')</label>
                            <div class="input-group input--group">
                                <input type="number" step="any" class="form-control" name="max_limit" required
                                    value="{{ old('max_limit') }}">
                                <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                            </div>
                            <span class="text--danger fs-13 maximum-error d-none">@lang('The maximum amount must be greater than the minimum amount')</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card border border--gray h-100">
                    <h5 class="card-header bg--gray fs-20">@lang('Charge')</h5>
                    <div class="card-body">
                        <div class="form-group">
                            <label>@lang('Fixed Charge')</label>
                            <div class="input-group input--group">
                                <input type="number" step="any" class="form-control" name="fixed_charge" required
                                    value="{{ old('fixed_charge') }}">
                                <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Percent Charge')</label>
                            <div class="input-group input--group">
                                <input type="number" step="any" class="form-control" name="percent_charge" required
                                    value="{{ old('percent_charge') }}">
                                <div class="input-group-text">%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card border border--gray h-100">
                    <h5 class="card-header bg--gray fs-20">@lang('Deposit Instruction')</h5>
                    <div class="card-body">
                        <div class="form-group">
                            <textarea rows="8" class="form-control border-radius-5 editor" name="instruction">{{ old('instruction') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="submitRequired form-change-alert d-none mt-3">
                    <i class="fas fa-exclamation-triangle"></i>
                    @lang('You\'ve to click on the submit button to apply the changes')
                </div>
                <x-generated-form generateTitle="Generate User Data Form" formTitle="User Data Form"
                    formSubtitle="Effortlessly gather complete user information with our easy-to-use data form."
                    generateSubTitle="Create user data forms to capture required data from the users on deposit via this gateway." />
            </div>
            <div class="col-12">
                <x-admin.ui.btn.submit class="submit-btn" />
            </div>
        </div>
    </form>
@endsection

@push('breadcrumb-plugins')
    <x-back_btn route="{{ route('admin.gateway.automatic.index') }}" />
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/summernote-lite.min.js') }}"></script>
@endpush
@push('style-lib')
    <link href="{{ asset('assets/global/css/summernote-lite.min.css') }}" rel="stylesheet">
    
@endpush


@push('script')
    <script>
        (function($) {
            "use strict";
            $('input[name=currency]').on('input', function() {
                $('.currency_symbol').text($(this).val());
            });

            @if (old('currency'))
                $('input[name=currency]').trigger('input');
            @endif

            $("input[name=min_limit],input[name=max_limit],input[name=fixed_charge]").on('input change', function() {
                validation();
            });

            const validation = () => {
                const minLimit = Number($('input[name=min_limit]').val());
                const maxLimit = Number($('input[name=max_limit]').val());
                const fixedCharge = Number($('input[name=fixed_charge]').val());
                var minAmountValidate, maxAmountValidate = false;

                if (minLimit && (minLimit <= fixedCharge)) {
                    $(".minimum-error").removeClass('d-none');
                    minAmountValidate = false;
                } else {
                    $(".minimum-error").addClass('d-none');
                    minAmountValidate = true;
                }

                if (maxLimit <= minLimit && (minLimit && maxLimit)) {
                    maxAmountValidate = false;
                    $(".maximum-error").removeClass('d-none');
                } else {
                    $(".maximum-error").addClass('d-none');
                    maxAmountValidate = true;
                }

                if (minAmountValidate && maxAmountValidate) {
                    $(".submit-btn").removeClass('disabled').attr("disabled", false);
                } else {
                    $(".submit-btn").addClass('disabled').attr("disabled", true);
                }
            }
        })(jQuery);
    </script>
@endpush
