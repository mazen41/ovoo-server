@props(['url', 'variant', 'title', 'value', 'icon', 'isFooter' => false, 'currency' => true])

<div class="widget widget-four widget--{{ $variant }} h-100">
    <a href="{{ $url }}" class="widget-card-link"></a>
    <div class="widget-inner widget-four-shape overflow-hidden position-relative">
        <div class="mb-3 d-flex align-items-center gap-2">
            <span class="widget-icon">
                <i class="{{ $icon }}"></i>
            </span>
            <p class="widget-title">
                {{ __($title) }}
            </p>
        </div>
        <h6 class="widget-amount">
            @if ($currency)
                {{ gs('cur_sym') }}{{ showAmount($value, currencyFormat: false) }}
                <span class="currency">{{ __(gs('cur_text')) }}</span>
            @else
                <span>{{ $value }}</span>
            @endif
        </h6>
    </div>
    @if ($isFooter)
        <div class="widget-footer footer-bg-default">
            <span class="widget-footer__text">@lang('View') </span>
            <span class="widget-footer__icon"><i class="far fa-arrow-alt-circle-right"></i></span>
        </div>
    @endif
</div>
