@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Quickly generate a floater widget by completing the form below.')</p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <a href="{{ route('user.floater.index') }}" class="btn btn--dark"><i class="las la-undo"></i>
                        @lang('Back')
                    </a>
                    <button type="submit" form="whatsappForm" class="btn btn--base btn-shadow">
                        <i class="lab la-telegram"></i> @lang('Generate Floater')
                    </button>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <div class="information-wrapper">
                <div class="row">
                    <div class="col-xxl-8">
                        <form action="{{ route('user.floater.generate') }}" method="POST" id="whatsappForm">
                            @csrf
                            <div class="form-group">
                                <label class="label-two">@lang('WhatsApp Number')</label>
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <select class="form--control select2 img-select2 form-two" name="dial_code"
                                            required>
                                            @foreach ($countries as $key => $country)
                                                <option value="{{ $country->dial_code }}"
                                                    data-src="{{ asset('assets/images/country/' . strtolower($key) . '.svg') }}"
                                                    @selected(old('country') == $country->dial_code)>
                                                    {{ $country->country }}(+{{ $country->dial_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="number" name="mobile" value="{{ old('mobile') }}"
                                        class="form-control form--control form-two" placeholder="@lang('Enter mobile number')"
                                        required>
                                </div>
                            </div>
                            <div class="form-group position-relative">
                                <label class="label-two">@lang('Message')</label>
                                <textarea name="message" class="form--control form-two" cols="30" rows="10" placeholder="@lang('Enter message')"
                                    autocomplete="off" required></textarea>
                            </div>
                            <div class="form-group">
                                <label class="label-two"> @lang('Floater Color')</label>
                                <div class="input-group color-input">
                                    <input type="text" class="form--control form-control form-two colorCode"
                                        name="color_code" placeholder="@lang('Enter color code')" required
                                        value="{{ old('color_code') }}">
                                    <span class="input-group-text">
                                        <input type='text' class="form--control form-two colorPicker"
                                            value="{{ old('color_code') }}" data-color="{{ old('color_code') }}" />
                                    </span>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="row g-4 mt-4 floater-widget-script d-none">
                    <div class="col-12">
                        <h4>@lang('Floater widget script & preview')</h4>
                    </div>
                    <div class="col-lg-6">
                        <div class="floaterResult">
                            <p class="fs-14 mb-3">@lang('Copy and paste the script below on your website to activate the floater.')</p>
                            <textarea class="form--control form-two floaterScript" readonly></textarea>
                            <button type="button" class="btn btn--base mt-3 copyScript">
                                <i class="las la-copy"></i> @lang('Copy Script')
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <p class="fs-14 mb-3">@lang('This is how it’ll show up for the users.')</p>
                        <div class="floaterPreview">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
    <link rel = "stylesheet" href = "{{ asset('assets/admin/css/spectrum.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/spectrum.min.js') }}"></script>
@endpush
@push('script')
    <script>
        "use strict";
        (function($) {

            $('.select2').select2();

            $('#whatsappForm').on('submit', function(e) {
                e.preventDefault();
                const $this = $(this);
                const formData = new FormData($this[0]);

                $.ajax({
                    url: $this.attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status == 'success') {
                            $('.floater-widget-script').removeClass('d-none');
                            $('.floaterScript').val(response.data.script);
                            $('.floaterPreview').append(response.data.preview);
                            $('#whatsappForm').trigger('reset');
                            $('select[name=dial_code]').trigger('change');
                            notify('success', response.message);
                        } else {
                            notify('error', response.message || "@lang('Something went to wrong')")
                        }
                    }
                });
            });


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

            $('.copyScript').on('click', function() {
                var script = $('.floaterScript').val();
                try {
                    navigator.clipboard.writeText(script);
                    notify('success', '@lang('Script copied to clipboard')');
                } catch (err) {
                    notify('error', '@lang('Failed to copy script')');
                }
            });

            $('body').on('mouseenter', ".whatsapp-button", function() {
                $('.floater-popup-whatsapp').removeClass('d-none').css('display', 'flex');
                $('.floater-whats-input').val($('textarea[name=message]').val());
            });

            $('body').on('click', "#send-btn", function() {
                const mobile = $(this).data('mobile');
                const message = $(this).data('message');
                const whatsappLink = `https://wa.me/${mobile}?text=${encodeURIComponent(message)}`;
                window.open(whatsappLink, '_blank');
            });

            $('body').on('click', ".floater-closePopup", function() {
                $('.floater-popup-whatsapp').addClass('d-none');
            });

        })(jQuery);
    </script>
@endpush
