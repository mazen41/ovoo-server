@props(['paddingZero' => false])

<div {{ $attributes->merge(['class' => 'card-body ' . ($paddingZero ? 'p-0' : '')]) }}>
    {{ $slot }}
</div>
