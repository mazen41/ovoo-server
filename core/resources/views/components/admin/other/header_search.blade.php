<div class="header-search">
    <div class="search-card">
        <div class="search-card__body">
            <label class="search-card__label flex-align">
                <span class="search-card__icon">
                    <x-admin.svg.search />
                </span>
                <input type="search" class="form--control border-0 outline-0 " placeholder="@lang('Search')...."
                    autocomplete="false">
            </label>
            @php
                $count = 0;
            @endphp
            <ul class="search-card__list">
                @foreach ($menus as $k => $menu)
                    @foreach ($menu as $parentMenu)
                        @php
                            $showParent =
                                empty($parentMenu->permission) ||
                                count(array_intersect($parentMenu->permission, $permissions)) > 0;
                        @endphp

                        @if ($showParent)
                            @if (@$parentMenu->submenu)
                                @foreach (@$parentMenu->submenu as $subMenu)
                                    @php
                                        $showSub =
                                            empty($subMenu->permission) ||
                                            count(array_intersect($subMenu->permission, $permissions)) > 0;
                                    @endphp

                                    @if ($showSub)
                                        <li class="search-card__item" data-keyword='@json($subMenu->keyword)'>
                                            <a href="{{ route($subMenu->route_name) }}" class="search-card__link">
                                                <div class="search-card__text">
                                                    <span class="title">{{ __($subMenu->title) }}</span>
                                                    <span class="subtitle">{{ __($parentMenu->title) }}</span>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            @else
                                <li class="search-card__item" data-keyword='@json(@$parentMenu->keyword ?? [])'>
                                    <a href="{{ route($parentMenu->route_name) }}" class="search-card__link">
                                        <div class="search-card__text">
                                            <span class="title">{{ __($parentMenu->title) }}</span>
                                            <span class="subtitle">{{ __(ucwords(str_replace('_', ' ', $k))) }}</span>
                                        </div>
                                    </a>
                                </li>

                                @if ($parentMenu->title == 'Manage Sections')
                                    @foreach (getPageSections(true) as $s => $secs)
                                        @php $count++; @endphp
                                        <li class="search-card__item" data-keyword='@json(@$parentMenu->keyword ?? [])'>
                                            <a href="{{ route('admin.frontend.sections', $s) }}"
                                                class="search-card__link">
                                                <div class="search-card__text">
                                                    <span class="title">{{ __($secs['name']) }}</span>
                                                    <span class="subtitle">@lang('Manage Frontend')</span>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            @endif
                        @endif
                    @endforeach
                @endforeach
            </ul>
            <div class="search-empty-message text-center p-5 d-none">
                <img src="{{ asset('assets/images/empty_box.png') }}" class="empty-message">
                <span class="d-block">@lang('No result found')</span>
            </div>
        </div>
        <div class="search-card__footer">
            <span class="instruction">
                <span class="instruction__icon">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </span>
                <span class="instruction__text">@lang('to select')</span>
            </span>
            <span class="instruction">
                <span class="instruction__icon">
                    <i class="fa-solid fa-arrow-up"></i>
                </span>
                <span class="instruction__icon">
                    <i class="fa-solid fa-arrow-down"></i>
                </span>
                <span class="instruction__text">@lang('to navigate')</span>
            </span>
            <span class="instruction">
                <span class="instruction__icon esc-text fw-bold">
                    @lang('ESC')
                </span>
                <span class="instruction__text">@lang('to close')</span>
            </span>
        </div>
    </div>
</div>