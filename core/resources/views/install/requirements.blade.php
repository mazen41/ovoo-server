@extends('install.layout')

@section('heading', 'Server Requirements')
@section('subheading', 'Everything below has to pass before the installation can continue.')

@php
    $rows   = collect($rows);
    $php    = $rows->firstWhere('group', 'PHP');
    $groups = $rows->where('group', '!=', 'PHP')->groupBy('group');
    $failed = $rows->where('passed', false)->count();

    /*
     * Group keys come from Installer::checkRequirements(). Anything not listed falls back to its
     * raw key, so adding a group to config/install.php still renders sensibly.
     */
    $titles = ['Extension' => 'PHP Extensions', 'Permission' => 'Writable Paths'];
    $nouns  = ['Extension' => 'extensions', 'Permission' => 'writable paths'];
@endphp

@section('content')
    <div class="summary {{ $passed ? 'summary--ok' : 'summary--bad' }}">
        <span class="summary__icon">
            @if ($passed)
                <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/>
                </svg>
            @else
                <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 4a1 1 0 0 1 1 1v5a1 1 0 1 1-2 0V5a1 1 0 0 1 1-1Zm0 12.1a1.2 1.2 0 1 0 0-2.4 1.2 1.2 0 0 0 0 2.4Z" clip-rule="evenodd"/>
                </svg>
            @endif
        </span>

        <div>
            <p class="summary__head">
                @if ($passed)
                    Your server meets every requirement
                @else
                    {{ $failed }} {{ $failed === 1 ? 'check needs' : 'checks need' }} attention
                @endif
            </p>

            <p class="summary__meta">
                @if ($php)
                    <span class="{{ $php['passed'] ? '' : 'meta--bad' }}">PHP {{ $php['current'] }}</span>
                @endif
                @foreach ($groups as $key => $items)
                    @php $ok = $items->where('passed', true)->count(); @endphp
                    &middot;
                    <span class="{{ $ok === $items->count() ? '' : 'meta--bad' }}">
                        {{ $ok === $items->count() ? $items->count() : $ok . '/' . $items->count() }}
                        {{ $nouns[$key] ?? strtolower($key) }}
                    </span>
                @endforeach
            </p>

            @unless ($passed)
                <p class="summary__help">
                    Missing extensions are enabled by your hosting provider; permission problems are
                    usually solved by setting the listed paths to <code>755</code>. Fix the items
                    marked below, then reload this page.
                </p>
            @endunless
        </div>
    </div>

    @foreach ($groups as $key => $items)
        <div class="group">
            <p class="group__title">{{ $titles[$key] ?? $key }}</p>
            <div class="checks">
                @foreach ($items as $row)
                    <span class="check {{ $row['passed'] ? '' : 'check--bad' }}">
                        @if ($row['passed'])
                            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.3 5.3a1 1 0 0 1 1.4 0L10 8.6l3.3-3.3a1 1 0 1 1 1.4 1.4L11.4 10l3.3 3.3a1 1 0 0 1-1.4 1.4L10 11.4l-3.3 3.3a1 1 0 0 1-1.4-1.4L8.6 10 5.3 6.7a1 1 0 0 1 0-1.4Z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                        <span class="check__name">{{ $row['label'] }}</span>
                        {{-- Status is otherwise carried by colour and icon shape alone. --}}
                        <span class="sr-only">{{ $row['passed'] ? 'OK' : 'Failed' }}</span>
                    </span>
                @endforeach
            </div>
        </div>
    @endforeach

    <div class="actions actions--end">
        @if ($passed)
            <a class="btn btn--primary" href="{{ route('install.purchase') }}">
                Continue
                <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.3 4.3a1 1 0 0 1 1.4 0l5 5a1 1 0 0 1 0 1.4l-5 5a1 1 0 0 1-1.4-1.4L11.6 10 7.3 5.7a1 1 0 0 1 0-1.4Z" clip-rule="evenodd"/>
                </svg>
            </a>
        @else
            <button class="btn btn--primary" disabled>Continue</button>
        @endif
    </div>
@endsection
