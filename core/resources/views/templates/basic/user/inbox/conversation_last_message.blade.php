@php
    $isUnread = ($message->status != Status::READ && $message->type == Status::MESSAGE_RECEIVED);
    $boldClass = $isUnread ? ' text--bold' : '';
@endphp

<div class="last-message" data-conversation-id="{{ $message->conversation_id }}">
    @if ($message->media_id)
        @if ($message->message_type === Status::VIDEO_TYPE_MESSAGE)
            <p class="text text-muted{{ $boldClass }}">
                <i class="las la-video"></i> {{ __('Video') }}
            </p>
        @elseif ($message->message_type === Status::DOCUMENT_TYPE_MESSAGE)
            <p class="text text-muted{{ $boldClass }}">
                <i class="las la-file"></i> {{ __('Document') }}
            </p>
        @elseif ($message->message_type === Status::AUDIO_TYPE_MESSAGE)
            <p class="text text-muted{{ $boldClass }}">
                <i class="las la-microphone"></i> {{ __('Audio') }}
            </p>
        @else
            <p class="text text-muted{{ $boldClass }}">
                <i class="las la-image"></i> {{ __('Photo') }}
            </p>
        @endif
    @elseif ($message->message_type === Status::URL_TYPE_MESSAGE)
        <p class="text text-muted{{ $boldClass }}">
            <i class="fa-solid fa-paperclip"></i> {{ __('Cta URL') }}
        </p>
    @elseif($message->list_reply && !empty($message->list_reply))
        <p class="text text-muted"><i class="las la-undo"></i> {{ $message->list_reply['title'] }}</p>
    @elseif ($message->message_type === Status::LIST_TYPE_MESSAGE)
        <p class="text text-muted{{ $boldClass }}">
            <i class="fa-solid fa-list"></i> {{ __('Interactive List') }}
        </p>
    @elseif ($message->message_type === Status::BUTTON_TYPE_MESSAGE)
        <p class="text text-muted{{ $boldClass }}">
            <i class="las la-undo"></i> {{ __('Reply Button') }}
        </p>
    @elseif ($message->template)
        <p class="text text-muted{{ $boldClass }}">
            <i class="las la-envelope-square"></i> {{ __('Template Message') }}
        </p>
    @else
        @php
            $shortMessage = strLimit($message->message, 15);
        @endphp

        @if ($message->message_type === Status::LOCATION_TYPE_MESSAGE)
            <p class="text text-muted{{ $boldClass }}">
                <i class="fa-solid fa-location-dot"></i> {{ __('Location') }}
            </p>
        @else
            <p class="text{{ $boldClass }}">{!! nl2br($shortMessage) !!}</p>
        @endif
    @endif
</div>
