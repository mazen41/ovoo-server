<div class="sidebar-menu flex-between">
    <div class="sidebar-menu__inner">
        <span class="sidebar-menu__close d-lg-none d-block">
            <i class="fas fa-times"></i>
        </span>
        <div class="sidebar-logo">
            <a href="{{ route('home') }}" class="sidebar-logo__link">
                <img src="{{ siteLogo('dark') }}" alt="logo">
            </a>
            <a href="#" class="sidebar-logo__favicon d-none">
                <img src="{{ siteFavicon('dark') }}" alt="img">
            </a>
        </div>
        <ul class="sidebar-menu-list">
            <x-permission_check permission="view dashboard">
                <li class="sidebar-menu-list__item {{ menuActive('user.home') }}">
                    <a href="{{ route('user.home') }}" class="sidebar-menu-list__link" data-bs-toggle-custom="tooltip"
                        data-bs-placement="right" data-bs-title="@lang('Dashboard')">
                        <span class="icon">
                            <i class="fa-solid fa-border-all"></i>
                        </span>
                        <span class="text">@lang('My Dashboard')</span>
                    </a>
                </li>
            </x-permission_check>
            <x-permission_check :permission="[
                'view contact',
                'view contact list',
                'view contact tag',
                'view template',
                'view campaign',
                'view welcome message',
                'view shortlink',
                'view floater',
            ]">
                <li class="sidebar-menu-list__title">
                    <span class="text">@lang('MARKETING TOOLS')</span>
                </li>
            </x-permission_check>
            <x-permission_check :permission="['view contact', 'view contact list', 'view contact tag']">
                <li class="sidebar-menu-list__item has-dropdown" data-link="{{ route('user.contact.list') }}">
                    <a href="{{ route('user.contact.list') }}" class="sidebar-menu-list__link"
                        data-bs-toggle-custom="tooltip" data-bs-placement="right" data-bs-title="@lang('Contacts')">
                        <span class="icon">
                            <i class="fa-regular fa-id-card"></i>
                        </span>
                        <span class="text">@lang('Manage Contacts')</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul class="sidebar-submenu-list">
                            <x-permission_check permission="view contact">
                                <li class="sidebar-submenu-list__item {{ menuActive('user.contact.*') }}">
                                    <a href="{{ route('user.contact.list') }}" class="sidebar-submenu-list__link">
                                        <span class="text">@lang('Manage Contacts')</span>
                                    </a>
                                </li>
                            </x-permission_check>

                            <x-permission_check permission="view contact tag">
                                <li class="sidebar-submenu-list__item {{ menuActive('user.contacttag.*') }}">
                                    <a href="{{ route('user.contacttag.list') }}" class="sidebar-submenu-list__link">
                                        <span class="text">@lang('Manage Contact Tag')</span>
                                    </a>
                                </li>
                            </x-permission_check>

                            <x-permission_check permission="view contact list">
                                <li class="sidebar-submenu-list__item {{ menuActive('user.contactlist.*') }}">
                                    <a href="{{ route('user.contactlist.list') }}" class="sidebar-submenu-list__link">
                                        <span class="text">@lang('Manage Contact List')</span>
                                    </a>
                                </li>
                            </x-permission_check>
                        </ul>
                    </div>
                </li>
            </x-permission_check>
            <x-permission_check :permission="['view template', 'add template', 'delete template']">
                <li class="sidebar-menu-list__item has-dropdown {{ menuActive('user.template.*') }}"
                    data-link="{{ route('user.template.index') }}">
                    <a href="#" class="sidebar-menu-list__link" data-bs-toggle-custom="tooltip"
                        data-bs-placement="right" data-bs-title="@lang('Templates')">
                        <span class="icon"><i class="fa-solid fa-envelope-square"></i></span>
                        <span class="text">@lang('Manage Templates')</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul class="sidebar-submenu-list">
                            <li class="sidebar-submenu-list__item {{ menuActive('user.template.create') }}">
                                <a href="{{ route('user.template.create') }}" class="sidebar-submenu-list__link">
                                    <span class="text">@lang('New Template')</span>
                                </a>
                            </li>
                            <li class="sidebar-submenu-list__item {{ menuActive('user.template.create.carousel') }}">
                                <a href="{{ route('user.template.create.carousel') }}"
                                    class="sidebar-submenu-list__link">
                                    <span class="text">@lang('Carousel Template')</span>
                                </a>
                            </li>
                            <li class="sidebar-submenu-list__item {{ menuActive('user.template.index') }}">
                                <a href="{{ route('user.template.index') }}" class="sidebar-submenu-list__link">
                                    <span class="text">@lang('All Template')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </x-permission_check>
            <x-permission_check :permission="['view campaign', 'add campaign', 'delete campaign']">
                <li class="sidebar-menu-list__item has-dropdown {{ menuActive('user.campaign.*') }}"
                    data-link="{{ route('user.campaign.index') }}">
                    <a href="#" class="sidebar-menu-list__link" data-bs-toggle-custom="tooltip"
                        data-bs-placement="right" data-bs-title="@lang('Campaigns')">
                        <span class="icon"> <i class="fa-solid fa-volume-high"></i> </span>
                        <span class="text">@lang('Manage Campaigns')</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul class="sidebar-submenu-list">
                            <x-permission_check permission="add campaign">
                                <li class="sidebar-submenu-list__item {{ menuActive('user.campaign.create') }}">
                                    <a href="{{ route('user.campaign.create') }}" class="sidebar-submenu-list__link">
                                        <span class="text">@lang('New Campaign')</span>
                                    </a>
                                </li>
                            </x-permission_check>
                            <x-permission_check permission="view campaign">
                                <li class="sidebar-submenu-list__item {{ menuActive('user.campaign.index') }}">
                                    <a href="{{ route('user.campaign.index') }}" class="sidebar-submenu-list__link">
                                        <span class="text">@lang('All Campaign')</span>
                                    </a>
                                </li>
                            </x-permission_check>
                        </ul>
                    </div>
                </li>
            </x-permission_check>
            <x-permission_check :permission="['view welcome message', 'view flow builder']">
                <li class="sidebar-menu-list__item has-dropdown {{ menuActive(['user.automation.*', 'user.flow.builder.*']) }}"
                    data-link="{{ route('user.flow.builder.index') }}">
                    <a href="#" class="sidebar-menu-list__link" data-bs-toggle-custom="tooltip"
                        data-bs-placement="right" data-bs-title="@lang('Automation')">
                        <span class="icon"><i class="fa-solid fa-envelope-square"></i></span>
                        <span class="text">@lang('Manage Automation')</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul class="sidebar-submenu-list">
                            <x-permission_check permission="view welcome message">
                                <li
                                    class="sidebar-submenu-list__item {{ menuActive('user.automation.welcome.message') }}">
                                    <a href="{{ route('user.automation.welcome.message') }}"
                                        class="sidebar-submenu-list__link">
                                        <span class="text">@lang('Welcome Message')</span>
                                    </a>
                                </li>
                            </x-permission_check>
                            <x-permission_check permission="view flow builder">
                                <li class="sidebar-submenu-list__item {{ menuActive('user.flow.builder.index') }}">
                                    <a href="{{ route('user.flow.builder.index') }}"
                                        class="sidebar-submenu-list__link">
                                        <span class="text">@lang('Flow Builder')</span>
                                    </a>
                                </li>
                            </x-permission_check>
                            <li class="sidebar-submenu-list__item {{ menuActive('user.automation.ai.assistant') }}">
                                <a href="{{ route('user.automation.ai.assistant') }}"
                                    class="sidebar-submenu-list__link">
                                    <span class="text">@lang('AI Assistant')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </x-permission_check>
            <x-permission_check :permission="['add shortlink', 'view shortlink']">
                <li class="sidebar-menu-list__item has-dropdown {{ menuActive('user.shortlink.*') }}"
                    data-link="{{ route('user.shortlink.index') }}">
                    <a href="#" class="sidebar-menu-list__link" data-bs-toggle-custom="tooltip"
                        data-bs-placement="right" data-bs-title="@lang('ShortLink')">
                        <span class="icon"><i class="fa-solid fa-link"></i></span>
                        <span class="text">@lang('Manage ShortLink')</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul class="sidebar-submenu-list">
                            <x-permission_check permission="add shortlink">
                                <li class="sidebar-submenu-list__item {{ menuActive('user.shortlink.create') }}">
                                    <a href="{{ route('user.shortlink.create') }}"
                                        class="sidebar-submenu-list__link">
                                        <span class="text">@lang('Create ShortLink')</span>
                                    </a>
                                </li>
                            </x-permission_check>
                            <x-permission_check permission="view shortlink">
                                <li class="sidebar-submenu-list__item {{ menuActive('user.shortlink.index') }}">
                                    <a href="{{ route('user.shortlink.index') }}" class="sidebar-submenu-list__link">
                                        <span class="text">@lang('Manage ShortLink')</span>
                                    </a>
                                </li>
                            </x-permission_check>
                        </ul>
                    </div>
                </li>
            </x-permission_check>
            <x-permission_check :permission="['add floater', 'view floater']">
                <li class="sidebar-menu-list__item has-dropdown {{ menuActive('user.floater.*') }}"
                    data-link="{{ route('user.floater.index') }}">
                    <a href="#" class="sidebar-menu-list__link" data-bs-toggle-custom="tooltip"
                        data-bs-placement="right" data-bs-title="@lang('Floaters')">
                        <span class="icon">
                            <i class="fa-brands fa-whatsapp"></i>
                        </span>
                        <span class="text">@lang('Manage Floaters')</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul class="sidebar-submenu-list">
                            <x-permission_check permission="add floater">
                                <li class="sidebar-submenu-list__item {{ menuActive('user.floater.create') }}">
                                    <a href="{{ route('user.floater.create') }}" class="sidebar-submenu-list__link">
                                        <span class="text">@lang('Create Floater')</span>
                                    </a>
                                </li>
                            </x-permission_check>
                            <x-permission_check permission="view floater">
                                <li class="sidebar-submenu-list__item {{ menuActive('user.floater.index') }}">
                                    <a href="{{ route('user.floater.index') }}" class="sidebar-submenu-list__link">
                                        <span class="text">@lang('Manage Floater')</span>
                                    </a>
                                </li>
                            </x-permission_check>
                        </ul>
                    </div>
                </li>
            </x-permission_check>
            <x-permission_check :permission="['add cta url', 'view cta url']">
                <li class="sidebar-menu-list__item has-dropdown {{ menuActive('user.cta-url.*') }}"
                    data-link="{{ route('user.cta-url.index') }}">
                    <a href="#" class="sidebar-menu-list__link" data-bs-toggle-custom="tooltip"
                        data-bs-placement="right" data-bs-title="@lang('CTA URL')">
                        <span class="icon">
                            <i class="fa-solid fa-paperclip"></i>
                        </span>
                        <span class="text">@lang('Manage CTA URL')</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul class="sidebar-submenu-list">
                            <x-permission_check permission="add cta url">
                                <li class="sidebar-submenu-list__item {{ menuActive('user.cta-url.create') }}">
                                    <a href="{{ route('user.cta-url.create') }}" class="sidebar-submenu-list__link">
                                        <span class="text">@lang('Create URL')</span>
                                    </a>
                                </li>
                            </x-permission_check>
                            <x-permission_check permission="view cta url">
                                <li class="sidebar-submenu-list__item {{ menuActive('user.cta-url.index') }}">
                                    <a href="{{ route('user.cta-url.index') }}" class="sidebar-submenu-list__link">
                                        <span class="text">@lang('CTA URl List')</span>
                                    </a>
                                </li>
                            </x-permission_check>
                        </ul>
                    </div>
                </li>
            </x-permission_check>
            <x-permission_check :permission="['add interactive list', 'view interactive list']">
                <li class="sidebar-menu-list__item has-dropdown {{ menuActive('user.interactive-list.*') }}"
                    data-link="{{ route('user.interactive-list.index') }}">
                    <a href="#" class="sidebar-menu-list__link" data-bs-toggle-custom="tooltip"
                        data-bs-placement="right" data-bs-title="@lang('Interactive List')">
                        <span class="icon">
                            <i class="fa-solid fa-list"></i>
                        </span>
                        <span class="text">@lang('Manage Interactive List')</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul class="sidebar-submenu-list">
                            <x-permission_check permission="add interactive list">
                                <li
                                    class="sidebar-submenu-list__item {{ menuActive('user.interactive-list.create') }}">
                                    <a href="{{ route('user.interactive-list.create') }}"
                                        class="sidebar-submenu-list__link">
                                        <span class="text">@lang('Create List')</span>
                                    </a>
                                </li>
                            </x-permission_check>
                            <x-permission_check permission="view interactive list">
                                <li
                                    class="sidebar-submenu-list__item {{ menuActive('user.interactive-list.index') }}">
                                    <a href="{{ route('user.interactive-list.index') }}"
                                        class="sidebar-submenu-list__link">
                                        <span class="text">@lang('Interactive List')</span>
                                    </a>
                                </li>
                            </x-permission_check>
                        </ul>
                    </div>
                </li>
            </x-permission_check>

            <x-permission_check :permission="['update ecommerce configuration', 'show ecommerce products']">
                <li class="sidebar-menu-list__item has-dropdown {{ menuActive('user.ecommerce.*') }}"
                    data-link="{{ route('user.ecommerce.woocommerce.products') }}">
                    <a href="#" class="sidebar-menu-list__link" data-bs-toggle-custom="tooltip"
                        data-bs-placement="right" data-bs-title="@lang('E-Commerce')">
                        <span class="icon">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </span>
                        <span class="text">@lang('E-Commerce')</span>
                    </a>
                    <div class="sidebar-submenu submenu-child">
                        <ul class="sidebar-submenu-list">
                            <li
                                class="sidebar-menu-list__item has-dropdown {{ menuActive('user.ecommerce.woocommerce.*') }}">
                                <a href="#" class="sidebar-menu-list__link" data-bs-toggle-custom="tooltip"
                                    data-bs-placement="right"
                                    data-bs-title="This top tooltip is themed via CSS variables.">
                                    <span class="text">@lang('Woo-Commerce')</span>
                                </a>
                                <div class="sidebar-submenu child">
                                    <ul class="sidebar-submenu-list">
                                        <x-permission_check permission="show ecommerce products">
                                            <li
                                                class="sidebar-submenu-list__item {{ menuActive('user.ecommerce.woocommerce.products') }}">
                                                <a href="{{ route('user.ecommerce.woocommerce.products') }}"
                                                    class="sidebar-submenu-list__link">
                                                    <span class="text">@lang('Products')</span>
                                                </a>
                                            </li>
                                        </x-permission_check>

                                        <x-permission_check permission="update ecommerce configuration">
                                            <li
                                                class="sidebar-submenu-list__item {{ menuActive('user.ecommerce.woocommerce.config') }}">
                                                <a href="{{ route('user.ecommerce.woocommerce.config') }}"
                                                    class="sidebar-submenu-list__link">
                                                    <span class="text">@lang('Config')</span>
                                                </a>
                                            </li>
                                        </x-permission_check>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>


            </x-permission_check>


            <x-permission_check :permission="['view inbox', 'view customer', 'view agent']">
                <li class="sidebar-menu-list__title">
                    <span class="text">@lang('CRM TOOLS')</span>
                </li>
            </x-permission_check>
            <x-permission_check permission="view inbox" data-link="{{ route('user.inbox.list') }}">
                <li class="sidebar-menu-list__item">
                    <a href="{{ route('user.inbox.list') }}"
                        class="sidebar-menu-list__link {{ menuActive('user.inbox.*') }}"
                        data-bs-toggle-custom="tooltip" data-bs-placement="right" data-bs-title="@lang('Inbox')">
                        <span class="icon"> <i class="fas fa-sms"></i> </span>
                        <span class="text">@lang('Manage Inbox')</span>
                    </a>
                </li>
            </x-permission_check>
            <x-permission_check permission="view customer">
                <li class="sidebar-menu-list__item" data-link="{{ route('user.customer.list') }}">
                    <a href="{{ route('user.customer.list') }}"
                        class="sidebar-menu-list__link {{ menuActive('user.customer.*') }}"
                        data-bs-toggle-custom="tooltip" data-bs-placement="right" data-bs-title="@lang('Customer')">
                        <span class="icon"> <i class="fas fa-users"></i> </span>
                        <span class="text">@lang('Manage Customer')</span>
                    </a>
                </li>
            </x-permission_check>
            <x-permission_check permission="view agent">
                <li class="sidebar-menu-list__item" data-link="{{ route('user.agent.list') }}">
                    <a href="{{ route('user.agent.list') }}"
                        class="sidebar-menu-list__link {{ menuActive('user.agent.*') }}"
                        data-bs-toggle-custom="tooltip" data-bs-placement="right" data-bs-title="@lang('Agent')">
                        <span class="icon"> <i class="fa-solid fa-users-gear"></i> </span>
                        <span class="text">@lang('Manage Agent')</span>
                    </a>
                </li>
            </x-permission_check>
            <x-permission_check permission="view ticket" data-link="{{ route('ticket.index') }}">
                <li class="sidebar-menu-list__item">
                    <a href="{{ route('ticket.index') }}"
                        class="sidebar-menu-list__link {{ menuActive('ticket.index') }}"
                        data-bs-toggle-custom="tooltip" data-bs-placement="right" data-bs-title="@lang('Support Ticket')">
                        <span class="icon"> <i class="fa-solid fa-tags"></i> </span>
                        <span class="text">@lang('Support Ticket')</span>
                    </a>
                </li>
            </x-permission_check>

            @if (isParentUser())
                <li class="sidebar-menu-list__title">
                    <span class="text">@lang('FINANCE')</span>
                </li>
                <li class="sidebar-menu-list__item" data-link="{{ route('user.deposit.history') }}">
                    <a href="{{ route('user.deposit.history') }}"
                        class="sidebar-menu-list__link {{ menuActive('user.deposit.*') }}"
                        data-bs-toggle-custom="tooltip" data-bs-placement="right" data-bs-title="@lang('Deposit')">
                        <span class="icon"> <i class="fa-solid fa-money-bill-transfer"></i> </span>
                        <span class="text">@lang('Manage Deposit')</span>
                    </a>
                </li>
                <li class="sidebar-menu-list__item" data-link="{{ route('user.withdraw.history') }}">
                    <a href="{{ route('user.withdraw.history') }}"
                        class="sidebar-menu-list__link {{ menuActive('user.withdraw*') }}"
                        data-bs-toggle-custom="tooltip" data-bs-placement="right" data-bs-title="@lang('Withdraw')">
                        <span class="icon"> <i class="fa-solid fa-wallet"></i> </span>
                        <span class="text">@lang('Manage Withdraw')</span>
                    </a>
                </li>
                <li class="sidebar-menu-list__item" data-link="{{ route('user.transactions') }}">
                    <a href="{{ route('user.transactions') }}"
                        class="sidebar-menu-list__link {{ menuActive('user.transactions') }}"
                        data-bs-toggle-custom="tooltip" data-bs-placement="right" data-bs-title="@lang('Transactions')">
                        <span class="icon"><i class="fa-solid fa-right-left"></i></span>
                        <span class="text">@lang('Transactions Logs')</span>
                    </a>
                </li>
                <li class="sidebar-menu-list__item" data-link="{{ route('user.referral.index') }}">
                    <a href="{{ route('user.referral.index') }}"
                        class="sidebar-menu-list__link {{ menuActive('user.referral.index') }}"
                        data-bs-toggle-custom="tooltip" data-bs-placement="right" data-bs-title="@lang('Referrals')">
                        <span class="icon"> <i class="fa-solid fa-share-nodes"></i> </span>
                        <span class="text">@lang('Manage Referrals')</span>
                    </a>
                </li>
            @endif
            <li class="sidebar-menu-list__title">
                <span class="text">@lang('BILLING & PROFILE')</span>
            </li>

            @if (isParentUser())
                <li class="sidebar-menu-list__item" data-link="{{ route('user.whatsapp.account.index') }}">
                    <a href="{{ route('user.whatsapp.account.index') }}"
                        class="sidebar-menu-list__link {{ menuActive('user.whatsapp.account.*') }}"
                        data-bs-toggle-custom="tooltip" data-bs-placement="right" data-bs-title="@lang('Whatsapp Accounts')">
                        <span class="icon"> <i class="fa-solid fa-phone"></i> </span>
                        <span class="text">@lang('Whatsapp Accounts')</span>
                    </a>
                </li>
                <li class="sidebar-menu-list__item" data-link="{{ route('user.subscription.index') }}">
                    <a href="{{ route('user.subscription.index') }}"
                        class="sidebar-menu-list__link {{ menuActive('user.subscription.index') }}"
                        data-bs-toggle-custom="tooltip" data-bs-placement="right" data-bs-title="@lang('Subscription Info')">
                        <span class="icon"> <i class="fa-solid fa-dollar-sign"></i> </span>
                        <span class="text">@lang('Subscription Info')</span>
                    </a>
                </li>
            @endif
            <li class="sidebar-menu-list__item" data-link="{{ route('user.profile.setting') }}">
                <a href="{{ route('user.profile.setting') }}"
                    class="sidebar-menu-list__link {{ menuActive('user.profile.setting') }}"
                    data-bs-toggle-custom="tooltip" data-bs-placement="right" data-bs-title="@lang('Profile')">
                    <span class="icon"> <i class="fas fa-user"></i> </span>
                    <span class="text">@lang('Manage Profile')</span>
                </a>
            </li>
        </ul>
    </div>
</div>
