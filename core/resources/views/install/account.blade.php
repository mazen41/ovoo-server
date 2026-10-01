@extends('install.layout')

@section('heading', 'Administrator Account')
@section('subheading', 'The last step. This creates the tables and your admin login.')

@section('content')
    <div class="note">
        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm1-11.9a1.1 1.1 0 1 1-2.2 0 1.1 1.1 0 0 1 2.2 0ZM9 9a1 1 0 0 0 0 2v3a1 1 0 0 0 1 1h1a1 1 0 1 0 0-2v-3a1 1 0 0 0-1-1H9Z" clip-rule="evenodd"/>
        </svg>
        <div>
            Submitting this form builds the database and can take up to a minute. Do not close or
            reload the page while it runs.
        </div>
    </div>

    <form method="POST" action="{{ route('install.finish') }}" data-loading data-overlay>
        @csrf

        <div class="field">
            <label for="name">Full name</label>
            <input type="text" id="name" name="name" required value="{{ old('name') }}">
        </div>

        <div class="row">
            <div class="field">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="off"
                    spellcheck="false" value="{{ old('username') }}">
            </div>

            <div class="field">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" required autocomplete="off"
                    spellcheck="false" value="{{ old('email') }}">
            </div>
        </div>

        <div class="row">
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="new-password">
                <small>At least 8 characters.</small>
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    autocomplete="new-password">
            </div>
        </div>

        <div class="actions">
            @include('install.partials.back')

            <button type="submit" class="btn btn--primary" data-loading-text="Installing…">
                Install now
                <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.3 4.3a1 1 0 0 1 1.4 0l5 5a1 1 0 0 1 0 1.4l-5 5a1 1 0 0 1-1.4-1.4L11.6 10 7.3 5.7a1 1 0 0 1 0-1.4Z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </form>
@endsection
