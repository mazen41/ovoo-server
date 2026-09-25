@php
    $user = auth()->user();
    $viewMobile = @$user->hasAgentPermission('view contact mobile');
@endphp
<div class="chatbox-area__left">
    <span class="close-icon">
        <i class="fas fa-times"></i>
    </span>
    <div class="chatbox-wrapper">
        <div class="chatbox-wrapper__header">
            <x-whatsapp_account :isHide="true" />
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

@push('script')
    <script>
        "use strict";
        (function($) {

            const $conversationListWrapper = $('#chat-list');
            const $messageBody = $('.msg-body');
            const $contactDetails = $('.contact__details');

            let moreConversationList = true;
            let isFetchConversation = true;
            let page = 1;
            let status = "{{ request()->status ?? 0 }}";

            window.conversation_id = "{{ $conversationId }}";
            window.whatsapp_account_id = "{{ request()->whatsapp_account_id ?? 0 }}";

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
                        whatsapp_account_id: window.whatsapp_account_id
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
                $(".empty-conversation").remove();
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
                        search
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
                            $('.contact__mobile').text(mobileNumber);
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
    </style>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush



@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
