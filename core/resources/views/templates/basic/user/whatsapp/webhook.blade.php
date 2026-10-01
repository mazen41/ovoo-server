@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">
                    @lang('On this page you’ll able to get the webhook URL for receiving response form WhatsApp. Make sure you configure the webhook URL to your')
                    <a target="_blank" href="https://developers.facebook.com/apps/">
                       <i class="la la-external-link"></i>@lang('Meta Dashboard')
                    </a>
                </p>
            </div>
        </div>
        <div class="dashboard-container__body">
            <form id="whatsapp-meta-form">
                <div class="row gy-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">@lang('Webhook URL')</label>
                            <div class="input-group">
                                <input type="text" value="{{ route('webhook') }}"
                                    class="form-control form--control form-two" readonly>
                                <button type="button" class="input-group-text copyText"> <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">@lang('Verify token') <span data-bs-toggle="tooltip"
                                    data-bs-title="@lang('If the token field is empty, you can enter any string value to verify.')"><i
                                        class="fas fa-info-circle text-info"></i></span></label>
                            <div class="input-group">
                                <input type="text" value="{{ gs('webhook_verify_token') }}"
                                    class="form-control form--control form-two" readonly>
                                <button type="button" class="input-group-text copyText"> <i class="fas fa-copy"></i>
                                </button>
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

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.copyText').on('click', function() {
                var copyText = $(this).closest('.input-group').find('input');
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
