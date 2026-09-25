<aside class="dashboard__sidebar  sidebar-menu">
    <div class="dashboard__sidebar-area">
        <div class="dashboard__sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="dashboard__sidebar-logo">
                <img class="img-fluid light-show" src="{{ siteLogo() }}">
                <img class="img-fluid dark-show" src="{{ siteLogo('dark') }}">
            </a>
            <span class="sidebar-menu__close header-dropdown__icon">
                <i class="las la-angle-double-left"></i>
            </span>
        </div>
        @php
            $routeCount = 0;
        @endphp
        <div class="dashboard__sidebar-inner">
            <ul class="dashboard-nav ps-0">
                @foreach ($menus as $k => $menu)
                    @php
                        $allPermissions = collect($menu)->pluck('permission')->filter()->toArray();
                    @endphp
                    <x-admin.permission_check :permission="array_merge(...$allPermissions)">
                        <li class="dashboard-nav__title">
                            <span class="dashboard-nav__title-text">{{ __(str_replace('_', ' ', $k)) }}</span>
                        </li>
                    </x-admin.permission_check>
                    @foreach ($menu as $parentMenu)
                        @if (@$parentMenu->submenu)
                            <x-admin.permission_check :permission="@$parentMenu->permission">
                                <li class="dashboard-nav__items has-dropdown">
                                    <a href="javascript:void(0)"
                                        class="dashboard-nav__link {{ menuActive(@$parentMenu->menu_active ?? @$parentMenu->route_name) }}">
                                        <span class="dashboard-nav__link-icon">
                                            <i class="{{ $parentMenu->icon }}"></i>
                                        </span>
                                        <span class="dashboard-nav__link-text">
                                            {{ __($parentMenu->title) }}
                                            @foreach (@$parentMenu->counters ?? [] as $counter)
                                                @if ($$counter > 0)
                                                    <span class="nav-badge text--warning fs-16">
                                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                                    </span>
                                                    @break
                                                @endif
                                            @endforeach
                                        </span>
                                    </a>
                                    <ul class="dashboard-nav sidebar-submenu">
                                        @foreach ($parentMenu->submenu as $subMenu)
                                            <x-admin.permission_check :permission="@$subMenu->permission">
                                                <li class="dashboard-nav__items">
                                                    <a href="{{ route($subMenu->route_name) }}"
                                                        class="dashboard-nav__link {{ menuActive(@$subMenu->menu_active ?? @$subMenu->route_name) }}">
                                                        <span class="dashboard-nav__link-icon"><i
                                                                class="las la-dot-circle"></i></span>
                                                        <span class="dashboard-nav__link-text">
                                                            {{ __($subMenu->title) }}
                                                            @php $counter = @$subMenu->counter; @endphp
                                                            @if (@$$counter)
                                                                <span class="nav-badge bg--dark text--white">
                                                                    {{ @$$counter }}
                                                                </span>
                                                            @endif
                                                        </span>
                                                    </a>
                                                </li>
                                            </x-admin.permission_check>
                                        @endforeach
                                    </ul>
                                </li>
                            </x-admin.permission_check>
                        @else
                            <x-admin.permission_check :permission="@$parentMenu->permission">
                                <li class="dashboard-nav__items">
                                    <a href="{{ route($parentMenu->route_name) }}"
                                        class="dashboard-nav__link {{ menuActive(@$parentMenu->menu_active ?? @$parentMenu->route_name) }}">
                                        <span class="dashboard-nav__link-icon">
                                            <i class="{{ $parentMenu->icon }}"></i>
                                        </span>
                                        <span class="dashboard-nav__link-text">{{ __($parentMenu->title) }}</span>
                                    </a>
                                </li>
                            </x-admin.permission_check>
                        @endif
                    @endforeach
                @endforeach
            </ul>
        </div>
    </div>
</aside>
