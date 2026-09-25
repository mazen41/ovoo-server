@props(['tag' => 'a'])

@if ($tag == 'a')
    <a {{ $attributes->merge(['class' => 'btn  btn-outline--primary']) }}>
        <i class="las la-info-circle me-1"></i>@lang('Details')
    </a>
@else
    <button type="button" {{ $attributes->merge(['class' => 'btn  btn-outline--primary details-btn']) }}>
        <i class="las la-info-circle me-1"></i>@lang('Details')
    </button>
@endif
