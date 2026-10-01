@php
    $authContent = @getContent('auth.content', true)->data_values;
@endphp
@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @if (gs('registration'))
        <section class="account mb-100 section-shape banner-bg">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <div class="account-inner">
                            <div class="row">
                                <div class="col-lg-6  d-lg-block d-none">
                                    <div class="account-thumb">
                                        <img src="{{ frontendImage('auth', @$authContent->register_image) }}" alt="">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="account-form">
                                        <div class="account-form__content mb-4">
                                            <h3 class="account-form__title mb-2" data-highlight="[-1]">
                                                {{ __(@$authContent->register_title) }}</h3>
                                            <p class="account-form__desc">{{ __(@$authContent->register_subtitle) }}</p>
                                        </div>
                                        <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha">
                                            @csrf
                                            <div class="row gy-2">
                                                @if (session()->get('reference') != null)
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="referenceBy"
                                                                class="form-label">@lang('Reference By')</label>
                                                            <input type="text" name="referBy" id="referenceBy"
                                                                class="form--control"
                                                                value="{{ session()->get('reference') }}" readonly>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>@lang('First Name')</label>
                                                        <input type="text" class="form--control" name="firstname"
                                                            placeholder="@lang('Enter your first name')" value="{{ old('firstname') }}"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>@lang('Last Name')</label>
                                                        <input type="text" class="form--control" name="lastname"
                                                            placeholder="@lang('Enter your last name')" value="{{ old('lastname') }}"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>@lang('Your Email')</label>
                                                        <input type="email" class="form--control checkUser" name="email"
                                                            placeholder="@lang('Enter your email')" value="{{ old('email') }}"
                                                            required>
                                                        <span class="exists-error d-none"></span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>@lang('Password')</label>
                                                        <div class="position-relative">
                                                            <input type="password" class="form-control form--control"
                                                                name="password" placeholder="@lang('Enter your password')" required>
                                                            <span
                                                                class="password-show-hide fas fa-solid toggle-password fa-eye-slash"></span>
                                                            <x-strong-password />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>@lang('Confirm Password')</label>
                                                        <div class="position-relative">
                                                            <input type="password" class="form-control form--control"
                                                                name="password_confirmation"
                                                                placeholder="@lang('Confirm your password')" required>
                                                            <div
                                                                class="password-show-hide fas fa-solid toggle-password fa-eye-slash">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if (gs('agree'))
                                                    @php
                                                        $policyPages = getContent(
                                                            'policy_pages.element',
                                                            false,
                                                            orderById: true,
                                                        );
                                                    @endphp
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <div class="form--check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="agree" id="agree">
                                                                <div class="form-check-label">
                                                                    <label for="agree" @checked(old('agree'))
                                                                        name="agree"> @lang('I agree to the') </label>
                                                                    @foreach ($policyPages as $policy)
                                                                        <a href="{{ route('policy.pages', $policy->slug) }}"
                                                                            target="_blank">{{ __($policy->data_values->title) }}</a>
                                                                        @if (!$loop->last)
                                                                            ,
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <x-captcha />
                                                <div class="col-sm-12">
                                                    <button type="submit" class="btn btn--base w-100">
                                                        @lang('Create New Account')
                                                    </button>
                                                </div>
                                                @include($activeTemplate . 'partials.social_login')
                                                <div class="col-sm-12">
                                                    <div class="have-account">
                                                        <p class="have-account__text">
                                                            @lang('Already Have An Account?')
                                                            <a href="{{ route('user.login') }}"
                                                                class="have-account__link text--base">
                                                                @lang('Login in here')
                                                            </a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        @include($activeTemplate . 'partials.registration_disabled')
    @endif

@endsection

@if (gs('registration'))
    @push('script')
        <script>
            "use strict";
            (function($) {

                $('.checkUser').on('focusout', function(e) {
                    var url = "{{ route('user.checkUser') }}";
                    var value = $(this).val();
                    var token = '{{ csrf_token() }}';

                    var data = {
                        email: value,
                        _token: token
                    }

                    $.post(url, data, function(response) {
                        if (response.data == true) {
                            $(".exists-error").html(`
                                @lang('You’re already part of our community!')
                                <a class="ms-1" href="{{ route('user.login') }}">@lang('Login now')</a>
                            `).removeClass('d-none').addClass("text--danger mt-1 d-block");
                            $(`button[type=submit]`).attr('disabled', true).addClass('disabled');
                        } else {
                            $(".exists-error").empty().addClass('d-none').removeClass(
                                "text--danger mt-1 d-block");
                            $(`button[type=submit]`).attr('disabled', false).removeClass('disabled');
                        }
                    });
                });
            })(jQuery);
        </script>
    @endpush
@endif
