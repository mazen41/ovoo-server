@extends('install.layout')

@section('heading', 'Installation Complete')
@section('subheading', 'Your site is ready to use.')

@section('content')
    <div class="done">
        <div class="done__badge">
            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/>
            </svg>
        </div>

        <h2>{{ config('install.product_name') }} is installed</h2>
        <p>Everything is set up and your administrator account is ready. Sign in to the admin panel
            to finish configuring your site.</p>

        <div class="done__actions">
            <a class="btn btn--ghost" href="{{ url('/') }}">Visit site</a>
            <a class="btn btn--primary" href="{{ url('admin') }}">Go to admin panel</a>
        </div>
    </div>
@endsection
