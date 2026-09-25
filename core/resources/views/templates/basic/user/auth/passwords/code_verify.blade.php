@extends($activeTemplate . 'layouts.app')
@section('app-content')
    <div class="verification-section banner-bg">
        <div class="container">
            <div class="verification-section__top">
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ siteLogo('dark') }}" alt="image">
                </a>
            </div>
            <div class="verification-code-wrapper">
                <div class="verification-area">
                    <div class="verification-area__content">
                        <div class="verification-wrapper__icon">
                            <i class="fa-solid fa-envelope-open"></i>
                        </div>
                        <h3 class="title">@lang('Verify Email Address')</h3>
                        <p class="verification-text">
                            @lang('A 6 digit verification code sent to your email address') : {{ showEmailAddress($email) }}
                            @lang('Please enter the code below')
                        </p>
                    </div>
                    <form action="{{ route('user.password.verify.code') }}" method="POST" class="submit-form">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        @include($activeTemplate . 'partials.verification_code')
                        <div class="verification-area__btn">
                            <button type="submit" class="btn btn--base btn-shadow">
                              <i class="fa fa-paper-plane"></i>  @lang('Submit')
                            </button>
                        </div>
                        <div class="form-group verification-text mb-0 mt-4">
                            @lang('Please check including your Junk/Spam Folder. if not found, you can')
                            <a href="{{ route('user.password.request') }}">@lang('Try to send again')</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
