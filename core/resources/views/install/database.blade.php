@extends('install.layout')

@section('heading', 'Database Connection')
@section('subheading', 'Create an empty database first, then enter its details here.')

@section('content')
    <div class="note">
        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm1-11.9a1.1 1.1 0 1 1-2.2 0 1.1 1.1 0 0 1 2.2 0ZM9 9a1 1 0 0 0 0 2v3a1 1 0 0 0 1 1h1a1 1 0 1 0 0-2v-3a1 1 0 0 0-1-1H9Z" clip-rule="evenodd"/>
        </svg>
        <div>
            The database must already exist and be <strong>empty</strong>. The tables are created in
            the next step, and nothing is written to disk until these details connect successfully.
        </div>
    </div>

    <form method="POST" action="{{ route('install.database.save') }}" data-loading>
        @csrf

        <div class="row">
            <div class="field">
                <label for="db_host">Database host</label>
                <input type="text" id="db_host" name="db_host" required autocomplete="off"
                    value="{{ old('db_host', $saved['host'] ?? 'localhost') }}">
            </div>

            <div class="field">
                <label for="db_port">Port</label>
                <input type="text" id="db_port" name="db_port" required autocomplete="off"
                    value="{{ old('db_port', $saved['port'] ?? '3306') }}">
            </div>
        </div>

        <div class="field">
            <label for="db_database">Database name</label>
            <input type="text" id="db_database" name="db_database" required autocomplete="off"
                spellcheck="false" value="{{ old('db_database', $saved['database'] ?? '') }}">
        </div>

        <div class="row">
            <div class="field">
                <label for="db_username">Database username</label>
                <input type="text" id="db_username" name="db_username" required autocomplete="off"
                    spellcheck="false" value="{{ old('db_username', $saved['username'] ?? '') }}">
            </div>

            <div class="field">
                <label for="db_password">Database password</label>
                <input type="password" id="db_password" name="db_password" autocomplete="new-password">
                <small>
                    @if (!empty($saved))
                        Re-enter it to continue, or leave empty if the user has no password.
                    @else
                        Leave empty if the user has no password.
                    @endif
                </small>
            </div>
        </div>

        <div class="actions">
            @include('install.partials.back')

            <button type="submit" class="btn btn--primary" data-loading-text="Testing connection…">
                Test connection and continue
                <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.3 4.3a1 1 0 0 1 1.4 0l5 5a1 1 0 0 1 0 1.4l-5 5a1 1 0 0 1-1.4-1.4L11.6 10 7.3 5.7a1 1 0 0 1 0-1.4Z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </form>
@endsection
