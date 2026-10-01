@extends($activeTemplate . 'layouts.app')
@section('app-content')
    <div class="verification-section banner-bg">
        <div class="container">
            <div class="verification-section__top">
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ siteLogo('dark') }}" alt="@lang('Logo')">
                </a>
            </div>
            <div class="verification-code-wrapper">
                <div class="verification-area">
                    <div class="verification-area__content">
                        <div class="verification-wrapper__icon">
                            <i class="fa-solid fa-shield-alt"></i>
                        </div>
                        <h3 class="title">@lang('Two-Factor Authentication')</h3>
                        <p class="text">@lang('For enhanced security, please enter the 6-digit verification code sent to your email.')</p>
                    </div>
                    <p class="verification-code__text">@lang('Enter the Verification Code')</p>
                    <form action="{{ route('user.2fa.verify') }}" method="POST" class="submit-form">
                        @csrf
                        <p class="verification-text">@lang('We have sent a 6-digit verification code to your email') : {{ showEmailAddress(@$email) }}</p>
                        <input type="hidden" name="email" value="{{ @$email }}">
                        @include($activeTemplate . 'partials.verification_code')
                        <div class="verification-area__btn">
                            <button type="submit" class="btn btn--base">@lang('Verify & Continue')</button>
                        </div>
                        <div class="form-group verification-text mb-0 mt-4">
                            @lang('Didn’t receive the code? Please check your Junk/Spam folder. If still not found, you can')
                            <a href="{{ route('user.password.request') }}">@lang('request a new code.')</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
