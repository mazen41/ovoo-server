@props(['route' => '', 'class' => 'btn  btn--secondary'])

<a href="{{ $route }}" class="{{ $class }}">
    <i class="la la-undo"></i> @lang('Back')
</a>
