@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="verification-section banner-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-5">
                    <div class="card custom--card">
                        <div class="card-header">
                            <h5 class="card-title">@lang('Reset Password')</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <p>@lang('Your account is verified successfully. Now you can change your password. Please enter a strong password and don\'t share it with anyone.')</p>
                            </div>
                            <form method="POST" action="{{ route('user.password.update') }}">
                                @csrf
                                <input type="hidden" name="email" value="{{ @$email }}">
                                <input type="hidden" name="token" value="{{ @$token }}">
                                <div class="form-group">
                                    <label class="form-label">@lang('Password')</label>
                                    <input type="password"
                                        class="form-control form--control @gs('secure_password')secure-password @endgs"
                                        name="password" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">@lang('Confirm Password')</label>
                                    <input type="password" class="form-control form--control" name="password_confirmation"
                                        required>
                                </div>
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

@gs('secure_password')
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endgs
