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
            <h2 class="account__title">@lang('Recover Account')</h2>
            <p class="account__desc">@lang('Please enter your email to recover account')</p>
            <form action="{{ route('admin.password.reset') }}" method="POST" class="account__form verify-gcaptcha">
                @csrf
                <div class="form-group">
                    <label class="form--label">@lang('Email')</label>
                    <input type="email" class="form--control h-48" value="{{ old('email') }}" name="email" required>
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
