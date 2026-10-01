@extends('errors.app')
@section('content')
    <div class="error-content__footer">
        <p class="error-content__message">
            <span class="title">@lang('Session expired')</span>
            <span class="text">@lang('Please refresh your browser and try again to continue where you left off.')</span>
        </p>
        <a href="{{ route('home') }}" class="btn btn-outline--primary error-btn">
            <span class="btn--icon"><i class="fa-solid fa-house"></i></span>
            <span class="text">@lang('Back to Home')</span>
        </a>
    </div>
@endsection
