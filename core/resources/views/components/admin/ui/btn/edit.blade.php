@props(['tag' => 'a'])

@if ($tag == 'a')
    <a {{ $attributes->merge(['class' => 'btn  btn-outline--primary']) }}>
        <i class="las la-pencil-alt me-1"></i>@lang('Edit')
    </a>
@else
    <button type="button" {{ $attributes->merge(['class' => 'btn  btn-outline--primary edit-btn']) }}>
        <i class="las la-pencil-alt me-1"></i>@lang('Edit')
    </button>
@endif
