@props(['action', 'title', 'status'])

@php
    $enableMessage = trans("Are you sure to enable this $title");
    $disableMessage = trans("Are you sure to disable this $title");
@endphp

<div class="d-flex justify-content-center">
    <div class="form-check form-switch form--switch pl-0 form-switch-success">
        <input class="form-check-input status-switch" type="checkbox" role="switch" @checked($status)
            data-action="{{ $action }}" data-message-enable="{{ $enableMessage }}"
            data-message-disable="{{ $disableMessage }}">
    </div>
</div>
