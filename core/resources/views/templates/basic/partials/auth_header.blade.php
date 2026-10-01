@php
    $user = auth()->user();
@endphp
<div class="dashboard-header">
    <div class="dashboard-header__inner flex-between">
        <div class="dashboard-header__left">
            <div class="dashboard-body__bar d-lg-none d-block">
                <span class="dashboard-body__bar-icon"><i class="fas fa-bars"></i></span>
            </div>
            <h3 class="title">{{ __($pageTitle) }}</h3>
        </div>
        <div class="user-info d-flex align-items-center gap-3">
            <div class="user-info__right">
                @if (!$user->parent_id && !userSubscriptionExpiredCheck($user))
                    <a class="btn btn--warning" href="{{ route('user.subscription.index') }}?tab=pricing-plans">
                        <i class="fas fa-crown"></i> @lang('Upgrade Now')
                    </a>
                @endif
                <div class="user-info__button">
                    <div class="user-info__thumb">
                        <img src="{{ auth()->user()->imageSrc }}" alt="image">
                    </div>
                    <div class="user-info__profile">
                        <p class="user-info__name"> {{ auth()->user()->fullname }} </p>
                        <span class="user-info__desc"> {{ showEmailAddress(auth()->user()->email) }} <span
                                class="icon"><i class="fa-solid fa-caret-down"></i></span> </span>
                    </div>
                </div>
            </div>

            <ul class="user-info-dropdown">
                <li class="user-info-dropdown__item"><a class="user-info-dropdown__link"
                        href="{{ route('user.profile.setting') }}">
                        <span class="icon"><i class="far fa-user"></i></span>
                        <span class="text">@lang('View Profile')</span>
                    </a></li>
                <li class="user-info-dropdown__item">
                    <a class="user-info-dropdown__link" href="{{ route('user.subscription.index') }}">
                        <span class="icon"> <i class="fa-solid fa-dollar-sign"></i> </span>
                        <span class="text">@lang('Subscription Info')</span>
                    </a>
                </li>
                @if (isParentUser())
                    <li class="user-info-dropdown__item">
                        <a class="user-info-dropdown__link" href="{{ route('user.whatsapp.account.index') }}">
                            <span class="icon"> <i class="fa-brands fa-whatsapp"></i> </span>
                            <span class="text">@lang('WhatsApp Account')</span>
                        </a>
                    </li>
                @endif
                <li class="user-info-dropdown__item">
                    <a class="user-info-dropdown__link" href="{{ route('user.notification.setting') }}">
                        <span class="icon"> <i class="fa-regular fa-bell-slash"></i></span>
                        <span class="text">@lang('Notification Setting')</span>
                    </a>
                </li>
                @if (isParentUser())
                    <li class="user-info-dropdown__item">
                        <a class="user-info-dropdown__link" href="{{ route('user.development.credential') }}">
                            <span class="icon"><i class="fab fa-codepen"></i></span>
                            <span class="text">@lang('Developer Tool')</span>
                        </a>
                    </li>
                    <li class="user-info-dropdown__item">
                        <a class="user-info-dropdown__link" href="{{ route('user.ip.white.list.index') }}">
                            <span class="icon"><i class="fas fa-mobile"></i></span>
                            <span class="text">@lang('IP White List')</span>
                        </a>
                    </li>
                @endif
                <li class="user-info-dropdown__item">
                    <a class="user-info-dropdown__link" href="{{ route('user.twofactor') }}">
                        <span class="icon"> <i class="fa-solid fa-shield-halved"></i> </span>
                        <span class="text">@lang('2FA Setting')</span>
                    </a>
                </li>
                <li class="user-info-dropdown__item">
                    <a class="user-info-dropdown__link text--danger" href="{{ route('user.logout') }}">
                        <span class="icon text--danger"> <i class="fa-solid fa-arrow-right-from-bracket"></i> </span>
                        <span class="text text--danger"> @lang('Sign Out') </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="dashboard-header__shape">
        <img src="{{ getImage($activeTemplateTrue . 'images/ds-1.png') }}" alt="">
    </div>
</div>
