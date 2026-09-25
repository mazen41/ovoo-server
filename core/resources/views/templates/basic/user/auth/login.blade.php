@php
    $authContent = @getContent('auth.content', true)->data_values;
@endphp
@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="account mb-100 section-shape banner-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="account-inner">
                        <div class="row">
                            <div class="col-lg-6  d-lg-block d-none">
                                <div class="account-thumb">
                                    <img src="{{ frontendImage('auth', @$authContent->login_image) }}" alt="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="account-form">
                                    <div class="account-form__content mb-4">
                                        <h3 class="account-form__title mb-2">{{ __(@$authContent->login_title) }}</h3>
                                        <p class="account-form__desc">{{ __(@$authContent->login_subtitle) }}</p>
                                    </div>
                                    <form action="{{ route('user.login') }}" method="POST" class="verify-gcaptcha">
                                        @csrf
                                        <div class="row gy-2">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>@lang('Username')</label>
                                                    <input type="text" class="form--control" name="username"
                                                        placeholder="@lang('Enter your username or email')" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>@lang('Password')</label>
                                                    <div class="position-relative">
                                                        <input type="password" class="form-control form--control"
                                                            name="password" placeholder="@lang('Enter your password')" required>
                                                        <span
                                                            class="password-show-hide fas toggle-password fa-eye-slash"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <x-captcha />
                                            <div class="col-sm-12">
                                                <div class="d-flex flex-wrap justify-content-between form-group">
                                                    <div class="form--check">
                                                        <input class="form-check-input" type="checkbox" id="remember"
                                                            {{ old('remember') ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="remember">@lang('Keep me logged in')</label>
                                                    </div>
                                                    <p class="forgot-password">
                                                        <a href="{{ route('user.password.request') }}"
                                                            class="forgot-password__link text--base">@lang('Forgot Your Password?')
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <button type="submit"
                                                    class="btn btn--base w-100">@lang('Sign In')</button>
                                            </div>
                                            @include($activeTemplate . 'partials.social_login')
                                            <div class="col-sm-12">
                                                <div class="have-account">
                                                    <p class="have-account__text">@lang('Don\'t Have An Account?') <a
                                                            href="{{ route('user.register') }}"
                                                            class="have-account__link text--base">@lang('Register here')</a></p>
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
@endsection
