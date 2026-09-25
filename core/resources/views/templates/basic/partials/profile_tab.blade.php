<div class="profile-page-wrapper">
    <span class="sidebar-menu__close d-md-none d-block"><i class="fas fa-times"></i></span>
    <ul class="profile-list">
        @if (isParentUser())
            <li>
                <a href="{{ route('user.whatsapp.account.index') }}"
                    class="profile-list__link {{ menuActive('user.whatsapp.account.*') }}">
                    <span class="profile-list__icon"> <i class="fa-brands fa-whatsapp"></i> </span>@lang('WhatsApp Accounts')
                </a>
            </li>
            <li>
                <a href="{{ route('user.whatsapp.webhook.config') }}"
                    class="profile-list__link {{ menuActive('user.whatsapp.webhook.config') }}">
                    <span class="profile-list__icon"> <i class="fa-brands fa-whatsapp"></i> </span>@lang('Webhook Setup')
                </a>
            </li>
            <li>
                <a href="{{ route('user.subscription.index') }}"
                    class="profile-list__link {{ menuActive('user.subscription.index') }}">
                    <span class="profile-list__icon"> <i class="fa-solid fa-dollar-sign"></i></span> @lang('Subscription')
                </a>
            </li>
        @endif
        <li>
            <a href="{{ route('user.profile.setting') }}"
                class="profile-list__link {{ menuActive('user.profile.setting') }}">
                <span class="profile-list__icon"> <i class="fas fa-user"></i> </span> @lang('My Profile')
            </a>
        </li>
        @if (isParentUser())
            <li>
                <a href="{{ route('user.development.credential') }}"
                    class="profile-list__link {{ menuActive('user.development.credential') }}">
                    <span class="profile-list__icon"> <i class="fab fa-codepen"></i> </span> @lang('Developer Tool')
                </a>
            </li>
            <li>
                <a href="{{ route('user.ip.white.list.index') }}"
                    class="profile-list__link {{ menuActive('user.ip.white.list.index') }}">
                    <span class="profile-list__icon"> <i class="fas fa-mobile"></i> </span> @lang('IP White List')
                </a>
            </li>
        @endif
        <li>
            <a href="{{ route('user.twofactor') }}" class="profile-list__link {{ menuActive('user.twofactor') }}">
                <span class="profile-list__icon"> <i class="fa-solid fa-shield-halved"></i> </span> @lang('2FA Setting')
            </a>
        </li>
        <li>
            <a href="{{ route('user.change.password') }}"
                class="profile-list__link {{ menuActive('user.change.password') }}">
                <span class="profile-list__icon"> <i class="fas fa-key"></i> </span> @lang('Change Password')
            </a>
        </li>
    </ul>
</div>
<div class="d-md-none d-flex justify-content-end">
    <span class="profile-bar-icon"> <i class="fa-solid fa-list"></i> </span>
</div>
