@props(['url', 'variant', 'title', 'value', 'icon'])

<div class="widget  widget--{{ $variant }}">
    <a href="{{ $url }}" class="widget-card-link"></a>
    <div class="widget-inner">
        <div class="widget-three__header d-flex align-items-center gap-2">
            <div class="widget-icon">
                <i class="{{ $icon }}"></i>
            </div>
            <p class="widget-title">
                {{ __($title) }}
            </p>
        </div>
        <h6 class="widget-amount">
            {{ $value }} <span class="currency fw-700">{{ __(gs('cur_text')) }}</span>
        </h6>
    </div>
    <div class="widget-footer">
        <span class="widget-footer__text">@lang('View')</span>
        <span class="widget-footer__icon"><i class="far fa-arrow-alt-circle-right"></i></span>
    </div>
</div>
