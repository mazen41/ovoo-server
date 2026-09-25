@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="row justify-content-center justify-content-xl-start">
            <div class="col-12">
                <div class="dashboard-container__body">
                    <form method="POST">
                        @csrf
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item d-flex gap-2 flex-wrap justify-content-between ps-0">
                                <span class="d-flex gap-2 flex-wrap pe-5 pe-md-0">
                                    <span class="section-bg notification-icon d-none d-md-block text-center">
                                        <i class="la la-envelope"></i>
                                    </span>
                                    <span>
                                        <span class="fs-18">
                                            @lang('Email Notification')
                                        </span>
                                        <span class="d-block fs-14">@lang('Receive important updates via email')</span>
                                    </span>
                                </span>
                                <span>
                                    <div class="form-check form-switch form--switch pl-0 form-switch-success ps-0">
                                        <input class="form-check-input" type="checkbox" role="switch" name="en"
                                            @checked($user->en)>
                                    </div>
                                </span>
                            </li>
                            <li class="list-group-item d-flex gap-2 flex-wrap justify-content-between ps-0">
                                <span class="d-flex gap-2 flex-wrap pe-5 pe-md-0">
                                    <span class="section-bg notification-icon d-none d-md-block text-center">
                                        <i class="la la-mobile-phone"></i>
                                    </span>
                                    <span>
                                        <span class="fs-18">
                                            @lang('SMS Notification')
                                        </span>
                                        <span class="d-block fs-14">@lang('Get instant alerts via SMS')</span>
                                    </span>
                                </span>
                                <span>
                                    <div class="form-check form-switch form--switch pl-0 form-switch-success ps-0">
                                        <input class="form-check-input" type="checkbox" role="switch" name="sn"
                                            @checked($user->sn)>
                                    </div>
                                </span>
                            </li>
                            <li class="list-group-item d-flex gap-2 flex-wrap justify-content-between ps-0">
                                <span class="d-flex gap-2 flex-wrap pe-5 pe-md-0">
                                    <span class="section-bg notification-icon d-none d-md-block text-center">
                                        <i class="la la-bell"></i>
                                    </span>
                                    <span>
                                        <span class="fs-18">
                                            @lang('Push Notification')
                                        </span>
                                        <span class="d-block fs-14">@lang('Stay updated with real-time push notifications')</span>
                                    </span>
                                </span>
                                <span>
                                    <div class="form-check form-switch form--switch pl-0 form-switch-success ps-0">
                                        <input class="form-check-input" type="checkbox" role="switch" name="pn"
                                            @checked($user->pn)>
                                    </div>
                                </span>
                            </li>
                        </ul>
                        <button class="btn btn--base btn-shadow" type="submit">
                            <i class="fa fa-paper-plane"></i> @lang('Update Notification Setting')
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection


@push('style')
    <style>
        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            padding: 8px;
        }

        .notification-icon i {
            font-size: 1.5rem;
        }

        /*  Custom Switch Design */
        .form--switch .form-check-input {
            border-radius: 3px;
            background-image: none;
            position: relative;
            box-shadow: none;
            border: 0;
            background-color: hsl(var(--black)/0.1) !important;
            padding: 10px !important;
            margin-left: 0;
            margin-bottom: 5px;
            border-radius: 40px;
            width: 45px;
            height: 20px;
            cursor: pointer;
        }

        .form--switch .form-check-input:focus {
            border-radius: 40px;
            background-image: none;
            position: relative;
            box-shadow: none;
            border: 0;
        }

        .form--switch .form-check-input::before {
            position: absolute;
            content: "";
            width: 15px;
            height: 15px;
            background-color: #fff;
            top: 50%;
            transform: translateY(-50%);
            border-radius: 2px;
            left: 3px;
            border-radius: 50%;
            transition: 0.2s linear;
        }

        .form--switch .form-check-input:checked {
            background-color: hsl(var(--primary)) !important;
        }

        .form--switch .form-check-input:checked::before {
            left: calc(100% - 18px);
            background-color: #fff !important;
        }

        .form--switch .form-check-input:checked[type=checkbox] {
            background-image: none;
        }

        .form--switch .form-check-label {
            width: calc(100% - 14px);
            padding-left: 5px;
            cursor: pointer;
        }

        .form--switch.switch--lg .form-check-input {
            width: 70px;
            height: 38px;
        }

        .form--switch.switch--lg .form-check-input::before {
            width: 28px;
            height: 28px;
        }

        .form--switch.switch--lg .form-check-input:checked {
            background-color: hsl(var(--success)) !important;
        }

        .form--switch.switch--lg .form-check-input:checked::before {
            left: calc(100% - 33px);
        }

        .form-switch-success.form--switch .form-check-input:checked {
            background-color: hsl(var(--success)) !important;
        }
    </style>
@endpush
