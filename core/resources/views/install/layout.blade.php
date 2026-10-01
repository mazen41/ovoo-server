{{--
    Standalone installer layout.

    Nothing here may touch the database: at this point there may not be one. That rules out gs(),
    activeTemplate(), the language files and the normal layouts, so the styling, the icons and the
    script are all inline. No external fonts or CDNs either, since plenty of servers install
    without outbound internet access.
--}}
@php
    $steps = [
        ['label' => 'Requirements',   'hint' => 'Server compatibility'],
        ['label' => 'Purchase',       'hint' => 'License validation'],
        ['label' => 'Database',       'hint' => 'Connection details'],
        ['label' => 'Administrator',  'hint' => 'Your login'],
    ];

    $totalSteps = count($steps);
    $isDone     = $step > $totalSteps;
    $logoUrl    = \App\Lib\Installer::logoUrl();

    // Shades are derived here rather than with CSS color-mix(), which is unsupported on older
    // browsers in a way that silently drops the whole declaration and leaves hover states blank.
    $brand     = config('install.brand_color', '#ff6200');
    [$r, $g, $b] = sscanf(ltrim($brand, '#'), '%2x%2x%2x');
    $brandDark = sprintf('#%02x%02x%02x', (int) ($r * .86), (int) ($g * .86), (int) ($b * .86));
    $brandRing = "rgba($r, $g, $b, .22)";
    $brandFaint = "rgba($r, $g, $b, .18)";
@endphp
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="color-scheme" content="light">
    <title>{{ $pageTitle }} &middot; {{ config('install.product_name') }} Setup</title>
    <style>
        :root {
            --brand: {{ $brand }};
            --brand-dark: {{ $brandDark }};
            --brand-ring: {{ $brandRing }};
            --brand-faint: {{ $brandFaint }};
            --ink: #101722;
            --text: #1d2733;
            --muted: #6b7787;
            --line: #e3e8ef;
            --field: #d4dae3;
            --ok: #157347;
            --ok-soft: #e7f5ed;
            --bad: #b42318;
            --bad-soft: #fdecea;
            --mono: ui-monospace, SFMono-Regular, "SF Mono", Menlo, Consolas, monospace;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 40px 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f6f9;
            color: var(--text);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 15px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        .shell {
            width: 100%;
            max-width: 1040px;
            display: grid;
            grid-template-columns: 296px 1fr;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(16, 24, 40, .04), 0 18px 48px -12px rgba(16, 24, 40, .16);
        }
        .link{
            color: var(--brand);
        }
        /* ---------- sidebar ---------- */

        .rail {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 40px;
            padding: 34px 28px;
            background: var(--ink);
            color: #fff;
        }

        .rail__logo img { display: block; height: 30px; width: auto; max-width: 100%; }
        .rail__logo .wordmark { font-size: 19px; font-weight: 700; letter-spacing: -.01em; }
        .rail__kicker {
            margin: 14px 0 0;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .45);
        }

        .steps { list-style: none; margin: 34px 0 0; padding: 0; }
        .steps li {
            position: relative;
            display: flex;
            align-items: center;   /* marker sits level with the middle of the two-line label */
            gap: 14px;
            padding-bottom: 26px;
        }
        .steps li:last-child { padding-bottom: 0; }

        /*
         * Connector between the markers. The label block is a deliberate 36px (20 + 16), so the
         * centred 28px marker runs from 4px to 32px: starting at 36px leaves an even 4px of air
         * under this marker and, with bottom:0, the same 4px above the next one.
         */
        .steps li:not(:last-child)::before {
            content: "";
            position: absolute;
            left: 13px;
            top: 36px;
            bottom: 0;
            width: 2px;
            background: rgba(255, 255, 255, .12);
        }
        .steps li.is-done:not(:last-child)::before { background: var(--brand); }

        .step__mark {
            position: relative;
            z-index: 1;
            flex: 0 0 28px;
            height: 28px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 14px;
            font-weight: 700;
            background: transparent;
            border: 2px solid rgba(255, 255, 255, .2);
            color: rgba(255, 255, 255, .5);
        }
        .step__mark svg { width: 15px; height: 15px; }

        .steps li.is-active .step__mark {
            background: var(--brand);
            border-color: var(--brand);
            color: #fff;
            box-shadow: 0 0 0 4px var(--brand-ring);
        }
        .steps li.is-done .step__mark { background: var(--brand); border-color: var(--brand); color: #fff; }

        /* Fixed line-heights rather than ratios, so the 36px block the connector assumes is exact. */
        .step__text { min-width: 0; }
        .step__name { display: block; font-size: 14px; font-weight: 600; line-height: 20px; color: rgba(255, 255, 255, .55); }
        .step__hint { display: block; font-size: 12px; line-height: 16px; color: rgba(255, 255, 255, .34); }
        .steps li.is-active .step__name, .steps li.is-done .step__name { color: #fff; }

        .rail__foot { margin: 0; font-size: 12px; line-height: 1.55; color: rgba(255, 255, 255, .38); }

        /* ---------- content ---------- */

        .panel { display: flex; flex-direction: column; min-width: 0; }
        .panel__head { padding: 34px 40px 22px; border-bottom: 1px solid var(--line); }
        .eyebrow {
            margin: 0 0 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--brand);
        }
        .panel__head h1 { margin: 0; font-size: 23px; font-weight: 650; letter-spacing: -.02em; color: var(--ink); }
        .lede { margin: 8px 0 0; font-size: 14px; color: var(--muted); }
        .panel__body { padding: 26px 40px 34px; }

        /* ---------- notices ---------- */

        .note {
            display: flex;
            gap: 11px;
            padding: 13px 16px;
            margin-bottom: 22px;
            border: 1px solid var(--line);
            border-left: 3px solid var(--brand);
            border-radius: 8px;
            background: #fafbfc;
            font-size: 13.5px;
            color: #46505e;
        }
        .note svg { flex: 0 0 16px; width: 16px; height: 16px; margin-top: 3px; color: var(--brand); }
        .note--error { border-color: #f3c9c4; border-left-color: var(--bad); background: var(--bad-soft); color: #7a231b; }
        .note--error svg { color: var(--bad); }
        .note ul { margin: 4px 0 0; padding-left: 18px; }

        /* ---------- requirements ---------- */

        .group { margin-bottom: 24px; }
        .group:last-of-type { margin-bottom: 0; }
        .group__title {
            margin: 0 0 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--muted);
        }
        /*
         * Twenty checks as full-width rows made this step the only one that scrolled. Chips in an
         * auto-filling grid keep every check visible in roughly a third of the height.
         */
        .checks { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 8px; }
        .check {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
            padding: 7px 11px;
            border: 1px solid var(--line);
            border-radius: 8px;
            font-size: 13px;
            color: #46505e;
        }
        .check svg { flex: 0 0 14px; width: 14px; height: 14px; color: var(--ok); }
        .check__name { min-width: 0; overflow-wrap: anywhere; }
        .check--bad { border-color: #f3c9c4; background: var(--bad-soft); color: var(--bad); font-weight: 600; }
        .check--bad svg { color: var(--bad); }

        .summary {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 16px 18px;
            margin-bottom: 26px;
            border: 1px solid;
            border-radius: 10px;
        }
        .summary--ok { border-color: #c3e3d1; background: var(--ok-soft); }
        .summary--bad { border-color: #f3c9c4; background: var(--bad-soft); }
        .summary__icon {
            flex: 0 0 34px;
            height: 34px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            color: #fff;
        }
        .summary__icon svg { width: 19px; height: 19px; }
        .summary--ok .summary__icon { background: var(--ok); }
        .summary--bad .summary__icon { background: var(--bad); }
        .summary__head { margin: 0; font-size: 15px; font-weight: 650; }
        .summary--ok .summary__head { color: #10603b; }
        .summary--bad .summary__head { color: #7a231b; }
        .summary__meta { margin: 3px 0 0; font-family: var(--mono); font-size: 12.5px; }
        .summary--ok .summary__meta { color: #2f6b4c; }
        .summary--bad .summary__meta { color: #8a4038; }
        .summary__help { margin: 10px 0 0; font-size: 13px; color: #7a231b; }
        .meta--bad { color: var(--bad); font-weight: 700; }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0 0 0 0);
            white-space: nowrap;
            border: 0;
        }

        /* ---------- forms ---------- */

        .row { display: flex; gap: 18px; flex-wrap: wrap; }
        .row > * { flex: 1 1 210px; min-width: 0; }
        .field { margin-bottom: 18px; }
        .field label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #33404f; }
        .field input {
            width: 100%;
            height: 42px;
            padding: 0 13px;
            border: 1px solid var(--field);
            border-radius: 8px;
            background: #fff;
            font-size: 14px;
            font-family: inherit;
            color: var(--text);
            transition: border-color .15s, box-shadow .15s;
        }
        .field input::placeholder { color: #a9b2be; }
        .field input:hover { border-color: #b9c2ce; }
        .field input:focus { outline: 0; border-color: var(--brand); box-shadow: 0 0 0 3px var(--brand-ring); }
        .field small { display: block; margin-top: 6px; color: var(--muted); font-size: 12px; }

        code {
            font-family: var(--mono);
            font-size: 12.5px;
            background: #eef1f5;
            padding: 1px 6px;
            border-radius: 4px;
        }

        /* ---------- buttons ---------- */

        .actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-top: 28px;
            padding-top: 22px;
            border-top: 1px solid var(--line);
        }
        .actions--end { justify-content: flex-end; }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            height: 44px;
            padding: 0 22px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
            transition: background-color .15s, border-color .15s, color .15s;
        }
        .btn svg { width: 15px; height: 15px; }
        .btn--primary { background: var(--brand); color: #fff; box-shadow: 0 1px 2px rgba(16, 24, 40, .08); }
        .btn--primary:hover { background: var(--brand-dark); }
        .btn--primary:focus-visible { outline: 0; box-shadow: 0 0 0 3px var(--brand-ring); }
        .btn--ghost { background: #fff; border-color: var(--field); color: #46505e; }
        .btn--ghost:hover { border-color: #b9c2ce; background: #f8fafc; color: var(--ink); }
        .btn[disabled], .btn.is-busy { pointer-events: none; opacity: .72; }
        .btn[disabled]:not(.is-busy) { background: #cfd6df; color: #fff; box-shadow: none; opacity: 1; }

        .spinner {
            width: 15px;
            height: 15px;
            border: 2px solid rgba(255, 255, 255, .38);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ---------- working overlay ---------- */

        .overlay {
            position: fixed;
            inset: 0;
            z-index: 60;
            display: none;
            place-items: center;
            padding: 24px;
            background: rgba(244, 246, 249, .93);
            backdrop-filter: blur(3px);
        }
        .overlay.is-on { display: grid; }
        .overlay__box { max-width: 380px; text-align: center; }
        .overlay__spinner {
            width: 42px;
            height: 42px;
            margin: 0 auto 22px;
            border: 3px solid var(--brand-faint);
            border-top-color: var(--brand);
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }
        .overlay__box h2 { margin: 0 0 6px; font-size: 18px; font-weight: 650; color: var(--ink); }
        .overlay__status { margin: 0; font-size: 14px; color: var(--muted); min-height: 22px; }
        .overlay__bar { margin: 22px auto 12px; width: 220px; height: 4px; border-radius: 999px; background: #e1e6ed; overflow: hidden; }
        .overlay__bar span { display: block; width: 40%; height: 100%; border-radius: 999px; background: var(--brand); animation: slide 1.4s ease-in-out infinite; }
        @keyframes slide { 0% { transform: translateX(-100%); } 100% { transform: translateX(250%); } }
        .overlay__warn { margin: 0; font-size: 12.5px; color: #8a94a2; }

        /* ---------- success ---------- */

        .done { text-align: center; padding: 14px 0 6px; }
        .done__badge {
            width: 66px;
            height: 66px;
            margin: 0 auto 22px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: var(--ok-soft);
            color: var(--ok);
        }
        .done__badge svg { width: 32px; height: 32px; }
        .done h2 { margin: 0 0 8px; font-size: 21px; font-weight: 650; color: var(--ink); }
        .done p { margin: 0 auto; max-width: 400px; font-size: 14.5px; color: var(--muted); }
        .done__actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; margin-top: 30px; }

        /* ---------- responsive ---------- */

        @media (max-width: 880px) {
            body { padding: 0; align-items: stretch; }
            .shell { max-width: none; grid-template-columns: 1fr; border: 0; border-radius: 0; box-shadow: none; min-height: 100vh; }
            .rail { flex-direction: row; align-items: center; justify-content: space-between; gap: 20px; padding: 20px 22px; }
            .rail__kicker, .rail__foot, .step__text { display: none; }
            .steps { display: flex; align-items: center; margin: 0; }
            .steps li { padding: 0 0 0 26px; }
            .steps li:first-child { padding-left: 0; }
            .steps li:not(:last-child)::before { display: none; }
            .steps li:not(:first-child)::after {
                content: "";
                position: absolute;
                left: 0;
                top: 13px;
                width: 26px;
                height: 2px;
                background: rgba(255, 255, 255, .12);
            }
            .steps li.is-done::after, .steps li.is-active::after { background: var(--brand); }
            .panel__head { padding: 26px 22px 18px; }
            .panel__body { padding: 22px 22px 30px; }
        }

        @media (max-width: 520px) {
            .rail__logo img { height: 24px; }
            .panel__head h1 { font-size: 20px; }
            .actions { flex-direction: column-reverse; align-items: stretch; }
            .btn { width: 100%; }
        }

        @media (prefers-reduced-motion: reduce) {
            .spinner, .overlay__spinner, .overlay__bar span { animation: none; }
        }
    </style>
</head>

<body>
    <main class="shell">
        <aside class="rail">
            <div>
                <div class="rail__logo">
                    @if ($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ config('install.product_name') }}">
                    @else
                        <div class="wordmark">{{ config('install.product_name') }}</div>
                    @endif
                </div>
                <p class="rail__kicker">Installation Wizard</p>

                <ol class="steps">
                    @foreach ($steps as $index => $item)
                        @php $number = $index + 1; @endphp
                        <li class="{{ $step == $number ? 'is-active' : ($step > $number ? 'is-done' : '') }}">
                            <span class="step__mark">
                                @if ($step > $number)
                                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    {{ $number }}
                                @endif
                            </span>
                            <span class="step__text">
                                <span class="step__name">{{ $item['label'] }}</span>
                                <span class="step__hint">{{ $item['hint'] }}</span>
                            </span>
                        </li>
                    @endforeach
                </ol>
            </div>

            <p class="rail__foot">Need a hand? Contact Support: <a href="https://ovosolution.com/get-support" class="link" target="_blank" rel="noopener noreferrer">https://ovosolution.com/get-support</a></p>
        </aside>

        <section class="panel">
            <div class="panel__head">
                <p class="eyebrow">{{ $isDone ? 'Setup complete' : 'Step ' . $step . ' of ' . $totalSteps }}</p>
                <h1>@yield('heading', $pageTitle)</h1>
                <p class="lede">@yield('subheading')</p>
            </div>

            <div class="panel__body">
                @if ($errors->any())
                    <div class="note note--error">
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm0-12a1 1 0 0 1 1 1v3.5a1 1 0 1 1-2 0V7a1 1 0 0 1 1-1Zm0 8.5a1.1 1.1 0 1 0 0-2.2 1.1 1.1 0 0 0 0 2.2Z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            @if ($errors->count() === 1)
                                {{ $errors->first() }}
                            @else
                                <strong>Please correct the following:</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </section>
    </main>

    <div class="overlay" id="overlay" role="status" aria-live="polite">
        <div class="overlay__box">
            <div class="overlay__spinner"></div>
            <h2>Setting up your site</h2>
            <p class="overlay__status" id="overlayStatus">Preparing your configuration&hellip;</p>
            <div class="overlay__bar"><span></span></div>
            <p class="overlay__warn">This can take up to a minute. Please do not close or reload this page.</p>
        </div>
    </div>

    <script>
        (function () {
            'use strict';

            var SPINNER = '<span class="spinner" aria-hidden="true"></span>';

            // The steps the final submit actually works through, in order, with the delay before
            // each one is shown. Reassurance only, so the last message simply stays put.
            var STAGES = [
                [2000,  'Creating database tables…'],
                [14000, 'Loading default data…'],
                [32000, 'Creating your administrator account…'],
                [44000, 'Finishing up…']
            ];

            var timers = [];

            function startOverlay() {
                var overlay = document.getElementById('overlay'),
                    status  = document.getElementById('overlayStatus');

                if (!overlay) return;
                overlay.classList.add('is-on');

                STAGES.forEach(function (stage) {
                    timers.push(setTimeout(function () { status.textContent = stage[1]; }, stage[0]));
                });
            }

            document.querySelectorAll('form[data-loading]').forEach(function (form) {
                form.addEventListener('submit', function () {
                    // Native validation runs first, so reaching this means the form is going.
                    if (form.dataset.submitted) return;
                    form.dataset.submitted = '1';

                    var button = form.querySelector('[type="submit"]');

                    if (button) {
                        button.classList.add('is-busy');
                        button.innerHTML = SPINNER + '<span>' +
                            (button.dataset.loadingText || 'Working…') + '</span>';

                        // Deferred: disabling a button during its own submit event can drop it
                        // from the payload in some browsers.
                        setTimeout(function () { button.disabled = true; }, 0);
                    }

                    if (form.hasAttribute('data-overlay')) startOverlay();
                });
            });

            // Returning via the browser's Back button restores the page from the cache with the
            // button still spinning, so reload it into its normal state.
            window.addEventListener('pageshow', function (event) {
                if (event.persisted) window.location.reload();
            });
        })();
    </script>
</body>

</html>
