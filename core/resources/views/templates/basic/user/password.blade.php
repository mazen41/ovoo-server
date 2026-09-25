@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <form method="POST" class="verify-gcaptcha">
            @csrf
            <div class="container-top">
                <div class="container-top__left">
                    <h5 class="container-top__title">@lang('Change Password')</h5>
                    <p class="container-top__desc">@lang('Update your account password by filling out the form below')</p>
                </div>
                <div class="container-top__right">
                    <div class="btn--group">
                        <a href="{{ route('user.home') }}" class="btn btn--dark btn-shadow">
                            <i class="las la-tachometer-alt"></i>
                            @lang('Go to Dashboard')
                        </a>
                        <button class="btn btn--base btn-shadow">
                            <i class="lab la-telegram"></i>
                            @lang('Save Changes')
                        </button>
                    </div>
                </div>
            </div>
            <div class="dashboard-container__body">
                <div class="profile-info">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>@lang('New Password')</label>
                                <input type="password" class="form--control form-two secure-password"
                                    placeholder="@lang('Enter your new password')" name="password" required>
                                <x-strong-password />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>@lang('Confirm Password')</label>
                                <input type="password" class="form--control form-two" placeholder="@lang('Confirm your new password')"
                                    name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>@lang('Old Password')</label>
                                <input type="password" class="form--control form-two" placeholder="@lang('Enter your old password')"
                                    name="current_password" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('topbar_tabs')
    @include('Template::partials.profile_tab')
@endpush
