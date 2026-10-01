@extends('errors.app')
@section('content')
    <div class="error-content__footer">
        <p class="error-content__message">
            <span class="title">@lang('Oops')! @lang('Internal server error')</span>
            <span class="text">
                @lang("We're currently working to resolve the issue. Please try again shortly.")
            </span>
        </p>
        <a href="{{ route('home') }}" class="btn btn-outline--primary error-btn">
            <span class="btn--icon"><i class="fa-solid fa-house"></i></span>
            <span class="text">@lang('Back to Home')</span>
        </a>
    </div>
@endsection
