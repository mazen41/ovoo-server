@php
    $bgClasses = ['bg--success', 'bg--info', 'bg--warning', 'bg--danger'];
    $hash = crc32(@$contact->fullName);
    $bgClass = $bgClasses[$hash % count($bgClasses)];
    $user = auth()->user();
@endphp

@if (@$contact->image && $user->hasAgentPermission('view contact profile'))
    <div class="contact_thumb ">
        <img src="{{ @$contact->imageSrc }}" alt="Image">
    </div>
@else
    <div class="contact_thumb   {{ $bgClass }}">
        {{ __(@$contact->fullNameShortForm) }}
    </div>
@endif
