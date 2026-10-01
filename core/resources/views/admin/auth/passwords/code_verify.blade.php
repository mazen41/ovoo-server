@extends('admin.layouts.master')
@section('content')
    <main class="account">
        <span class="account__overlay bg-img dark-bg"
            data-background-image="{{ asset('assets/admin/images/login-dark.png') }}"></span>
        <span class="account__overlay bg-img light-bg"
            data-background-image="{{ asset('assets/admin/images/login-bg.png') }}"></span>
        <div class="account__card">
            <div class="account__logo">
                <img class="light-show" src="{{ siteLogo() }}">
                <img class="dark-show" src="{{ siteLogo('dark') }}">
            </div>
            <h2 class="account__title">@lang('Verify Code')</h2>
            <p class="account__desc">@lang('Please check your email for the verification code and enter it below')</p>
            <form action="{{ route('admin.password.verify.code') }}" method="POST" class="account__form">
                @csrf
                <div class="form-group">
                    <div class="verification-code">
                        <input type="number" name="code" class="overflow-hidden" autocomplete="off" maxlength="6">
                        <div class="boxes">
                            <span>-</span>
                            <span>-</span>
                            <span>-</span>
                            <span>-</span>
                            <span>-</span>
                            <span>-</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn--primary w-100  h-48 mb-2 fs-16">
                        <i class="fa-regular fa-circle-check"></i> @lang('Verify Now')
                    </button>
                    <a href="{{ route('admin.login') }}" class="forgot-password">
                        <i class="fas fa-arrow-alt-circle-left"></i> @lang('Back to login')
                    </a>
                </div>
            </form>
        </div>
    </main>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/verification_code.css') }}">
@endpush

@push('script')
    <script>
        (function($) {
            'use strict';
            $('[name=code]').on('input', function() {


                $(this).val(function(i, val) {
                    
                    if (val.length == 6) {
                        $(this).blur();
                        $('form').find('button[type=submit]').html(
                            '<i class="las la-spinner fa-spin"></i>');
                        $('form').find('button[type=submit]').removeClass('disabled');
                        $('form')[0].submit();
                    } else {
                        $('form').find('button[type=submit]').addClass('disabled');
                    }
                    if (val.length > 6) {
                        return val.substring(0, val.length - 1);
                    }
                    return val;
                });
                for (let index = $(this).val().length; index >= 0; index--) {
                    $($('.boxes span')[index]).html('');
                }
            });
        })(jQuery)
    </script>
@endpush
