@props(['url', 'variant', 'title', 'value', 'icon'])
<a href="{{ $url }}">
    <div class="widget widget-one widget--{{ $variant }}">
        <span class="widget-one__arrow"><i class="fa-solid fa-arrow-right"></i></span>
        <div class="widget-one__content">
            <div class="widget-icon">
                <i class="{{ $icon }}"></i>
            </div>
            <p class="widget-title">
                {{ __($title) }}
            </p>
        </div>
        <h6 class="widget-amount">
            {{ $value }} 
        </h6>
    </div>
</a>
