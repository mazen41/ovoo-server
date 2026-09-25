@php
    $socialCredentials = gs('socialite_credentials');
@endphp
@if (
    @$socialCredentials->linkedin->status ||
        @$socialCredentials->facebook->status == Status::ENABLE ||
        @$socialCredentials->google->status == Status::ENABLE)
    <div class="col-sm-12">
        <div class="social-link-wrapper">
            @if (request()->routeIs('user.login'))
                <p class="text">@lang('OR USE YOUR SOCIAL ACCOUNT TO LOGIN')</p>
            @else
                <p class="text">@lang('OR USE YOUR SOCIAL ACCOUNT TO REGISTER')</p>
            @endif
            <ul class="social-link-list  mb-3">
                @if (@$socialCredentials->google->status == Status::ENABLE)
                    <li>
                        <a href="{{ route('user.social.login', 'google') }}" class="social-btn-link google">
                            <i class="fab fa-google"></i>
                        </a>
                    </li>
                @endif
                @if (@$socialCredentials->facebook->status == Status::ENABLE)
                    <li>
                        <a href="{{ route('user.social.login', 'facebook') }}" class="social-btn-link facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </li>
                @endif
                @if (@$socialCredentials->linkedin->status == Status::ENABLE)
                    <li>
                        <a href="{{ route('user.social.login', 'linkedin') }}" class="social-btn-link linkedin">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
@endif
