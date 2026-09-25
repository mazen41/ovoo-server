<header>
    <div class="container">
        <div class="header_inner">
            <div class="menu-bar">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                    color="currentColor" fill="none">
                    <path d="M4 5L20 5" stroke="#141B34" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M4 12L20 12" stroke="#141B34" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M4 19L20 19" stroke="#141B34" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ siteLogo() }}" alt="logo">
            </a>
            <nav class="menu">
                <a target="_blank" href="{{ route('ticket.open') }}" class="menu-item logo">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 12C4 7.58 7.58 4 12 4C16.42 4 20 7.58 20 12" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" />
                        <path d="M4 12V16C4 17.1 4.9 18 6 18H7" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" />
                        <path d="M20 12V16C20 17.1 19.1 18 18 18H17" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" />
                        <path d="M7 18C7 19.66 8.34 21 10 21H14" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" />
                    </svg>
                    <span class="text">
                        @lang('Support')
                    </span>
                </a>
            </nav>
        </div>
    </div>
</header>
