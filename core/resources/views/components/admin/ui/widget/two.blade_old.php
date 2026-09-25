@props(['url' => '', 'variant', 'title', 'value', 'icon'])


<div class="widget-two widget widget--{{ $variant }}">
    <a href="{{ $url }}" class="widget-card-link"></a>
    <div class="widget-icon">
        <i class="{{ $icon }}"></i>
    </div>
    <div class="widget-two__content">
        <p class="widget-title mb-2">
            {{ $title }}
        </p>
        <h6 class="widget-amount">
            {{ $value }}
        </h6>
    </div>
</div>
