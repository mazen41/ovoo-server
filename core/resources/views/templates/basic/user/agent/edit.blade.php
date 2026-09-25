@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Quickly update your agent data by completing the simple form below.')</p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <a href="{{ route('user.agent.list') }}" class="btn btn--dark btn-shadow">
                        <i class="las la-undo"></i>
                        @lang('Back')
                    </a>
                    <button type="submit" form="agent-form" class="btn btn--base btn-shadow">
                        <i class="lab la-telegram"></i>
                        @lang('Update Agent')
                    </button>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <div class="information-wrapper">
                <div class="row">
                    <div class="col-xxl-8">
                        <form action="{{ route('user.agent.update', $agent->id) }}" method="POST" id="agent-form">
                            @csrf
                            <div class="row gy-2">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('First Name')</label>
                                        <input type="text" class="form--control form-two" name="firstname"
                                            placeholder="@lang('Enter firstname')"
                                            value="{{ old('firstname', $agent->firstname) }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Last Name')</label>
                                        <input type="text" class="form--control form-two" name="lastname"
                                            placeholder="@lang('Enter lastname')" required
                                            value="{{ old('lastname', $agent->lastname) }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Username')</label>
                                        <input type="text" class="form--control form-two checkUser" name="username"
                                            placeholder="@lang('Enter username')" required readonly
                                            value="{{ $agent->username }}">
                                        <span class="username-exists-error d-none"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Email Address')</label>
                                        <input type="email" class="form--control form-two checkUser" name="email"
                                            placeholder="@lang('Enter email')" required readonly value="{{ $agent->email }}">
                                        <span class="email-exists-error d-none"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label> @lang('Country') </label>
                                        <select class="form-select form--control form-two select2" disabled>
                                            <option value="{{ $agent->country_name }}">{{ __(@$agent->country_name) }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label> @lang('Mobile') </label>
                                        <div class="input-group select-input">
                                            <select class="form-select form--control form-two select2" name="dial_code"
                                                disabled>
                                                <option value="{{ $agent->dial_code }}">{{ $agent->dial_code }}</option>
                                            </select>
                                            <input type="number" class="form--control form-two form-control" name="mobile"
                                                value="{{ $agent->mobile }}" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('City')</label>
                                        <input type="text" class="form--control form-two" name="city"
                                            placeholder="@lang('Enter city')" value="{{ old('city', $agent->city) }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('State')</label>
                                        <input type="text" class="form--control form-two" name="state"
                                            placeholder="@lang('Enter state')" value="{{ old('state', $agent->state) }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Zip Code')</label>
                                        <input type="text" class="form--control form-two" name="zip"
                                            placeholder="@lang('Enter zip')" value="{{ old('zip', $agent->zip) }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Address')</label>
                                        <input type="text" class="form--control form-two" name="address"
                                            placeholder="@lang('Enter address')"
                                            value="{{ old('address', $agent->address) }}">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush
