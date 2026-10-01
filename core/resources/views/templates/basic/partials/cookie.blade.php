@php
    $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
@endphp
@if ($cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie'))
    <div class="cookies-card hide">
        <div class="cookies-card__header">
            <div class="cookies-card__icon">
                <i class="las la-cookie-bite"></i>
            </div>
        </div>
        <p class="cookies-card__content">
            {{ __($cookie->data_values->short_desc) }}
        </p>
        <div class="cookies-card__footer">
            <a href="{{ route('cookie.policy') }}" class="cookies-card__btn-outline btn btn-outline--base">@lang('View More')</a>
            <button type="button"  class="cookies-card__btn policy btn btn--base">@lang('Accept All')</button>
        </div>
    </div>
@endif
