@extends('admin.layouts.master')
@section('content')
    <main class="account">
        <span class="account__overlay bg-img dark-bg"
            data-background-image="{{ asset('assets/admin/images/login-dark.png') }}"></span>
        <span class="account__overlay bg-img light-bg"
            data-background-image="{{ asset('assets/admin/images/login-bg.png') }}"></span>
        <div class="account__card">
            <div class="account__logo">
                <img class="light-show" src="{{ siteLogo() }}" alt="brand-thumb">
                <img class="dark-show" src="{{ siteLogo('dark') }}" alt="brand-thumb">
            </div>
            <h2 class="account__title">@lang('Reset Password') 👋</h2>
            <p class="account__desc">@lang('Please enter your new password below to secure your account')</p>
            <form action="{{ route('admin.password.change') }}" method="POST" class="account__form verify-gcaptcha">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group">
                    <label class="form--label">@lang('New Password')</label>
                    <input type="password" class="form--control h-48" name="password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form--label">@lang('Confirm Password')</label>
                    <div class="position-relative">
                        <input id="password_confirmation" name="password_confirmation" required type="password" class="form--control h-48">
                        <span class="password-show-hide fas toggle-password fa-eye-slash" id="#password_confirmation"></span>
                    </div>
                </div>

                <x-captcha :isAdmin=true />
                <div class="form-group">
                    <button type="submit" class="btn btn--primary w-100  h-48 mb-2 fs-16">
                        <i class="fa-regular fa-paper-plane"></i> @lang('Submit')
                    </button>
                    <a href="{{ route('admin.login') }}" class="forgot-password">
                        <i class="fas fa-arrow-alt-circle-left"></i> @lang('Back to login')
                    </a>
                </div>
            </form>
        </div>
    </main>
@endsection
