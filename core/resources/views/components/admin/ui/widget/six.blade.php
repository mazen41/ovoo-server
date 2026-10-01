@props(['url', 'variant', 'title', 'value', 'icon'])

<div class="widget-six widget widget--{{ $variant }} flex-between">
    <a href="{{ $url }}" class="widget-card-link"></a>
    <div class="widget-six-left d-flex align-items-center gap-3">
        <div class="widget-icon">
            <i class="{{ $icon }}"></i>
        </div>
        <div class="widget-content">
            <p class="widget-title">{{ __($title) }}</p>
            <h4 class="widget-amount">
                {{ $value }}
            </h4>
        </div>
    </div>
    <span class="widget-six-arrow">
        <i class="fas fa-chevron-right"></i>
    </span>
</div>
