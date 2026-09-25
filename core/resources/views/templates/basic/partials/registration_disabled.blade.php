@php
    $registrationDisabled = getContent('register_disable.content', true);
@endphp
<div class="register-disable banner-bg">
    <div class="container">
        <div class="register-disable-image">
            <img class="fit-image"
                src="{{ frontendImage('register_disable', @$registrationDisabled->data_values->image, '280x280') }}"
                alt="">
        </div>

        <h5 class="register-disable-title">{{ __(@$registrationDisabled->data_values->heading) }}</h5>
        <p class="register-disable-desc">
            {{ __(@$registrationDisabled->data_values->subheading) }}
        </p>
        <div class="text-center">
            <a href="{{ route('home') }}" class="register-disable-footer-link btn btn--base btn-shadow">
                @lang('Browse') {{ __(gs('site_name')) }}
            </a>
        </div>
    </div>
</div>
@push('style')
    <style>
        .register-disable {
            height: 100vh;
            width: 100%;
            background-color: hsl(var(--body-bg));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-disable.banner-bg {
            padding-top: 0;
        }

        .register-disable-image {
            max-width: 300px;
            width: 100%;
            margin: 0 auto 32px;
        }

        .register-disable-title {
            color: hsl(var(--white));
            font-size: 42px;
            margin-bottom: 18px;
            text-align: center
        }

        @media (max-width:575px) {
            .register-disable-image {
                max-width: 200px;
            }

            .register-disable-title {
                font-size: 32px;
            }
        }

        .register-disable-icon {
            font-size: 16px;
            background: rgb(255, 15, 15, .07);
            color: rgb(255, 15, 15, .8);
            border-radius: 3px;
            padding: 6px;
            margin-right: 4px;
        }

        .register-disable-desc {
            color: hsl(var(--white)/.8);
            font-size: 18px;
            max-width: 565px;
            width: 100%;
            margin: 0 auto 32px;
            text-align: center;
        }

        .register-disable-footer-link {
            color: #fff;
            background-color: #5B28FF;
            padding: 13px 24px;
            border-radius: 6px;
            text-decoration: none
        }

        .register-disable-footer-link:hover {
            background-color: #440ef4;
            color: #fff;
        }
    </style>
@endpush
