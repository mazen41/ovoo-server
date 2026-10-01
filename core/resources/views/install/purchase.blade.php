@extends('install.layout')

@section('heading', 'Verify Your Purchase')
@section('subheading', 'Your purchase code confirms this copy is licensed to you.')

@section('content')
    <div class="note">
        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm1-11.9a1.1 1.1 0 1 1-2.2 0 1.1 1.1 0 0 1 2.2 0ZM9 9a1 1 0 0 0 0 2v3a1 1 0 0 0 1 1h1a1 1 0 1 0 0-2v-3a1 1 0 0 0-1-1H9Z" clip-rule="evenodd"/>
        </svg>
        <div>
            Your purchase code is on your Account Panel downloads page, under
            <strong>Download &rarr; License certificate &amp; purchase code</strong>.
        </div>
    </div>

    <form method="POST" action="{{ route('install.purchase.verify') }}" data-loading>
        @csrf

        <div class="field">
            <label for="purchase_code">Purchase Code</label>
            <input type="text" id="purchase_code" name="purchase_code" required
                autocomplete="off" spellcheck="false"
                placeholder="00000000-0000-0000-0000-000000000000">
        </div>

        <div class="field">
            <label for="envato_username">Account Username</label>
            <input type="text" id="envato_username" name="envato_username" required
                autocomplete="off" spellcheck="false"
                value="{{ old('envato_username', $savedUsername ?? '') }}">
        </div>

        <div class="actions">
            @include('install.partials.back')

            <button type="submit" class="btn btn--primary" data-loading-text="Verifying…">
                Verify and continue
                <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.3 4.3a1 1 0 0 1 1.4 0l5 5a1 1 0 0 1 0 1.4l-5 5a1 1 0 0 1-1.4-1.4L11.6 10 7.3 5.7a1 1 0 0 1 0-1.4Z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </form>
@endsection
