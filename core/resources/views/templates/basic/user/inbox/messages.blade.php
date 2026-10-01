@php
    $user = auth()->user();
    $whatsapp = @$user->currentWhatsapp();
@endphp

@forelse ($messages->getCollection()->sortBy('ordering') as $message)
    @if ($channel == Status::CHANNEL_TELEGRAM)
        {!! view()->file(base_path('addons/TeleWpp/Source/resources/views/user/inbox/single_message.blade.php'), ['message' => $message])->render() !!}
    @else
        @include('Template::user.inbox.single_message', ['message' => $message])
    @endif
@empty
    <div
        class="vh-100 d-flex flex-column justify-content-center align-items-center text-center conversation-empty-message">
        <img src="{{ asset('assets/images/no-data.gif') }}" class="empty-message">
        <span class="d-block fs-20 fw-bold">@lang('No Conversation History Found')</span>
        <span class="d-block fs-16 text-muted">@lang('There are no available data to display on this box at the moment.')</span>
    </div>
@endforelse
