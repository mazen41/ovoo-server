@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Quickly edit your whatsapp short link by completing the form below.')</p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <a href="{{ route('user.shortlink.index') }}" class="btn btn--dark"><i class="las la-undo"></i>
                        @lang('Back')</a>
                    <button type="submit" form="whatsappForm" class="btn btn--base btn-shadow"><i
                            class="lab la-telegram"></i> @lang('Update Short Link')</button>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <div class="information-wrapper">
                <div class="row">
                    <div class="col-xxl-8">
                        <form action="{{ route('user.shortlink.update', $shortLink->id) }}" method="POST"
                            id="whatsappForm">
                            @csrf
                            <div class="row gy-2">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Short Link')</label>
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                {{ route('home') }}/wl/
                                            </div>
                                            <input type="text" name="code" value="{{ $shortLink->code }}"
                                                class="form--control form-two form-control form-control-lg"
                                                placeholder="@lang('Enter code')" readonly>
                                        </div>
                                        <p class="exist"></p>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="label-two">@lang('WhatsApp Number')</label>
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <select class="form--control select2 form-two img-select2" name="dial_code"
                                                    required>
                                                    @foreach ($countries as $key => $country)
                                                        <option value="{{ $country->dial_code }}"
                                                            data-src="{{ asset('assets/images/country/' . strtolower($key) . '.svg') }}"
                                                            @selected($shortLink->dial_code == $country->dial_code)>
                                                            {{ $country->country }}(+{{ $country->dial_code }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input type="number" name="mobile"
                                                value="{{ old('mobile', $shortLink->mobile) }}"
                                                class="form--control form-control form-two" placeholder="@lang('Enter mobile number')"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group position-relative">
                                        <label class="label-two">@lang('Message')</label>
                                        <textarea name="message" class="form--control form-two" cols="30" rows="10" placeholder="@lang('Enter message')"
                                            autocomplete="off" required>{{ old('message', $shortLink->message) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade custom--modal" id="linkModal" tabindex="-1" aria-labelledby="linkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('YOUR WHATSAPP SHORT LINK IS READY NOW')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="icon">
                            <i class="fas fa-times"></i>
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-5">
                        <div class="text-center qrCode mb-4">
                             <div class="qrcode_image" id="qrcode-canvas">
                                <img src="" alt="">
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="input-group">
                                <input type="text" class="form-control form--control form-two qrcode-url-input" readonly>
                                <span class="input-group-text cursor-pointer copy-url"><i class="la la-copy"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {

            var qrCodeUrl = '';

            $('#whatsappForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const url = form.attr('action');
                const data = form.serialize();
                $.post(url, data, function(response) {
                    if (response.status != 'success') {
                        notify('error', response.message || "@lang('Something went to wrong')");
                    } else {
                        qrCodeUrl = response.data.qr_code_url;
                        $(".qrcode-url-input").val(qrCodeUrl);
                        $(".qrcode_image img").attr('src', response.data.qr_code_image);
                        form.trigger('reset');
                        notify('success', response.message);
                        $("#linkModal").modal('show');
                    }
                });
            });

            $('.copy-url').on('click', function() {
                try {
                    navigator.clipboard.writeText(qrCodeUrl).then(() => {
                        notify('success', 'Link copied to clipboard!');
                    });
                } catch (e) {
                    notify('error', 'Unable to copy link!');
                }

            });

       
        })(jQuery);
    </script>
@endpush


@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush


@push('style')
    <style>
        .dashboard-container .select2+.select2-container .select2-selection__rendered {
            background-color: #f8f9fa !important;
            border: 0 !important;
            box-shadow: unset !important;
        }

        .qrCode {
            border: 5px solid hsl(var(--base));
            padding: 40px;
            border-radius: 5px;
        }
    </style>
@endpush
