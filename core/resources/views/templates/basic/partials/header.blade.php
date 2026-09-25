<header class="header" id="header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand logo" href="{{ route('home') }}">
                <img src="{{ siteLogo('dark') }}" alt="Image">
            </a>
            <button class="navbar-toggler header-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span id="hiddenNav"><i class="las la-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu me-auto align-items-lg-center">
                    <li class="nav-item {{ menuActive('home') }} d-block d-lg-none">
                        <div class="top-button d-flex flex-wrap justify-content-between align-items-center">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                @auth
                                <a href="{{ route('user.logout') }}" class="btn btn--danger">@lang('Logout')</a>
                                    <a href="{{ route('user.home') }}" class="btn btn--base">@lang('Dashboard')</a>
                                @else
                                    <a href="{{ route('user.login') }}" class="btn btn--base-two">@lang('Login')</a>
                                    <a href="{{ route('user.register') }}" class="btn btn--base">@lang('Create Free Account')</a>
                                @endauth

                            </div>
                            @if (gs('multi_language'))
                                <div class="custom--dropdown language--dropdown">
                                    <div class="custom--dropdown__selected dropdown-list__item">
                                        <div class="icon">
                                            <img src="{{ getCurrentLangImage() }}" alt="image">
                                        </div>
                                        <span class="text">{{ strtoupper(getCurrentLang()) }}</span>
                                    </div>
                                    <ul class="dropdown-list">
                                        @foreach ($languages as $language)
                                            <li class="dropdown-list__item langSel" data-value="{{ $language->code }}">
                                                <a href="#" class="thumb">
                                                    <img src="{{ $language->image_src }}" alt="image">
                                                </a>
                                                <span class="text">{{ strtoupper($language->code) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </li>
                    <li class="nav-item {{ menuActive('home') }}">
                        <a class="nav-link" href="{{ route('home') }}">@lang('Home')</a>
                    </li>

                    <li class="nav-item {{ menuActive('features') }}">
                        <a class="nav-link" href="{{ route('features') }}">@lang('Features')</a>
                    </li>
                    <li class="nav-item {{ menuActive('pricing') }}">
                        <a class="nav-link" href="{{ route('pricing') }}">@lang('Pricing')</a>
                    </li>
                    @foreach ($pages as $page)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pages', $page->slug) }}">
                                {{ __($page->name) }}
                            </a>
                        </li>
                    @endforeach
                    <li class="nav-item {{ menuActive(['blogs', 'blog.details']) }}">
                        <a class="nav-link" href="{{ route('blogs') }}">@lang('Blog')</a>
                    </li>
                    <li class="nav-item {{ menuActive('contact') }}">
                        <a class="nav-link" href="{{ route('contact') }}">@lang('Contact')</a>
                    </li>
                </ul>
                <div class="nav-item d-lg-block d-none ms-auto">
                    <div class="top-button d-flex flex-wrap justify-content-between align-items-center">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            @auth
                                <a href="{{ route('user.home') }}" class="btn btn--base">@lang('Dashboard')</a>
                            @else
                                <a href="{{ route('user.login') }}" class="btn btn--base-two">@lang('Login')</a>
                                <a href="{{ route('user.register') }}" class="btn btn--base">@lang('Create Free Account')</a>
                            @endauth
                        </div>
                        @if (gs('multi_language'))
                            <div class="custom--dropdown language--dropdown">
                                <div class="custom--dropdown__selected dropdown-list__item">
                                    <div class="icon">
                                        <img src="{{ getCurrentLangImage() }}" alt="image">
                                    </div>
                                    <span class="text">{{ strtoupper(getCurrentLang()) }}</span>
                                </div>
                                <ul class="dropdown-list">
                                    @foreach ($languages as $language)
                                        <li class="dropdown-list__item langSel" data-value="{{ $language->code }}">
                                            <a href="#" class="thumb">
                                                <img src="{{ $language->image_src }}" alt="image">
                                            </a>
                                            <span class="text">{{ strtoupper($language->code) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>
