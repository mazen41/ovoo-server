@extends('errors.app')
@section('content')
    <div class="error-content__footer">
        <p class="error-content__message">
            <span class="title">@lang('Page not found')</span>
            <span class="text">
                @lang('The page you are looking for may not exist, or an error has occurred. It might also be temporarily unavailable.')
            </span>
        </p>
        <a href="{{ route('home') }}" class="btn btn-outline--primary error-btn">
            <span class="btn--icon"><i class="fa-solid fa-house"></i></span>
            <span class="text">@lang('Back to Home')</span>
        </a>
    </div>
@endsection
