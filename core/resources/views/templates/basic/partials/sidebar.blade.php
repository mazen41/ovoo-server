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
            @foreach ($menus as $k => $menu)
                @php
                    $allPermissions = [];
                    foreach ($menu as $item) {
                        if (isset($item->permission)) {
                            $allPermissions = array_merge($allPermissions, $item->permission);
                        }
                    }
                    $allPermissions = array_unique(array_filter($allPermissions));
                @endphp

                @if ($k !== 'main' && $k !== 'BILLING & PROFILE')
                    @if (isset($menu[0]->is_parent_user) && $menu[0]->is_parent_user)
                        @if (isParentUser())
                            <li class="sidebar-menu-list__title">
                                <span class="text">@lang($k)</span>
                            </li>
                        @endif
                    @else
                        <x-permission_check :permission="$allPermissions">
                            <li class="sidebar-menu-list__title">
                                <span class="text">@lang($k)</span>
                            </li>
                        </x-permission_check>
                    @endif
                @elseif ($k === 'BILLING & PROFILE')
                    <li class="sidebar-menu-list__title">
                        <span class="text">@lang($k)</span>
                    </li>
                @endif

                @foreach ($menu as $parentMenu)
                    @if (isset($parentMenu->is_parent_user) && $parentMenu->is_parent_user && !isParentUser())
                        @continue
                    @endif

                    @if (isset($parentMenu->submenu))
                        <x-permission_check :permission="@$parentMenu->permission">
                            <li class="sidebar-menu-list__item has-dropdown {{ @$parentMenu->is_addon ? 'is-addon' : '' }} {{ menuActive(is_array(@$parentMenu->menu_active) ? @$parentMenu->menu_active : @$parentMenu->menu_active ?? @$parentMenu->route_name) }}"
                                data-link="{{ route(@$parentMenu->route_name) }}">
                                <a href="#" class="sidebar-menu-list__link" data-bs-toggle-custom="tooltip"
                                    data-bs-placement="right" data-bs-title="@lang(@$parentMenu->tooltip)">
                                    <span class="icon">
                                        <i class="{{ $parentMenu->icon }}"></i>
                                    </span>
                                    <span class="text">@lang($parentMenu->title)</span>
                                    @if (@$parentMenu->is_addon)
                                        <span class="sidebar-menu-list__item-addon">@lang('Addon')</span>
                                    @endif
                                </a>
                                <div
                                    class="sidebar-submenu{{ isset($parentMenu->is_submenu_child) && $parentMenu->is_submenu_child ? ' submenu-child' : '' }}">
                                    <ul class="sidebar-submenu-list">
                                        @foreach ($parentMenu->submenu as $subMenu)
                                            @if (isset($subMenu->submenu))
                                                <li
                                                    class="sidebar-menu-list__item has-dropdown {{ menuActive(@$subMenu->menu_active ?? @$subMenu->route_name) }}">
                                                    <a href="#" class="sidebar-menu-list__link"
                                                        data-bs-toggle-custom="tooltip" data-bs-placement="right"
                                                        @if (isset($subMenu->top_tooltip) && $subMenu->top_tooltip) data-bs-title="@lang(@$subMenu->tooltip)" @endif>
                                                        <span class="text">@lang($subMenu->title)</span>
                                                    </a>
                                                    <div class="sidebar-submenu child">
                                                        <ul class="sidebar-submenu-list">
                                                            @foreach ($subMenu->submenu as $childMenu)
                                                                <x-permission_check :permission="@$childMenu->permission">
                                                                    <li
                                                                        class="sidebar-submenu-list__item {{ menuActive(@$childMenu->menu_active ?? @$childMenu->route_name) }}">
                                                                        <a href="{{ route($childMenu->route_name) }}"
                                                                            class="sidebar-submenu-list__link">
                                                                            <span
                                                                                class="text">@lang($childMenu->title)</span>
                                                                        </a>
                                                                    </li>
                                                                </x-permission_check>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </li>
                                            @else
                                                @php
                                                    @$subMenu = (object) $subMenu;
                                                @endphp
                                                <x-permission_check :permission="@$subMenu->permission">
                                                    <li
                                                        class="sidebar-submenu-list__item {{ menuActive(@$subMenu->menu_active ?? @$subMenu->route_name) }}">
                                                        <a href="{{ route($subMenu->route_name) }}"
                                                            class="sidebar-submenu-list__link">
                                                            <span class="text">@lang($subMenu->title)</span>
                                                        </a>
                                                    </li>
                                                </x-permission_check>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        </x-permission_check>
                    @else
                        <x-permission_check :permission="@$parentMenu->permission">
                            <li class="sidebar-menu-list__item {{ @$parentMenu->is_addon ? 'is-addon' : '' }} {{ menuActive(is_array(@$parentMenu->menu_active) ? @$parentMenu->menu_active : @$parentMenu->menu_active ?? @$parentMenu->route_name) }}"
                                data-link="{{ route(@$parentMenu->route_name) }}">
                                <a href="{{ route(@$parentMenu->route_name) }}"
                                    class="sidebar-menu-list__link {{ menuActive(is_array(@$parentMenu->menu_active) ? @$parentMenu->menu_active : @$parentMenu->menu_active ?? @$parentMenu->route_name) }}"
                                    data-bs-toggle-custom="tooltip" data-bs-placement="right"
                                    data-bs-title="@lang(@$parentMenu->tooltip)">
                                    <span class="icon"> <i class="{{ $parentMenu->icon }}"></i> </span>
                                    <span class="text">@lang($parentMenu->title)</span>
                                    @if (@$parentMenu->is_addon)
                                        <span class="sidebar-menu-list__item-addon">@lang('Addon')</span>
                                    @endif
                                </a>
                            </li>
                        </x-permission_check>
                    @endif
                @endforeach
            @endforeach
        </ul>
    </div>
</div>
