@forelse ($conversations as $conversation)
    @php
        $unreadMessage = $conversation->unseenMessages->count();
        $lastMessage = @$conversation->lastMessage;
    @endphp

    <a class="chat-list__item {{ $activeConversationId == $conversation->id ? 'active' : '' }}"
        data-id="{{ $conversation->id }}">
        @include('Template::user.contact.thumb', ['contact' => @$conversation->contact])

        <div class="chat-list__content">
            <div class="left">
                <p class="name">{{ __(@$conversation->contact->fullName) }}</p>
                <div class="last-message">
                    @if (@$lastMessage->media_id)
                        <p class="text text-muted">
                            @if ($lastMessage->message_type == Status::VIDEO_TYPE_MESSAGE)
                                <i class="las la-video"></i> @lang('Video')
                            @elseif(@$lastMessage->message_type == Status::DOCUMENT_TYPE_MESSAGE)
                                <i class="las la-file"></i> @lang('Document')
                            @elseif(@$lastMessage->message_type == Status::AUDIO_TYPE_MESSAGE)
                                <i class="las la-microphone"></i> @lang('Audio')
                            @else
                                <i class="las la-image"></i> @lang('Photo')
                            @endif
                        </p>
                    @elseif (@$lastMessage->template)
                        <p class="last-message-text text @if ($unreadMessage > 0) text--bold @endif">
                            @lang('Template Message')
                        </p>
                    @elseif (@$lastMessage->ctaUrl)
                        <p class="text text-muted">
                            <i class="fa-solid fa-paperclip"></i> @lang('Cta URL')
                        </p>
                    @elseif(@$lastMessage->list_reply && !empty(@$lastMessage->list_reply))
                        <p class="text text-muted"><i class="las la-undo"></i>
                            {{ strLimit(@$lastMessage->list_reply['title'], 15) }}</p>
                    @elseif (@$lastMessage->interactiveList)
                        <p class="text text-muted">
                            <i class="fa-solid fa-list"></i> @lang('Interactive List')
                        </p>
                    @elseif (@$lastMessage->message_type == Status::BUTTON_TYPE_MESSAGE)
                        <p class="text text-muted">
                            <i class="las la-undo"></i> @lang('Reply Button')
                        </p>
                    @elseif (@$lastMessage->message_type == Status::URL_TYPE_MESSAGE)
                        <p class="text text-muted">
                            <i class="las la-paperclip"></i> @lang('Cta URL')
                        </p>
                    @else
                        @if (@$lastMessage->message_type == Status::LOCATION_TYPE_MESSAGE)
                            <p class="text text-muted">
                                <i class="las la-map-marker-alt"></i> @lang('Location')
                            </p>
                        @else
                            <p class="last-message-text text @if ($unreadMessage > 0) text--bold @endif">
                                {{ strLimit(@$lastMessage->message, 15) }}
                            </p>
                        @endif
                    @endif
                </div>
            </div>

            <div class="right">
                @if (@$conversation->last_message_at)
                    <p class="time last-message-at">{{ showDateTime(@$conversation->last_message_at) }}</p>
                @endif
                <div class="unseen-message">
                    @if ($unreadMessage > 0)
                        @if ($unreadMessage < 10)
                            <span class="number">{{ $unreadMessage }}</span>
                        @else
                            <span class="number">9+</span>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </a>
@empty
    <div class="empty-message text-center">
        <img src="{{ asset('assets/images/empty-con.png') }}" alt="empty">
    </div>
@endforelse
