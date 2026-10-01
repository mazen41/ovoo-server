<div class = "chat-box">
    <div class = "chat-box__shape">
        <img src   = "{{ getImage($activeTemplateTrue . 'images/chat-bg.png') }}" alt = "">
    </div>
    <div class="chat-box__header position-relative">
        <span class="message-inbox-btn">
            <i class="las la-angle-double-left"></i>
        </span>
        <div class="d-flex align-items-center gap-3">
            <div class="chat-box__item">
                <div class="chat-box__thumb">
                    <img class="avatar contact__profile"
                        src="{{ getImage($activeTemplateTrue . 'images/ch-1.png', isAvatar: true) }}" alt="image">
                </div>
                <div class="chat-box__content">
                    <p class="name contact__name"></p>
                    <p class="text contact__mobile"></p>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-end flex-wrap gap-2 conversation-left-side-actions">
            @if (isParentUser())
                <div class="dropdown chatbot-dropdown conversation-agent-assign-dropdown"></div>
            @endif

            <div class="dropdown chatbot-dropdown conversation-dropdown conversation-status-dropdown"></div>
            <div class="dropdown chatbot-dropdown ai-status-dropdown"></div>

            <div class="text-end d-flex justify-content-end gap-3 align-items-center">
                <span class="user-icon"><i class="fa-regular fa-user"></i></span>
            </div>
            <div class="dropdown chatbot-dropdown conversation-options">
                <button class="chatbot-dropdown__btn conversation-option-btn" type="button">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end chatbot-dropdown__menu conversation-option-list"></ul>
            </div>
        </div>
    </div>

    <div class="msg-body"></div>

    <div class="chat-box__footer">
        <div class="block-wrapper d-flex align-items-center justify-content-center mb-3 d-none">
            <div class="blocked-message px-4 py-2 d-inline-flex align-items-center">
                <i class="las la-ban me-2 fs-5"></i>
                <span>@lang('This contact has been blocked')</span>
            </div>
        </div>

        <form class="chat-send-area no-submit-loader" id="message-form">
            @csrf
            <input type="hidden" name="animation_url">
            <input type="hidden" name="picker_media_type">
            <div class="btn-group">
                <div class="chat-media">
                    <button class="chat-media__btn" type="button"><i class="las la-plus"></i></button>
                    <div class="chat-media__list">
                        @if (!request('channel') || request('channel') == Status::CHANNEL_WHATSAPP)
                            <label for="interactive_list" class="media-item interactive_list_btn">
                                <span class="icon"><i class="fa-solid fa-list"></i></span>
                                <span class="title">@lang('Interactive List')</span>
                                <input hidden class="media-input" name="interactive_list_id" type="number">
                            </label>
                            <label for="cta_url" class="media-item cta-url-btn">
                                <span class="icon"><i class="fa-solid fa-paperclip"></i></span>
                                <span class="title">@lang('CTA Url')</span>
                                <input hidden class="media-input" name="cta_url_id" type="number">
                            </label>
                        @endif

                        <label for="audio" class="media-item media_selector"
                            data-media-type="{{ Status::AUDIO_TYPE_MESSAGE }}">
                            <span class="icon"><i class="fas fa-file-audio"></i></span>
                            <span class="title">@lang('Audio')</span>
                            <input hidden class="media-input" name="audio" type="file" accept="audio/*">
                        </label>
                        <label for="audio" class="media-item voice_recorder"
                            data-media-type="{{ Status::AUDIO_TYPE_MESSAGE }}">
                            <span class="icon"><i class="fas fa-microphone"></i></span>
                            <span class="title">@lang('Voice')</span>
                            <input hidden class="media-input voice-recorder-input" name="voice_recorded_audio"
                                type="file" accept="audio/*">
                        </label>
                        <label for="document" class="media-item media_selector"
                            data-media-type="{{ Status::DOCUMENT_TYPE_MESSAGE }}">
                            <span class="icon"><i class="fas fa-file-alt"></i></span>
                            <span class="title">@lang('Document')</span>
                            <input hidden class="media-input" name="document" type="file" accept="application/pdf">
                        </label>
                        <label for="video" class="media-item media_selector"
                            data-media-type="{{ Status::VIDEO_TYPE_MESSAGE }}">
                            <span class="icon"><i class="fas fa-video"></i></span>
                            <span class="title">@lang('Video')</span>
                            <input class="media-input" name="video" type="file" accept="video/*" hidden>
                        </label>
                        @if (($channel ?? request('channel', 'whatsapp')) == 'telegram')
                            <label for="media_group" class="media-item media_selector"
                                data-media-type="{{ Status::IMAGE_TYPE_MESSAGE }}">
                                <span class="icon"><i class="fas fa-images"></i></span>
                                <span class="title">@lang('Media Group')</span>
                                <input hidden class="media-input" id="media_group" name="media_group[]"
                                    type="file" accept="image/*,video/*" multiple>
                            </label>
                        @endif
                        <label for="location" class="media-item location-modal-btn">
                            <span class="icon"><i class="fa-solid fa-location-dot"></i></span>
                            <span class="title">@lang('Location')</span>
                        </label>
                    </div>

                    <div class="chat-url__list">
                        @forelse ($ctaUrls as $url)
                            <label class="url-item select-url" data-id="{{ @$url->id }}"
                                data-name="{{ @$url->name }}" data-bs-toggle="tooltip"
                                data-bs-title="{{ @$url->cta_url }}">
                                <span class="icon"><i class="fa-solid fa-paperclip"></i></span>
                                <span class="title">{{ @$url->name }}</span>
                            </label>
                        @empty
                            <label class="url-item">
                                <span class="icon"><i class="fa-solid fa-ban"></i></span>
                                <span class="title">@lang('No CTA Link')</span>
                            </label>
                        @endforelse
                    </div>

                    <div class="chat-list__wrapper">
                        @forelse ($interactiveLists as $list)
                            <label class="url-item select-list" data-id="{{ @$list->id }}"
                                data-name="{{ @$list->name }}" data-bs-toggle="tooltip"
                                data-bs-title="{{ @$list->button_text }}">
                                <span class="icon"><i class="fa-solid fa-list"></i></span>
                                <span class="title">{{ @$list->name }}</span>
                            </label>
                        @empty
                            <label class="url-item">
                                <span class="icon"><i class="fa-solid fa-ban"></i></span>
                                <span class="title">@lang('No Interactive List')</span>
                            </label>
                        @endforelse
                    </div>
                </div>

                <label for="image" class="btn-item image-upload-btn media_selector"
                    data-media-type="{{ Status::IMAGE_TYPE_MESSAGE }}">
                    <i class="fa-solid fa-image"></i>
                    <input hidden class="image-input" name="image" type="file" accept=".jpg, .jpeg, .png">
                </label>

                @if (!request('channel') || request('channel') == Status::CHANNEL_WHATSAPP)
                    <span role="button" class="btn-item ecommerceBtn">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </span>
                @endif
            </div>

            <div class="image-preview-container"></div>
            <div class="voice-recorder-panel d-none" aria-live="polite">
                <div class="voice-recorder-panel__backdrop"></div>
                <div class="voice-recorder-panel__content">
                    <button type="button" class="voice-recorder-panel__close voice-recorder-cancel"
                        aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="voice-recorder-panel__pulse">
                        <span></span><span></span><span></span>
                    </div>
                    <div class="voice-recorder-panel__meta">
                        <p class="voice-recorder-panel__title">@lang('Recording voice message')</p>
                        <p class="voice-recorder-panel__subtitle">@lang('Tap start to begin. Stop to prepare the preview, then send it.')</p>
                    </div>
                    <div class="voice-recorder-panel__status">
                        <span class="voice-recorder-dot"></span>
                        <span class="voice-recorder-label">@lang('Ready')</span>
                        <span class="voice-recorder-timer">00:00</span>
                    </div>
                    <div class="voice-recorder-panel__wave">
                        <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                    </div>
                    <div class="voice-recorder-panel__actions">
                        <button type="button" class="btn btn--warning voice-recorder-start"><i
                                class="fas fa-circle me-1"></i>@lang('Start')</button>
                        <button type="button" class="btn btn--danger voice-recorder-stop"><i
                                class="fas fa-stop me-1"></i>@lang('Stop')</button>
                        <button type="button" class="btn btn--base voice-recorder-send"><i
                                class="fas fa-paper-plane me-1"></i>@lang('Send')</button>
                    </div>
                </div>
            </div>

            <div class="reply-to-message d-none" aria-live="polite">
                <div class="reply-to-message__content">
                    <span class="reply-to-message__title">@lang('Replying to')</span>
                    <span class="reply-to-message__text"></span>
                </div>
                <button type="button" class="reply-to-message__cancel" aria-label="@lang('Cancel reply')">
                    <i class="las la-times"></i>
                </button>
            </div>

            <div class="input-area d-flex align-center gap-2">
                <span id="togglePickerBtn" class="emoji-icon cursor-pointer"><i class="far fa-smile"></i></span>
                <div class="input-group">
                    <textarea name="message" class="form--control message-input" placeholder="@lang('Type your message here')" autocomplete="off"></textarea>
                </div>
                <button class="chating-btn" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none">
                        <path d="M22 2L15 22L11 13L2 9L22 2Z" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M22 2L11 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>


@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/esg-studio.css') }}">
@endpush

@push('style')
    <style>
        .chat-send-area {
            position: relative;
        }

        .reply-to-message {
            position: absolute;
            right: 40px;
            bottom: calc(100% + 8px);
            z-index: 5;
            display: flex;
            align-items: center;
            width: calc(100% - 245px);
            min-width: 0;
            padding: 9px 42px 9px 12px;
            overflow: hidden;
            border-radius: 8px;
            background: #fafbfb;
            color: #667781;
        }

        .reply-to-message::before {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            width: 4px;
            background: hsl(var(--base));
            content: '';
        }

        .reply-to-message__content {
            display: flex;
            min-width: 0;
            flex-direction: column;
        }

        .reply-to-message__title {
            color: hsl(var(--base)) !important;
            font-size: 13px;
            font-weight: 600;
        }

        .reply-to-message__text {
            overflow: hidden;
            font-size: 12px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .reply-to-message__cancel {
            position: absolute;
            top: 6px;
            right: 8px;
            padding: 4px;
            border: 0;
            background: transparent;
            color: #54656f;
            font-size: 20px;
            line-height: 1;
        }

        @media screen and (max-width: 1199px) {
            .reply-to-message {
                width: 100%;
            }
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/esg-studio.js') }}"></script>
@endpush


@push('script')
    <script>
        "use strict";
        (function($) {
            const inboxChannel = "{{ $channel ?? request('channel', 'whatsapp') }}";
            const emojiTrigger = document.querySelector('.emoji-icon');
            const messageInput = document.querySelector('.message-input');
            const messageForm = document.getElementById('message-form');
            const pickerMediaUrlInput = messageForm?.querySelector('input[name="animation_url"]');
            const pickerMediaTypeInput = messageForm?.querySelector('input[name="picker_media_type"]');
            const selectionShowcase = document.querySelector('.image-preview-container');
            const telegramIsInstalled = Boolean(
                "{{ addonIsInstalled('tele-wpp') && request('channel') && request('channel') == Status::CHANNEL_TELEGRAM }}"
            );

            function getSendablePickerMediaUrl(type, url) {
                if (type === 'gif') {
                    return url;
                }

                return url.replace(/\/512\.gif($|\?)/i, '/512.webp$1');
            }

            if (emojiTrigger && messageInput && window.ESGStudio) {
                emojiTrigger.addEventListener('click', function() {
                    const picker = new ESGStudio({
                        theme: 'light',
                        enableStickers: telegramIsInstalled,
                        enableGifs: telegramIsInstalled,
                        onSelect: function(type, data) {
                            if (type === 'emoji') {
                                const start = messageInput.selectionStart ?? messageInput.value
                                    .length;
                                const end = messageInput.selectionEnd ?? messageInput.value.length;

                                messageInput.value = messageInput.value.substring(0, start) + data +
                                    messageInput.value.substring(end);
                                messageInput.selectionStart = messageInput.selectionEnd = start +
                                    data.length;
                                messageInput.focus();
                            } else if (type === 'sticker' || type === 'gif') {
                                window.animation = data;
                                $(".image-preview-container").html(
                                    `<div class="preview-item image-preview"><img src="${data}"> <button class="remove-preview">×</button> </div> `
                                );
                                picker.hide();
                            } else {
                                console.error("Nothing selected");

                            }
                        }
                    });
                });
            }

            $('.conversation-dropdown').on('click', ".dropdown-item", function() {
                const value = $(this).data('value');
                const route = "{{ route('user.inbox.conversation.status', ':id') }}";

                $.ajax({
                    type: "POST",
                    url: route.replace(':id', window.conversation_id),
                    data: {
                        status: value,
                        channel: inboxChannel,
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status == 'success') {
                            $("body").find('.conversation-status-dropdown').html(response.data
                                .status_html);
                        }
                        notify(response.status, (response.message ?? 'Something went to wrong'));
                    },
                    error: function() {
                        notify('error', "@lang('Something went wrong.')");
                    }
                });
            });

            $('.ai-status-dropdown').on('click', ".ai-reply-button", function() {
                const value = $(this).data('value');
                const route = "{{ route('user.inbox.conversation.ai.reply', ':id') }}";

                $.ajax({
                    type: "POST",
                    url: route.replace(':id', window.conversation_id),
                    data: {
                        status: value,
                        channel: inboxChannel,
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status == 'success') {
                            $("body").find('.ai-status-dropdown').html(response.data.ai_reply_html);
                        }
                        notify(response.status, (response.message ?? 'Something went to wrong'));
                    },
                    error: function() {
                        notify('error', "@lang('Something went wrong.')");
                    }
                });
            });

            $('.conversation-options').on('click', ".conversation-option-btn", function() {
                const route = "{{ route('user.inbox.conversation.options', ':id') }}";

                $.ajax({
                    type: "GET",
                    url: route.replace(':id', window.conversation_id),
                    data: {
                        channel: inboxChannel
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $('.conversation-options').find(".conversation-option-list").html(
                                response.data.html);
                            $('.conversation-option-btn').dropdown('toggle');
                        } else {
                            notify(response.status, (response.message ??
                                'Something went to wrong'));
                        }
                    },
                    error: function() {
                        notify('error', "@lang('Something went wrong.')");
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
