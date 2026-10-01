@php
    $user = auth()->user();
    $viewMobile = @$user->hasAgentPermission('view contact mobile');
    $telegramIsInstalled = addonIsInstalled('tele-wpp');
@endphp
<div class="chatbox-area__left">
    <span class="close-icon">
        <i class="fas fa-times"></i>
    </span>
    <div class="chatbox-wrapper">
        <div class="chatbox-wrapper__header">
            @if (request('channel') && request('channel') == Status::CHANNEL_TELEGRAM && $telegramIsInstalled)
                @php
                    $bots = Addons\TeleWpp\Source\Models\TelegramBot::where('user_id', getParentUser()->id)->get();
                @endphp
                <div class="mb-2">
                    <select class="form--control select2 form-two" required name="bot_id" id="bot_id">
                        @foreach ($bots as $bot)
                            <option value="{{ $bot->id }}" @selected($bot->id == old('bot_id', request()->bot_id))>
                                {{ __($bot->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                <x-whatsapp_account :isHide="true" />
            @endif
            <div class="search-form">
                <input class="form--control conversation-search" name="search" type="text"
                    placeholder="@lang('Search conversation')..." autocomplete="off">
                <span class="search-form__icon"> <i class="fa-solid fa-magnifying-glass"></i> </span>
            </div>
            <div class="chatbox-wrapper__tab">
                <ul class="nav nav-pills custom--tab tab-two" id="chat-filters">
                    <li class="nav-item">
                        <button class="nav-link {{ activeClass((string) !request()->status) }}"
                            data-status="0">@lang('All')
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link {{ activeClass(request()->status == Status::UNREAD_CONVERSATION) }}"
                            data-status="{{ Status::UNREAD_CONVERSATION }}">
                            @lang('Unread')
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link {{ activeClass(request()->status == Status::PENDING_CONVERSATION) }}"
                            data-status="{{ Status::PENDING_CONVERSATION }}">
                            @lang('Pending')
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link {{ activeClass(request()->status == Status::DONE_CONVERSATION) }}"
                            data-status="{{ Status::DONE_CONVERSATION }}">
                            @lang('Done')
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link {{ activeClass(request()->status == Status::IMPORTANT_CONVERSATION) }}"
                            data-status="{{ Status::IMPORTANT_CONVERSATION }}">
                            @lang('Important')
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link {{ activeClass(request()->status == 'assigned') }}"
                            data-status="assigned">
                            @lang('Assigned')
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="chatbody">
            <div class="chat-list" id="chat-list">
            </div>
        </div>
    </div>
</div>


@include($activeTemplate . 'user.inbox.ecommerce')

<x-confirmation-modal isFrontend="true" />

<div class="image-preview-modal">
    <a class="image-preview-modal__download" href="" download>
        <i class="las la-download"></i>
    </a>
    <span class="image-preview-modal__close">&times;</span>
    <img src="" alt="">
</div>

<div class="video-preview-modal">
    <a class="video-preview-modal__download" href="" download>
        <i class="las la-download"></i>
    </a>
    <span class="video-preview-modal__close">&times;</span>
    <div class="video-preview-modal__content">
        <video controls preload="metadata"></video>
    </div>
</div>

@push('script')
    <script>
        "use strict";
        (function($) {

            const $conversationListWrapper = $('#chat-list');
            const $messageBody = $('.msg-body');
            const $contactDetails = $('.contact__details');
            const defaultImageUrl = "{{ asset('assets/images/default.png') }}";

            let moreConversationList = true;
            let isFetchConversation = true;
            let page = 1;
            let status = "{{ request()->status ?? 0 }}";

            window.conversation_id = "{{ $conversationId }}";
            window.whatsapp_account_id = "{{ request()->whatsapp_account_id ?? 0 }}";
            window.channel = "{{ request('channel') }}";
            window.telegram_bot_id = $('select[name=bot_id]').val();

            let messagePage = 1;
            let lastScrollTop = 0;
            let moreMessageList = true;
            let isFetchMessage = true;

            window.fetchChatList = function(search = '', resetPage = false) {
                if (resetPage) {
                    page = 1;
                }
                let url = "{{ route('user.inbox.conversation.list') }}";

                $.ajax({
                    url: `${url}?page=${page}`,
                    method: 'GET',
                    data: {
                        status,
                        search,
                        conversation_id: window.conversation_id,
                        whatsapp_account_id: window.whatsapp_account_id,
                        channel: window.channel,
                        bot_id: window.telegram_bot_id
                    },
                    beforeSend: function() {
                        isFetchConversation = true;
                        if (page == 1) {
                            $conversationListWrapper.html(conversationSkeleton());
                        } else {
                            $conversationListWrapper.append(conversationSkeleton());
                        }
                    },
                    success: function(response) {
                        isFetchConversation = false;
                        if (response.status == 'success') {
                            moreConversationList = response.data.more;
                            if (page > 1) {
                                $conversationListWrapper.find('.conversation-loader')
                                    .remove();
                                $conversationListWrapper.find('.empty-message').remove();
                                $conversationListWrapper.append(response.data.html);
                            } else {
                                $conversationListWrapper.html(response.data.html);
                            }
                            page++;
                        } else {
                            $conversationListWrapper.html(errorHtml());
                        }
                    },
                    error: function() {
                        isFetchConversation = false;
                        $conversationListWrapper.html(errorHtml());
                    }
                });
            }

            function conversationSkeleton() {

                let html = `<div class="conversation-loader text-center d-flex align-items-center justify-content-center flex-column  ${page==1 ? 'h-50vh' : 'my-5'}">
                    <div class="spinner-border text--base" role="status"></div>
                ${page==1 ? `<p class="fs-16 mt-1">@lang('Conversation is Loading')...</p>` : ''}
                </div>`

                return html;
            }

            function messageLoader() {
                $messageBody.addClass("h-100");
                let html = `<div class="message-loader text-center h-100 d-flex align-items-center justify-content-center flex-column py-4">
                    <div class="spinner-border text--base" role="status"></div>
                ${messagePage==1 ? `<p class="fs-16 mt-1">@lang('Message is Loading')...</p>` : ''}
                </div>`

                return html;
            }

            function contactDetailsLoader() {

                return `<div class="skeleton-wrapper">
                    <div class="skeleton skeleton-circle"></div>

                    <div class="skeleton skeleton-text skeleton-text-md"></div>

                    <div class="skeleton-buttons">
                        <div class="skeleton skeleton-btn"></div>
                        <div class="skeleton skeleton-btn"></div>
                    </div>

                    <div class="skeleton skeleton-text skeleton-text-sm"></div>
                    <div class="skeleton skeleton-text skeleton-text-sm"></div>
                    <div class="skeleton skeleton-text skeleton-text-sm"></div>
                    <div class="skeleton skeleton-text skeleton-text-sm"></div>
                    <div class="skeleton skeleton-text skeleton-text-sm"></div>
                    <div class="skeleton skeleton-text skeleton-text-sm"></div>
                    <div class="skeleton skeleton-text skeleton-text-sm"></div>
                    <div class="skeleton skeleton-text skeleton-text-sm"></div>
                    <div class="skeleton skeleton-text skeleton-text-sm"></div>
                    <div class="skeleton skeleton-text skeleton-text-sm"></div>
                    <div class="skeleton skeleton-text skeleton-text-sm"></div>
                    <div class="skeleton skeleton-text skeleton-text-sm"></div>
                </div>`
            }

            function errorHtml() {
                return ` <div class="error-message server-error-message text-center d-flex justify-content-center align-items-center flex-column gap-1 h-100">
                        <img src="{{ asset($activeTemplateTrue . 'images/server_error.png') }}" alt="empty">
                        <p class="fs-14">@lang('Something went wrong. Please try later')</p>
                    </div>`
            }

            $('.chat-list').on('scroll', function() {
                const el = this;
                if (el.scrollTop + el.clientHeight >= el.scrollHeight - 50) {
                    if (moreConversationList && !isFetchConversation) {
                        window.fetchChatList();
                    }
                }
            });

            $('.msg-body').on('scroll', function() {
                var scrollTop = $(this).scrollTop();
                if (scrollTop <= 10) {
                    if (moreMessageList && !isFetchMessage) {
                        loadMessages();
                    }
                }
            });

            $('#chat-filters').on('click', 'button', function() {
                page = 1;
                status = $(this).data('status');
                $('#chat-filters button').removeClass('active');
                $(this).addClass('active');
                window.fetchChatList();
                changeURL("status", status);
            });

            $('.conversation-search').on('keypress', function(e) {
                if (e.which === 13) {
                    let value = $(this).val();
                    page = 1;
                    window.fetchChatList(value);
                }
            });

            $('.chat-list').on('click', '.chat-list__item', function() {
                $(".empty-conversation").addClass('d-none');
                $(".chatbox-area__body").removeClass('d-none');
                window.conversation_id = $(this).data('id');
                messagePage = 1;
                loadMessages();
                loadContact();

                $('.chat-list__item').removeClass('active');
                $(this).addClass('active');
                changeURL("conversation", window.conversation_id);
                $('.chatbox-area .chatbox-area__left').removeClass('show-sidebar');
                $('.sidebar-overlay').removeClass('show');
            });

            @if (request('contact_id'))
                setTimeout(() => {
                    $(".chat-list").find('.chat-list__item.active').trigger('click');
                }, 2000);
            @endif

            function changeURL(paramsName, paramsValue) {
                const url = new URL(window.location.href);
                url.searchParams.delete('contact_id');
                if (paramsName == 'bot_id') {
                    url.searchParams.delete('conversation');
                }
                url.searchParams.set(paramsName, paramsValue);
                window.history.pushState({}, '', url);
            }

            function loadMessages(search = '', ) {

                let url = "{{ route('user.inbox.conversation.message', ':id') }}" + `?page=${messagePage}`;

                $.ajax({
                    url: url.replace(':id', window.conversation_id),
                    method: 'GET',
                    data: {
                        status,
                        search,
                        channel: window.channel,
                        bot_id: window.telegram_bot_id
                    },
                    beforeSend: function() {
                        isFetchMessage = true;
                        if (messagePage == 1) {
                            $messageBody.html(messageLoader());
                        } else {
                            $messageBody.prepend(messageLoader());
                        }
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $messageBody.find('.message-loader').remove();
                            isFetchMessage = false;
                            moreMessageList = response.data.more;
                            const contact = response?.data?.contact || {};
                            const {
                                firstname,
                                lastname,
                                mobile_code,
                                mobile,
                                image_src,
                                address
                            } = contact;

                            window.customerFirstName = firstname;
                            window.customerLastName = lastname;
                            window.customerMobileCode = mobile_code;
                            window.customerMobile = mobile;
                            window.customerCity = address?.city ?? '';
                            window.customerState = address?.state ?? '';
                            window.customerPostCode = address?.post_code ?? '';
                            window.customerAddress = address?.address ?? '';
                            window.customerCountry = address?.country ?? '';


                            const maskMobile = maskNumber(mobile_code + mobile);
                            const mobileNumber = '{{ $viewMobile }}' ? `+${mobile_code + mobile}` :
                                `${maskMobile}`;
                            $('.contact__name').text(contact.full_name);
                            $('.contact__mobile').text(mobile ? mobileNumber : "");
                            $('.contact__profile').attr('src', image_src);

                            if (messagePage == 1) {
                                $messageBody.html(response.data.html);
                                scrollToBottom($messageBody);
                            } else {
                                $messageBody.scrollTop(10);
                                $messageBody.prepend(response.data.html);
                            }

                            $("body").find('.conversation-status-dropdown').html(response.data.status_html);
                            $("body").find('.ai-status-dropdown').html(response.data.ai_reply_html);
                            $("body").find('.conversation-agent-assign-dropdown').html(response.data
                                .assign_html);

                        } else {
                            notify('error', response.message);
                            if (response.remark == 'restricted') {
                                window.location.href = "{{ route('user.inbox.list') }}";
                            }
                            $messageBody.html(errorHtml());
                        }

                        messagePage++;
                    },
                    error: function() {
                        isFetchMessage = false;
                        $messageBody.html(errorHtml());
                    }
                });
            }

            function loadContact() {
                const url = "{{ route('user.inbox.contact.details', ':id') }}";
                $.ajax({
                    url: url.replace(':id', window.conversation_id),
                    method: 'GET',
                    beforeSend: function() {
                        $contactDetails.html(contactDetailsLoader());
                    },
                    success: function(response) {


                        if (response.status == 'success') {
                            $contactDetails.html(response.data.html);
                            $(document).find('.clear_button').attr('data-action', response.data
                                .clearChatRoute);

                            if (response.data.isBlocked == '{{ Status::YES }}') {
                                $(document).find('.block-wrapper').removeClass('d-none');
                            } else {
                                $(document).find('.block-wrapper').addClass('d-none');
                            }

                            reInitTooltip();
                        } else {
                            $contactDetails.html(errorHtml());
                        }
                    },
                    error: function() {
                        $contactDetails.html(errorHtml());
                    }
                });
            }


            $('.contact__details').on('change', ".statusForm select[name=conversation_ai_reply]", function() {
                let value = $(this).val();
                let route = "{{ route('user.inbox.conversation.ai.reply', ':id') }}";
                $.post(route.replace(':id', window.conversation_id), {
                    _token: "{{ csrf_token() }}"
                }, function(data) {
                    notify(data.status, data.message);
                });
            });

            function scrollToBottom($selector) {
                setTimeout(() => {
                    $selector.scrollTop($selector[0].scrollHeight);
                }, 50);
            }

            window.fetchChatList();

            if (window.conversation_id != 0) {
                loadMessages();
                loadContact();
            }

            $('select[name=whatsapp_account_id]').on('change', function() {
                const id = $(this).val();
                const url = "{{ route('user.inbox.list') }}?whatsapp_account_id=" + id;
                window.location = url;
            });

            $('select[name=bot_id]').on('change', function() {
                window.telegram_bot_id = $(this).val();
                window.conversation_id = 0;
                page = 1;
                messagePage = 1;
                moreConversationList = true;
                moreMessageList = true;
                $(".chatbox-area__body").addClass('d-none');
                $(".empty-conversation").removeClass('d-none');
                $messageBody.html('');
                $contactDetails.html('');
                window.fetchChatList($('.conversation-search').val(), true);
                changeURL("bot_id", window.telegram_bot_id);
            });

            function maskNumber(number, visibleDigits = 2, maskChar = "*") {
                let str = number.toString();
                if (str.length <= visibleDigits) {
                    return str.padStart(visibleDigits, maskChar);
                }
                return maskChar.repeat(str.length - visibleDigits) + str.slice(-visibleDigits);
            }

            function reInitTooltip() {
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(
                    tooltipTriggerEl))
            }

            $(document).on('click', '.message-image', function() {
                const src = $(this).attr('src');
                if (!src || src === defaultImageUrl) {
                    return;
                }
                const downloadUrl = $(this).data('download-url');

                $('.image-preview-modal').addClass('show');
                $('.image-preview-modal img')
                    .attr('src', src)
                    .off('error')
                    .on('error', function() {
                        $(this).off('error').attr('src', defaultImageUrl);
                    });
                $('.image-preview-modal__download').attr('href', downloadUrl);
            });

            $(document).on('click', '.image-preview-modal__close, .image-preview-modal', function(e) {
                if (e.target === this || $(e.target).hasClass('image-preview-modal__close')) {
                    $('.image-preview-modal').removeClass('show');
                    $('.image-preview-modal img').attr('src', '');
                    $('.image-preview-modal__download').attr('href', '');
                }
            });

            $(document).on('click', '.message-video-thumb', function() {
                const videoUrl = $(this).data('video-url');
                const downloadUrl = $(this).data('download-url');
                const $modal = $('.video-preview-modal');
                $modal.find('video').attr('src', videoUrl);
                $modal.find('.video-preview-modal__download').attr('href', downloadUrl);
                $modal.addClass('show');
            });

            $(document).on('click', '.video-preview-modal__close, .video-preview-modal', function(e) {
                if (e.target === this || $(e.target).hasClass('video-preview-modal__close')) {
                    const $modal = $('.video-preview-modal');
                    const video = $modal.find('video')[0];
                    if (video) video.pause();
                    $modal.find('video').attr('src', '');
                    $modal.find('.video-preview-modal__download').attr('href', '');
                    $modal.removeClass('show');
                }
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .error-message.server-error-message img {
            max-height: 200px;
        }

        .chatbox-area__left .chatbox-wrapper__tab {
            scrollbar-width: thin;
            scrollbar-color: hsl(var(--base) / 0.8) hsl(var(--black) / 0.1);
        }

        .chatbox-area__left .chatbox-wrapper__tab::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .woo-product-list {
            position: relative;
            /* This is important so the overlay positions correctly */
            min-height: 200px;
            /* Or any height so overlay has area to cover */
        }

        .woo-overlay-loader {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            pointer-events: none;
            /* Remove d-none display:none when visible */
        }

        .message-image-wrapper {
            display: inline-block;
            max-width: 100%;
        }

        .message-image-wrapper .message-image {
            max-width: 200px;
            border-radius: 6px;
            cursor: pointer;
        }

        .image-preview-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            z-index: 99999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .image-preview-modal.show {
            display: flex;
        }

        .image-preview-modal img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 4px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
        }

        .image-preview-modal__close {
            position: absolute;
            top: 15px;
            right: 25px;
            color: #fff!important;
            font-size: 2.5rem;
            cursor: pointer;
            line-height: 1;
            opacity: 0.8;
            transition: opacity 0.2s;
            z-index: 1;
        }

        .image-preview-modal__close:hover {
            opacity: 1;
        }

        .image-preview-modal__download {
            position: absolute;
            top: 20px;
            right: 60px;
            color: #fff;
            font-size: 1.6rem;
            cursor: pointer;
            opacity: 0.8;
            transition: opacity 0.2s;
            z-index: 1;
            text-decoration: none;
        }

        .image-preview-modal__download:hover {
            opacity: 1;
            color: #fff;
        }

        .audio-message-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            max-width: 260px;
        }
        .message-audio-player {
            width: 100%;
            height: 36px;
            border-radius: 6px;
        }
        .audio-download-btn {
            font-size: 18px;
            color: hsl(var(--base));
            flex-shrink: 0;
        }
        .video-message-wrapper {
            position: relative;
            display: inline-block;
            cursor: pointer;
            max-width: 200px;
        }
        .message-video-thumb {
            max-width: 200px;
            border-radius: 6px;
            display: block;
        }
        .video-play-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 48px;
            height: 48px;
            background: rgba(0, 0, 0, 0.6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 20px;
            pointer-events: none;
        }
        .video-preview-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            z-index: 99999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .video-preview-modal.show {
            display: flex;
        }
        .video-preview-modal__close {
            position: absolute;
            top: 15px;
            right: 25px;
            color: #fff !important;
            font-size: 2.5rem;
            cursor: pointer;
            line-height: 1;
            opacity: 0.8;
            transition: opacity 0.2s;
            z-index: 1;
        }
        .video-preview-modal__close:hover {
            opacity: 1;
        }
        .video-preview-modal__download {
            position: absolute;
            top: 20px;
            right: 60px;
            color: #fff;
            font-size: 1.6rem;
            cursor: pointer;
            opacity: 0.8;
            transition: opacity 0.2s;
            z-index: 1;
            text-decoration: none;
        }
        .video-preview-modal__download:hover {
            opacity: 1;
            color: #fff;
        }
        .video-preview-modal__content video {
            max-width: 90vw;
            max-height: 85vh;
            border-radius: 4px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
        }
    </style>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush



@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
