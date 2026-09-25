@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Quickly add new agent by completing the simple form below.')</p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <a href="{{ route('user.agent.list') }}" class="btn btn--dark btn-shadow">
                        <i class="las la-list"></i>
                        @lang('Agent List')
                    </a>
                    <button type="submit" form="agent-form" class="btn btn--base btn-shadow">
                        <i class="lab la-telegram"></i>
                        @lang('Save Agent')
                    </button>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <div class="information-wrapper">
                <div class="row">
                    <div class="col-xxl-8">
                        <form action="{{ route('user.agent.save') }}" method="POST" id="agent-form">
                            @csrf
                            <div class="row gy-2">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('First Name')</label>
                                        <input type="text" class="form--control form-two" name="firstname"
                                            placeholder="@lang('Enter firstname')" value="{{ old('firstname') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Last Name')</label>
                                        <input type="text" class="form--control form-two" name="lastname"
                                            placeholder="@lang('Enter lastname')" required value="{{ old('lastname') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Username')</label>
                                        <input type="text" class="form--control form-two checkUser" name="username"
                                            placeholder="@lang('Enter username')" required value="{{ old('username') }}">
                                        <span class="username-exists-error d-none"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Email Address')</label>
                                        <input type="email" class="form--control form-two checkUser" name="email"
                                            placeholder="@lang('Enter email')" required value="{{ old('email') }}">
                                        <span class="email-exists-error d-none"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Country')</label>
                                        <select name="country" class="form-control form--control select2" required>
                                            @foreach ($countries as $key => $country)
                                                <option data-mobile_code="{{ $country->dial_code }}"
                                                    value="{{ $country->country }}" data-code="{{ $key }}">
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
                                                class="form-control form--control form-two checkUser" required>
                                        </div>
                                        <span class="mobile-exists-error d-none"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('City')</label>
                                        <input type="text" class="form--control form-two" name="city"
                                            placeholder="@lang('Enter city')" value="{{ old('city') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('State')</label>
                                        <input type="text" class="form--control form-two" name="state"
                                            placeholder="@lang('Enter state')" value="{{ old('state') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Zip Code')</label>
                                        <input type="text" class="form--control form-two" name="zip"
                                            placeholder="@lang('Enter zip')" value="{{ old('zip') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Address')</label>
                                        <input type="text" class="form--control form-two" name="address"
                                            placeholder="@lang('Enter address')" value="{{ old('address') }}">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
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
                if (name == 'email') {
                    var data = {
                        email: value,
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
                    } else if (name == 'email') {
                        var message = `@lang('Email already exists')`;
                        usernameError = true
                    } else {
                        var message = `@lang('Mobile already exists')`;
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
                    } else if (name == 'email') {
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