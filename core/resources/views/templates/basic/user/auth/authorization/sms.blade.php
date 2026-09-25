@extends($activeTemplate . 'layouts.app')
@section('app-content')
    <div class="verification-section banner-bg">
        <div class="container">
            <div class="verification-section__top">
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ siteLogo('dark') }}" alt="logo">
                </a>
            </div>
            <div class="verification-wrapper">
                <div class="verification-area">
                    <div class="verification-area__content">
                        <div class="verification-wrapper__icon">
                            <i class="fa-solid fa-envelope-open"></i>
                        </div>
                        <h3 class="title"> @lang('Verify Your Mobile Number') </h3>
                        <p class="verification-text">
                            @lang('A 6 digit verification code sent to your mobile number') : {{ showMobileNumber($user->mobileNumber) }}
                            @lang('Please enter the code below')
                        </p>
                    </div>

                    <form action="{{ route('user.verify.mobile') }}" method="POST" class="submit-form">
                        @csrf
                        @include($activeTemplate . 'partials.verification_code')
                    </form>
                    <div class="mt-3 text-center">
                        <p class="text--base">
                            @lang('If you don\'t get any code'), <span class="countdown-wrapper">@lang('try again after') <span id="countdown"
                                    class="fw-bold">--</span> @lang('seconds')</span> <a
                                href="{{ route('user.send.verify.code', 'sms') }}" class="try-again-link d-none">
                                @lang('Try again')</a>
                        </p>
                    </div>
                    <div class="verification-area__btn">
                        <button class="btn btn--base btn-shadow">
                            <i class="la la-check-circle"></i> @lang('Verify Now')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        var distance = Number("{{ @$user->ver_code_send_at->addMinutes(2)->timestamp - time() }}");
        var x = setInterval(function() {
            distance--;
            document.getElementById("countdown").innerHTML = distance;
            if (distance <= 0) {
                clearInterval(x);
                document.querySelector('.countdown-wrapper').classList.add('d-none');
                document.querySelector('.try-again-link').classList.remove('d-none');
            }
        }, 1000);
    </script>
@endpush
