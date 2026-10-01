@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="ban-section banner-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">

                    <div class="ban-wrapper">
                        <div class="ban-icon">
                            <img src="{{ getImage($activeTemplateTrue . 'images/ban.png') }}" alt="image">
                        </div>
                        <h2 class="text-center text--danger">@lang('YOU ARE BANNED')</h2>
                        <p class="ban-desc">
                            @lang('Your account has been banned due to a violation of our terms or policies.If you believe this is a mistake, please contact support for review.Access to services is currently restricted.')
                        </p>
                        <div class="text-wrapper">
                            <p class="fw-bold  text">@lang('Reason'):</p>
                            <p class="text">{{ __(@$user->ban_reason) }}</p>
                        </div>
                        <div class="btn--groups">
                            <a href="{{ route('home') }}" class="fw-bold home-link btn-outline--base btn">
                                <i class="la la-globe"></i>
                                @lang('Browse') {{  __(gs('site_name')) }}
                            </a>
                            <a href="{{ route('user.logout') }}" class="fw-bold btn-outline--danger btn">
                                <i class="las la-sign-out-alt"></i>
                                @lang('Logout')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .ban-section {
            padding-top: 60px;
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-bottom: 30px;
        }

        .ban-icon {
            text-align: center;
            font-size: 70px;
            color: hsl(var(--danger));
            line-height: 1;
            margin-bottom: 10px;
        }

        .text-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .text-wrapper .text {
            font-size: 24px
        }

        .btn--groups {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px 20px;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .ban-desc {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
        }
    </style>
@endpush
