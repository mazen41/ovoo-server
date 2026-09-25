@php
    $user = auth()->user();
    $whatsapp = @$user->currentWhatsapp();
@endphp

@forelse ($messages->getCollection()->sortBy('ordering') as $message)
    @include('Template::user.inbox.single_message', ['message' => $message])
@empty
    <div
        class="vh-100 d-flex flex-column justify-content-center align-items-center text-center conversation-empty-message">
        <img src="{{ asset('assets/images/no-data.gif') }}" class="empty-message">
        <span class="d-block fs-20 fw-bold">@lang('No Conversation History Found')</span>
        <span class="d-block fs-16 text-muted">@lang('There are no available data to display on this box at the moment.')</span>
    </div>
@endforelse
