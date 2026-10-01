@props(['tag' => 'a', 'text' => 'Add New'])

@if ($tag == 'a')
    <a {{ $attributes->merge(['class' => 'btn  btn-outline--primary']) }}>
        <i class="las la-plus me-1"></i>{{ __($text) }}
    </a>
@else
    <button type="button" {{ $attributes->merge(['class' => 'btn  btn-outline--primary add-btn']) }}>
        <i class="las la-plus me-1"></i>{{ __($text) }}
    </button>
@endif
