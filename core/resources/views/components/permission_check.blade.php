@props(['permission'])
@if (is_array($permission))
    @if ($user->hasAnyAgentPermission($permission))
        {{ $slot }}
    @endif
@else
    @if ($user->hasAgentPermission($permission))
        {{ $slot }}
    @endif
@endif
