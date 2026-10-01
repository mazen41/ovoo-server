@extends('admin.layouts.master')
@section('content')
    <main class="account">
        <span class="account__overlay bg-img dark-bg"
            data-background-image="{{ asset('assets/admin/images/login-dark.png') }}"></span>
        <span class="account__overlay bg-img light-bg"
            data-background-image="{{ asset('assets/admin/images/login-bg.png') }}"></span>
        <div class="account__card">
            <div class="account__logo">
                <img src="{{ siteLogo() }}" class="light-show" alt="brand-thumb">
                <img src="{{ siteLogo('dark') }}" class="dark-show" alt="brand-thumb">
            </div>
            <h2 class="account__title">@lang('Welcome Back') 👋</h2>
            <p class="account__desc">@lang('Please enter your credentials to proceed to the next step.')</p>
            <form action="{{ route('admin.login') }}" method="POST" class="account__form verify-gcaptcha">
                @csrf
                <div class="form-group">
                    <label class="form--label">@lang('Username')</label>
                    <input type="text" class="form--control h-48" value="{{ old('username') }}" name="username" required>
                </div>
                <div class="form-group">
                    <label  class="form--label">@lang('Password')</label>
                    <div class="position-relative">
                        <input id="password" name="password" required type="password" class="form--control h-48">
                        <span class="password-show-hide fas toggle-password fa-eye-slash" id="#password"></span>
                    </div>
                </div>
                <x-captcha :isAdmin=true />
                <div class="form-group">
                    <button type="submit" class="btn btn--primary w-100  h-48 mb-2 fs-16">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> @lang('Login')
                    </button>
                    <a href="{{ route('admin.password.reset') }}" class="forgot-password">
                        @lang('Forgot your password')?
                    </a>
                </div>
            </form>
        </div>
    </main>
@endsection
