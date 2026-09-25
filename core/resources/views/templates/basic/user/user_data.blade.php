@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="py-100 banner-bg section-padding-top">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-5">
                    <div class="card custom--card">
                        <div class="card-header">
                            <h5 class="card-title">{{ __($pageTitle) }}</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('user.data.submit') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Username')</label>
                                            <input type="text" class="form-control form--control checkUser"
                                                name="username" value="{{ old('username') }}" required>
                                            <span class="username-exists-error d-none"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group select2-dark">
                                            <label class="form-label">@lang('Country')</label>
                                            <select name="country" class="form-control form--control select2 img-select2"
                                                required>
                                                @foreach ($countries as $key => $country)
                                                    <option data-mobile_code="{{ $country->dial_code }}"
                                                        value="{{ $country->country }}" data-code="{{ $key }}"
                                                        data-src="{{ asset('assets/images/country/' . strtolower($key) . '.svg') }}">
                                                        <img src="{{ getImage('assets/images/flags/' . $key . '.png') }}"
                                                            alt="img">
                                                        {{ __($country->country) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Mobile')</label>
                                            <div class="input-group ">
                                                <span class="input-group-text mobile-code">

                                                </span>
                                                <input type="hidden" name="mobile_code">
                                                <input type="hidden" name="country_code">
                                                <input type="number" name="mobile" value="{{ old('mobile') }}"
                                                    class="form-control form--control checkUser" required>
                                            </div>
                                            <span class="mobile-exists-error d-none"></span>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="form-label">@lang('City')</label>
                                        <input type="text" class="form-control form--control" name="city"
                                            value="{{ old('city') }}">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="form-label">@lang('State')</label>
                                        <input type="text" class="form-control form--control" name="state"
                                            value="{{ old('state') }}">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="form-label">@lang('Zip Code')</label>
                                        <input type="text" class="form-control form--control" name="zip"
                                            value="{{ old('zip') }}">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="form-label">@lang('Address')</label>
                                        <input type="text" class="form-control form--control" name="address"
                                            value="{{ old('address') }}">
                                    </div>  
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100">
                                        @lang('Submit')
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {

            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif

            $('.select2').select2();

            $('select[name=country]').on('change', function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
                var value = $('[name=mobile]').val();
                var name = 'mobile';
                checkUser(value, name);
            });

            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));


            $('.checkUser').on('focusout', function(e) {
                var value = $(this).val();
                var name = $(this).attr('name')
                checkUser(value, name);
            });

            function checkUser(value, name) {
                var url = '{{ route('user.checkUser') }}';
                var token = '{{ csrf_token() }}';

                if (name == 'mobile') {
                    var mobile = `${value}`;
                    var data = {
                        mobile: mobile,
                        mobile_code: $('.mobile-code').text().substr(1),
                        _token: token
                    }
                }
                if (name == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    domModifyForExists(response, name);
                });
            }

            let usernameError = false;
            let mobileError = false;

            function domModifyForExists(response, name) {
                if (response.data == true) {
                    if (name == 'username') {
                        var message = `@lang('Username already exists')`;
                        usernameError = true
                    } else {
                        var message = `@lang('Mobile number already exists')`;
                        mobileError = true;
                    }

                    $(`.${name}-exists-error`)
                        .html(`${message}`)
                        .removeClass('d-none')
                        .addClass("text--danger mt-1 d-block");
                } else {
                    $(`.${name}-exists-error`)
                        .empty()
                        .addClass('d-none')
                        .removeClass("text--danger mt-1 d-block");

                    if (name == 'username') {
                        usernameError = false;
                    } else {
                        mobileError = false;
                    }
                }

                if (!usernameError && !mobileError) {
                    $(`button[type=submit]`)
                        .attr('disabled', false)
                        .removeClass('disabled');
                } else {
                    $(`button[type=submit]`)
                        .attr('disabled', true)
                        .addClass('disabled');
                }
            }
        })(jQuery);
    </script>
@endpush
