<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>

    <meta name="P-A-ID" content="{{ config('app.PUSHER_APP_KEY') }}">
    <meta name="P-CLUSTER" content="{{ config('app.PUSHER_APP_CLUSTER') }}">
    <meta name="APP-DOMAIN" content="{{ route('home') }}">

    @include('partials.seo')

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/api_documentation/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/api_documentation/prism/prism-okaidia.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/api_documentation/css/main.css') }}">
</head>

<body>
    <div class="sidebar-overlay"></div>
    @include('api_documentation.partials.header')
    <main>
        <div class="container">
            @include('api_documentation.partials.sidebar')
            <div class="content">
                @yield('master')
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/api_documentation/js/jquery.3.7.1.ajax.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/api_documentation/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('assets/api_documentation/prism/prism.js') }}"></script>
    <script>
        Prism = Prism || {};
        Prism.plugins = Prism.plugins || {};
        Prism.plugins.autoloader = {
            languages_path: "{{ asset('assets/api_documentation/prism/components/') }}/"
        };
    </script>
    <script src="{{ asset('assets/api_documentation/prism/plugins/autoloader/prism-autoloader.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            // sidebar toggle
            $('.menu-bar').click(function() {
                $('.sidebar').toggleClass('active');
                $('.sidebar-overlay').toggleClass('active');
            });


            // sidebar close
            $('.sidebar-overlay, .sidebar-header__close').click(function() {
                $('.sidebar').removeClass('active');
                $('.sidebar-overlay').removeClass('active');
            });


            let manualClick = false;
            let observer;

            $('.doc-link').on('click', function(e) {
                manualClick = true;

                if (observer) observer.disconnect();

                $('.doc-link').removeClass('active text-danger');
                $(this).addClass('active');

                const targetId = $(this).data('target');
                const target = document.getElementById(targetId);

                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }

                setTimeout(() => {
                    manualClick = false;
                    observeSections();
                }, 1000);
            });

            const headerHeight = $('header').outerHeight() || 0;

            function observeSections() {
                observer = new IntersectionObserver(entries => {
                    if (manualClick) return;

                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            $('.doc-link').removeClass('active text-danger');

                            const $activeLink = $('.doc-link[href="#' + entry.target.id + '"]');
                            $activeLink.addClass('active');

                        }
                    });
                }, {
                    rootMargin: `-${headerHeight + 20}px 0px -60% 0px`,
                    threshold: 0
                });

                $('.content__item').each(function() {
                    observer.observe(this);
                });
            }

            observeSections();

        });

        $(document).on('click', '.code-viewer__header-copy, .content-info-copy', function() {
            let $btn = $(this);

            if ($btn.data('copied')) return;
            let $svg = $btn.find('svg');

            if (!$btn.data('original')) {
                $btn.data('original', $svg.html());
            }

            let text = '';
            if ($btn.hasClass('code-viewer__header-copy')) {
                let parentWrapper = $btn.closest('.code-viewer');
                let codeWrapper = parentWrapper.find('pre code');
                text = codeWrapper.length ? codeWrapper.text() : parentWrapper.find('pre').text();
            } else if ($btn.hasClass('content-info-copy')) {
                let parentWrapper = $btn.closest('.content-info-api');
                let siteUrl = "{{ route('home') }}" + '/external-api';
                text = siteUrl + parentWrapper.find('.content-info-url').text();
                if (!text) return;
            }

            $btn.data('copied', true);

            navigator.clipboard.writeText(text).then(() => {
                $svg.html(`
                            <path d="M2.8 9.2L6.2 12.6L12.5 6.5"
                                stroke="currentColor"
                                stroke-width="1.6"
                                stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M2.8 9.2L6.2 12.6L12.5 6.5"
                                transform="translate(2 0)"
                                stroke="currentColor"
                                stroke-width="1.6"
                                stroke-linecap="round"
                                stroke-linejoin="round" />
                        `);

                $svg.addClass(
                    'text-emerald-500 scale-110 opacity-100 transition-all duration-200 ease-out'
                ).removeClass('opacity-70');

                setTimeout(() => {
                    $svg.html($btn.data('original')).removeClass(
                            'text-emerald-500 scale-110 transition-all duration-200 ease-out'
                        )
                        .addClass('opacity-70 transition-all duration-200 ease-in');
                    $btn.data('copied', false);
                }, 1000);
            });
        });
    </script>
</body>

</html>
