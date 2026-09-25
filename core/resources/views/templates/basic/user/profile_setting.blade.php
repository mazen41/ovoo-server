@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <form method="POST" enctype="multipart/form-data">
            @csrf
            <div class="container-top">
                <div class="container-top__left">
                    <h5 class="container-top__title">@lang('Manage Your Account')</h5>
                    <p class="container-top__desc">@lang('Update your account details, preferences, and more')</p>
                </div>
                <div class="container-top__right">
                    <div class="btn--group">
                        <a href="{{ route('user.home') }}" class="btn btn--dark btn-shadow">
                            <i class="las la-tachometer-alt"></i>
                            @lang('Go to Dashboard')
                        </a>
                        <button type="submit" class="btn btn--base btn-shadow">
                            <i class="lab la-telegram"></i>
                            @lang('Save Changes')
                        </button>
                    </div>
                </div>
            </div>
            <div class="dashboard-container__body">
                <div class="profile-header">
                    <h5 class="profile-header__title">@lang('Profile Picture')</h5>
                    <div class="profile-header__thumb">
                        <div class="file-upload">
                            <label class="edit" for="profile_image"><i class="las la-plus"></i></label>
                            <input type="file" name="profile_image" class="form--control form-two" id="profile_image"
                                hidden>
                        </div>
                        <div class="thumb">
                            <img class="image-preview" src="{{ @$user->imageSrc }}" alt="profile">
                        </div>
                    </div>
                    <p class="thumb-size"> @lang('Recommended profile image size') : <span class="number"> @lang('350x300.')</span>
                        @lang('Supported files') <span class="number">@lang('.jpg'), @lang('.png')
                            @lang('.jpeg')</span>
                    </p>
                </div>
                <div class="profile-info">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>@lang('Firstname')</label>
                                <input type="text" class="form--control form-two" placeholder="@lang('Enter your first name')"
                                    name="firstname" value="{{ $user->firstname }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>@lang('Lastname')</label>
                                <input type="text" class="form--control form-two" placeholder="@lang('Enter your last name')"
                                    name="lastname" value="{{ $user->lastname }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>@lang('Email')</label>
                                <input type="email" class="form--control form-two" name="email"
                                    value="{{ $user->email }}" readonly required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label> @lang('Mobile') </label>
                                <div class="input-group select-input">
                                    <select class="form-select form--control form-two select2" name="dial_code" disabled>
                                        <option value="{{ $user->dial_code }}">{{ $user->dial_code }}</option>
                                    </select>
                                    <input type="number" class="form--control form-two form-control" name="mobile"
                                        value="{{ $user->mobile }}" readonly required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>@lang('City')</label>
                                <input type="city" class="form--control form-two" name="city"
                                    value="{{ @$user->city }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>@lang('State')</label>
                                <input type="text" class="form--control form-two" name="state"
                                    value="{{ @$user->state }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>@lang('Zip')</label>
                                <input type="text" class="form--control form-two" name="zip"
                                    value="{{ @$user->zip }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label> @lang('Country') </label>
                                <select class="form-select form--control form-two select2" disabled>
                                    <option value="{{ $user->country_name }}">{{ __(@$user->country_name) }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush
@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('topbar_tabs')
    @include('Template::partials.profile_tab')
@endpush

@push('style')
    <style>
        ::placeholder {
            color: hsl(var(--black)/0.5) !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('#profile_image').on('change', function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('.image-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });
        })(jQuery);
    </script>
@endpush
