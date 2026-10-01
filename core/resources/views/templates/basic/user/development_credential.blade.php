@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __($pageTitle ?? '') }}</h5>
                <p class="container-top__desc">
                    @lang('This page displays your API credentials (Client ID and Client Secret) which allow you to securely access our system from external applications via API. Please keep these credentials confidential and use them to authenticate your requests.')
                    <a target="_blank" href="{{ route('external.api.documentation') }}">@lang('API Documentation')</a>
                </p>
            </div>
        </div>
        <div class="dashboard-container__body">
            <form id="whatsapp-meta-form">
                <div class="row gy-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">@lang('Client ID')</label>
                            <div class="input-group">
                                <input type="password" value="{{ $userApiCredential->client_id }}"
                                    class="form-control form--control form-two" readonly>
                                <button type="button" class="input-group-text copyText"
                                    data-text="{{ $userApiCredential->client_id }}">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">@lang('Client Secret')</label>
                            <div class="input-group">
                                <input type="password" value="{{ $userApiCredential->client_secret }}"
                                    class="form-control form--control form-two" readonly>
                                <button type="button" data-text="{{ $userApiCredential->client_secret }}"
                                    class="input-group-text copyText">
                                    <i class="fas fa-copy"></i>
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



@push('script')
    <script>
        (function($) {
            "use strict";

            $('.copyText').on('click', function(e) {
                const $this = $(this);
                const text = $this.attr("data-text");
                const oldHtml = $this.html();

                const tempTextArea = document.createElement('textarea');
                tempTextArea.value = text;
                tempTextArea.style.width = 0;
                tempTextArea.style.height = 0;

                document.body.appendChild(tempTextArea);

                tempTextArea.select();
                tempTextArea.setSelectionRange(0, 99999);

                navigator.clipboard.writeText(text).then(function() {
                    $this.html(`<i class="las la-check-double fw-bold me-2"></i> Copied`);
                    setTimeout(function() {
                        $this.html(oldHtml);
                    }, 1500);
                }).catch(function(error) {
                    console.error('Copy failed!', error);
                });

                document.body.removeChild(tempTextArea);
            });

        })(jQuery);
    </script>
@endpush
