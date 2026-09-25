@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">
                    @lang('Add WhatsApp Business account linked to the Cloud API. Use valid credentials from the')
                    <a target="_blank" href="https://developers.facebook.com/apps/">
                        @lang('Meta Dashboard') <i class="la la-external-link"></i>
                    </a>
                </p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <a href="{{ route('user.whatsapp.account.index') }}" class="btn btn--dark"><i class="las la-undo"></i>
                        @lang('Back')</a>
                    <button type="submit" form="whatsapp-meta-form" class="btn btn--base btn-shadow">
                        <i class="lab la-telegram"></i>
                        @lang('Submit Account')
                    </button>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <form id="whatsapp-meta-form" method="POST" action="{{ route('user.whatsapp.account.store') }}">
                @csrf
                <div class="row gy-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="label-two">@lang('WhatsApp Number')</label>
                            <input type="text" class="form--control form-two" name="whatsapp_number"
                                placeholder="@lang('Enter your WhatsApp Business Account Number with country code')" required value="{{ old('whatsapp_number') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="label-two">@lang('WhatsApp Business Account ID')</label>
                            <input type="text" class="form--control form-two" name="whatsapp_business_account_id"
                                placeholder="@lang('Enter Business Account ID')" required value="{{ old('whatsapp_business_account_id') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="label-two">@lang('WhatsApp Phone Number ID')</label>
                            <input type="text" class="form--control form-two" name="phone_number_id"
                                placeholder="@lang('Enter your WhatsApp Business Account Number ID')" required value="{{ old('phone_number_id') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="label-two">@lang('Meta App ID')</label>
                            <input type="text" class="form--control form-two" name="meta_app_id"
                                placeholder="@lang('Enter your app ID')" required value="{{ old('meta_app_id') }}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="label-two">@lang('Meta Access Token')</label>
                            <input type="text" class="form--control form-two" name="meta_access_token"
                                placeholder="@lang('Enter your access token')" required value="{{ old('meta_access_token') }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="label-two mb-0">@lang('Your Webhook URL')</label>
                            <span class="d-block fs-13 mb-2">
                                <i>
                                    @lang('This is your webhook URL. Your must provide this URL in')
                                    <a target="_blank" href="https://developers.facebook.com/apps/">
                                        <i class="la la-external-link"></i> @lang('Meta Dashboard')
                                    </a>
                                    @lang('to receive messages from WhatsApp.')
                                </i>
                            </span>
                            <div class="input-group">
                                <input type="text" readonly class="form--control form-control form-two webhook-url"
                                    value="{{ route('webhook') }}">
                                <span class="input-group-text copyText cursor-pointer">
                                    <i class="fas fa-copy"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('topbar_tabs')
    @include('Template::partials.profile_tab')
@endpush

@push('style')
    <style>
        .copied::after {
            top: 0;
            height: 100%;
            position: absolute;
            content: "Copied";
            background-color: #{{ gs('base_color') }};
        }
    </style>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.copyText').on('click', function() {
                var copyText = $('.webhook-url');
                copyText = copyText[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
                copyText.blur();
                this.classList.add('copied');
                setTimeout(() => this.classList.remove('copied'), 1500);
            });
        })(jQuery);
    </script>
@endpush
