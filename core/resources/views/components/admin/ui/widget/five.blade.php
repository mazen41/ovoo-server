@props(['url', 'variant', 'title', 'value', 'icon'])

<div class="widget-five widget widget--{{ $variant }}">
    <div class="widget-five__content">
        <p class="widget-title fs-14">
            {{ __($title) }}
        </p>
        <h4 class="widget-amount">
            {{ $value }}
        </h4>
    </div>
    <div class="widget-icon">
        <i class="{{ $icon }}"></i>
    </div>
</div>
