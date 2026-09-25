@props(['permission'])
@if ($permission)
    @if (is_array($permission))
        @if ($admin->hasAnyPermission($permission))
            {{ $slot }}
        @endif
    @else
        @if ($admin->can($permission))
            {{ $slot }}
        @endif
    @endif
@else
    {{ $slot }}
@endif