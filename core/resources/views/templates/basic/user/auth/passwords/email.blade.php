@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="verification-section banner-bg">
        <div class="container">
            <div class="verification-code-wrapper">
                <div class="verification-area">
                    <div class="card custom--card">
                        <div class="card-header">
                            <h5 class="card-title">{{ __($pageTitle) }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <p>@lang('To recover your account please provide your email or username to find your account.')</p>
                            </div>
                            <form method="POST" action="{{ route('user.password.email') }}" class="verify-gcaptcha">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">@lang('Email or Username')</label>
                                    <input type="text" class="form--control" name="value" value="{{ old('value') }}"
                                        required autofocus="off">
                                </div>
                                <x-captcha />
                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100 btn-shadow">
                                        <i class="fa fa-paper-plane"></i> @lang('Submit')
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
