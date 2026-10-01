@php
    $selectedConversationId = request()->conversation ?? 0;
    $teleGramInstalled = addonIsInstalled('tele-wpp');
@endphp

@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="text-end d-flex justify-content-start gap-3 align-items-center">
        <span class="filter-icon"> <i class="fas fa-stream"></i> </span>
    </div>
    <div class="chatbox-area">
        @include('Template::user.inbox.conversation')
        <div class="chatbox-area__body @if (!$selectedConversationId) d-none @endif">
            @include('Template::user.inbox.message_box')
            @include('Template::user.inbox.contact')
        </div>
        <div class="empty-conversation @if ($selectedConversationId) d-none @endif">
            <img class="conversation-empty-image" src="{{ asset($activeTemplateTrue . 'images/conversation_empty.png') }}"
                alt="img">
        </div>
    </div>
    <div class="modal custom--modal fade templateModal progressModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div id="import-progress" class="progress  h-1 w-full d-none">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated h-full w-0"></div>
                </div>
                <div class="modal-header">
                    <div class="modal-header__left">
                        <h5 class="modal-title">@lang('Send Message Template')</h5>
                        <p class="modal-subtitle">@lang('Choose a message template to send to your contacts.')</p>
                    </div>
                    <div class="modal-header__right">
                        <div class="btn--group">
                            <button class="btn--white btn" data-bs-dismiss="modal"
                                aria-label="Close">@lang('Cancel')</button>
                            <button class="btn--base btn btn-shadow" form="template-form"><i class="lab la-telegram"></i>
                                @lang('Send')</button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="csv-form-wrapper">
                        <div class="csv-form-wrapper__left">
                            <form action="{{ route('user.inbox.message.template.send') }}" method="POST"
                                id="template-form">
                                @csrf
                                <div class="thumb-form-wrapper">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Template')</label>
                                        <select class="form--control select2" name="template_id"
                                            data-minimum-results-for-search="-1" required>
                                            @forelse ($templates as $template)
                                                <option value="{{ $template->id }}"
                                                    data-template-header="{{ json_encode($template->header) }}"
                                                    data-cards="{{ $template->cards }}"
                                                    data-template-body="{{ $template->body }}"
                                                    data-template-footer="{{ $template->footer }}"
                                                    data-header-format="{{ $template->header_format }}"
                                                    data-category="{{ strtoupper($template->category?->name ?? '') }}"
                                                    data-buttons="{{ json_encode($template->buttons) }}"
                                                    data-header-media="{{ asset(getFilePath('templateHeader')) . '/' . $template->header_media }}">
                                                    {{ __($template->name) }}</option>
                                            @empty
                                                <option value="">@lang('No template found')</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 header-variable-area d-none">
                                    <div class="row justify-content-center">
                                        <div class="col-12">
                                            <div class="auth-devider text-center">
                                                <span> @lang('HEADER VARIABLES')</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="template-header-variables"></div>
                                </div>
                                <div class="col-12 body-variable-area d-none">
                                    <div class="row justify-content-center">
                                        <div class="col-12">
                                            <div class="auth-devider text-center">
                                                <span> @lang('BODY VARIABLES')</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="template-body-variables"></div>
                                </div>
                                <div class="col-12 button-variable-area d-none">
                                    <div class="row justify-content-center">
                                        <div class="col-12">
                                            <div class="auth-devider text-center">
                                                <span> @lang('BUTTON VARIABLES')</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="template-button-variables"></div>
                                </div>
                                <div class="col-12 d-none otp-template-note">
                                    <p class="text--small text-muted">
                                        @lang('The verification code you enter above is also sent to the template button, as WhatsApp requires both values to match.')
                                    </p>
                                </div>
                                <div class="col-12 d-none variable-code-wrapper">
                                    @foreach (variableShortCodes() as $key => $value)
                                        <span class="btn btn--sm btn--dark code-btn"
                                            data-code="{{ $value }}">{{ $value }}</span>
                                    @endforeach
                                </div>
                            </form>

                        </div>
                        <div class="template-info-container__right">
                            <div class="preview-item">
                                <div class="preview-item__header">
                                    <h5 class="preview-item__title">@lang('Template Preview')</h5>
                                </div>
                                <div class="preview-item__content">
                                    <div class="preview-item__shape">
                                        <img src="{{ getImage($activeTemplateTrue . 'images/preview-1.png') }}"
                                            alt="image">
                                    </div>
                                    <div class="card-item">
                                        <div class="card-item__thumb header_media">
                                            <img src="{{ getImage($activeTemplateTrue . 'images/preview-1.png') }}"
                                                alt="image">
                                        </div>
                                        <div class="card-item__content">
                                            <p class="card-item__title header_text">@lang('Template header')</p>
                                            <p class="card-item__desc body_text">@lang('Template body')</p>
                                            <p class="text-wrapper">
                                                <span class="text footer_text">@lang('Footer text')</span>
                                            </p>
                                        </div>
                                        <div class="button-preview mt-2 d-flex gap-2 flex-column"></div>
                                    </div>
                                    <div class="carousel-cards overflow-auto mt-1 d-flex gap-2 align-items-center d-none">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom--modal fade locationModal progressModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div id="import-progress" class="progress  h-1 w-full d-none">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated h-full w-0"></div>
                </div>
                <div class="modal-header">
                    <div class="modal-header__left">
                        <h5 class="modal-title">@lang('Send Location Message')</h5>
                        <p class="modal-subtitle">@lang('Select location via google map or enter latitude and longitude.')</p>
                    </div>
                    <div class="modal-header__right">
                        <div class="btn--group">
                            <button class="btn--white btn" data-bs-dismiss="modal"
                                aria-label="Close">@lang('Cancel')</button>
                            <button class="btn--base btn btn-shadow submitBtn" form="location-form"><i
                                    class="lab la-telegram"></i>
                                @lang('Send')</button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.inbox.message.send') }}" method="POST" id="location-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="label-two">@lang('Latitude')</label>
                                    <input type="text" class="form--control form-two" name="latitude"
                                        placeholder="@lang('Ex:35.74186524893564')" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="label-two">@lang('Longitude')</label>
                                    <input type="text" class="form--control form-two" name="longitude"
                                        placeholder="@lang('EX:-139.2120532811909')" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="label-two">@lang('Name')</label>
                                    <input type="text" class="form--control form-two" name="name"
                                        placeholder="@lang('Ex:Company 12, New York')" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="label-two">@lang('Address')</label>
                                    <input type="text" class="form--control form-two" name="address"
                                        placeholder="@lang('EX:Street 12, Delta, New York, United States')">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="label-two">@lang('Select Location via MAP')</label>
                                    <input class="controls" id="searchBox" type="text"
                                        placeholder="@lang('Search Here')">
                                    <div class="google-map" @if (gs('google_maps_api')) style="height: 400px" @endif
                                        id="map"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade custom--modal listMessageModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times fs-18"></i>
                        </button>
                        <h5 class="modal-title">@lang('Message Preview')</h5>
                    </div>
                </div>
                <div class="modal-body px-2 py-0">
                    <div class="message-preview-list">
                        <div class="message-preview-list__body">
                            <div class="message-preview-list__section_wrapper">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal isFrontend="true" />
@endsection

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/pusher.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/broadcasting.js') }}?v=2.4"></script>
    @if ($teleGramInstalled)
        <script src="{{ asset($activeTemplateTrue . 'js/lottie-player.js') }}"></script>
    @endif
    @if (gs('google_maps_api'))
        <script
            src="https://maps.googleapis.com/maps/api/js?key={{ gs('google_maps_api') }}&libraries=drawing,places,marker&v=3.45.8">
        </script>
    @endif
@endpush


@if ($teleGramInstalled)
    @push('topbar_tabs')
        <div class="profile-page-wrapper inbox-channel-tabs">
            <ul class="profile-list">
                <li>
                    <a href="{{ route('user.inbox.list') }}"
                        class="profile-list__link {{ !request('channel') ? 'active' : '' }}">
                        <span class="profile-list__icon"> <i class="fa-brands fa-whatsapp"></i> </span>@lang('WhatsApp')
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.inbox.list') }}?channel={{ Status::CHANNEL_TELEGRAM }}"
                        class="profile-list__link {{ request('channel') == Status::CHANNEL_TELEGRAM ? 'active' : '' }}">
                        <span class="profile-list__icon"> <i class="fa-brands fa-telegram"></i> </span>@lang('Telegram')
                    </a>
                </li>
            </ul>
        </div>
    @endpush
    @push('style')
        <style>
            .dashboard-body:has(.chatbox-area) {
                height: calc(100vh - 136px) !important;
            }
        </style>
    @endpush
@endif


@include('Template::user.inbox.audio_recorder')

@push('script')
    <script>
        (function($) {
            "use strict";

            const $messageBody = $('.msg-body');
            const $messageForm = $('#message-form');
            const $locationModal = $('.locationModal');
            const $listMessageModal = $('.listMessageModal');
            const $locationForm = $('#location-form');

            const $imageInput = $(".image-input");
            const $documentInput = $(".media-item input[name='document']");
            const $videoInput = $(".media-item input[name='video']");
            const $audioInput = $(".media-item.media_selector input[name='audio']");
            const $voiceRecorderInput = $(".voice-recorder-input");
            const $urlInput = $('input[name=cta_url_id]');
            const $listInput = $('input[name=interactive_list_id]');
            const $previewContainer = $(".image-preview-container");
            const $messageInput = $(".message-input");
            const $replyToMessage = $('.reply-to-message');
            const $replyToMessageTitle = $('.reply-to-message__title');
            const $replyToMessageText = $('.reply-to-message__text');
            const $voiceRecorderPanel = $('.voice-recorder-panel');
            const $voiceRecorderTimer = $('.voice-recorder-timer');
            const $voiceRecorderLabel = $('.voice-recorder-label');
            const $voiceRecorderWave = $('.voice-recorder-panel__wave');
            const $voiceRecorderStart = $('.voice-recorder-start');
            const $voiceRecorderStop = $('.voice-recorder-stop');
            const $voiceRecorderSend = $('.voice-recorder-send');
            const $voiceRecorderCancel = $('.voice-recorder-cancel');
            window.wooCommerceProduct = null;
            window.createdOrderData = null;
            window.replyTo = null;

            let isSubmitting = false;
            let mediaRecorder = null;
            let recordingStream = null;
            let recordingChunks = [];
            let recordingTimerInterval = null;
            let recordingStopTimeout = null;
            let recordingSeconds = 0;
            const maxRecordingSeconds = 180;
            let recordingShouldAutoSend = false;
            let recordingWasCancelled = false;

            function handleFormSubmit($form, url) {
                $form.on('submit', function(e) {
                    e.preventDefault();

                    if (isSubmitting) return;
                    isSubmitting = true;

                    const formData = new FormData(this);
                    const $submitBtn = $form.find('button[type=submit]');
                    const voiceRecordedFile = $voiceRecorderInput[0]?.files?.[0];

                    if (voiceRecordedFile instanceof File) {
                        formData.set('audio', voiceRecordedFile);
                    }

                    formData.delete('voice_recorded_audio');

                    formData.append('conversation_id', window.conversation_id);
                    formData.append('whatsapp_account_id', window.whatsapp_account_id);
                    formData.append('channel', window.channel);
                    formData.append('animation_url', window?.animation || '');
                    formData.append('bot_id', $("#bot_id").val());

                    if (window.replyTo?.wa_message_id) {
                        formData.append('wa_message_id', window.replyTo.wa_message_id);
                    }

                    if (wooCommerceProduct !== null) {
                        formData.append('product', JSON.stringify(wooCommerceProduct));
                    }

                    if (createdOrderData != null) {
                        formData.append('created_order_data', JSON.stringify(createdOrderData));
                    }

                    $.ajax({
                        url: url,
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            $submitBtn.attr('disabled', true).addClass('disabled');
                            $submitBtn.html(
                                `<div class="spinner-border text--base" role="status"></div>`);
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                $form.trigger('reset');
                                window.animation = "";
                                window.clearReplyTo();
                                $messageBody.append(response.data.html);
                                if (response.data.conversationId && response.data.lastMessageHtml) {
                                    $(`.chat-list__item[data-id="${response.data.conversationId}"]`)
                                        .find('.last-message').html(response.data.lastMessageHtml);
                                }
                                $('body').find(".conversation-empty-message").remove()

                                setTimeout(() => {
                                    $messageBody.scrollTop($messageBody[0].scrollHeight);
                                }, 50);
                            } else {
                                notify('error', response.message || "@lang('Something went to wrong')");
                            }
                        },
                        complete: function() {
                            isSubmitting = false;
                            $submitBtn.attr('disabled', false).removeClass('disabled');
                            $submitBtn.html(messageSendSvg());
                            $urlInput.val('');
                            $listInput.val('');
                            $('.message-input').attr('readonly', false);
                            clearImagePreview();
                            $('.chat-url__list').removeClass('show');
                            $('.chat-list__wrapper').removeClass('show');
                            $locationModal.modal('hide');
                            window.wooCommerceProduct = null;
                            window.createdOrderData = null;
                        }
                    });
                });
            }

            handleFormSubmit($messageForm, "{{ route('user.inbox.message.send') }}");
            handleFormSubmit($locationForm, "{{ route('user.inbox.message.send') }}");

            window.clearReplyTo = function() {
                window.replyTo = null;
                $replyToMessageText.text('');
                $replyToMessage.addClass('d-none');
            };

            $messageBody.on('click keydown', '.parent-message-wrapper', function(event) {
                if (event.type === 'keydown' && event.key !== 'Enter' && event.key !== ' ') return;

                event.preventDefault();

                const repliedMessageId = String($(this).attr('data-message-id') || '');
                if (!repliedMessageId) return;

                const $targetMessage = $messageBody.find('.single-message').filter(function() {
                    return String($(this).attr('data-message-id')) === repliedMessageId;
                }).first();

                if (!$targetMessage.length) return;

                const targetScrollTop = $messageBody.scrollTop() + $targetMessage.offset().top -
                    $messageBody.offset().top -
                    (($messageBody.innerHeight() - $targetMessage.outerHeight()) / 2);

                $messageBody.stop().animate({
                    scrollTop: Math.max(0, targetScrollTop)
                }, 300, function() {
                    $targetMessage.removeClass('replied-message-highlight');
                    void $targetMessage[0].offsetWidth;
                    $targetMessage.addClass('replied-message-highlight');

                    setTimeout(() => {
                        $targetMessage.removeClass('replied-message-highlight');
                    }, 1600);
                });
            });

            $messageBody.on('click', '.message-reply-button', function() {
                const waMessageId = $(this).attr('data-message-id');
                if (!waMessageId) return;

                const messageText = String($(this).attr('data-message-text'));

                window.replyTo = {
                    wa_message_id: String(waMessageId),
                    message: messageText
                };

                const contactName = $('.contact__name').text().trim();
                const contactMobile = $('.contact__mobile').text().trim();

                $replyToMessageTitle.text(contactName || contactMobile);
                $replyToMessageText.html(messageText);
                $replyToMessage.removeClass('d-none');
                $messageInput.trigger('focus');
            });

            $('.reply-to-message__cancel').on('click', function() {
                window.clearReplyTo();
                $messageInput.trigger('focus');
            });

            $('.chat-list').on('click', '.chat-list__item', function() {
                window.clearReplyTo();
            });

            $(document).on('submit', '.contactSearch', function(e) {
                e.preventDefault();
                let value = $(this).find('input[name=search]').val();
                window.fetchChatList(value);
            });

            $(document).on('click', '.resender', function() {
                if (isSubmitting) return;

                const $this = $(this);

                const messageId = $this.data('id');
                if (!messageId) return;

                isSubmitting = true;
                $this.addClass('loading');

                $.ajax({
                    url: "{{ route('user.inbox.message.resend') }}",
                    type: "POST",
                    data: {
                        'message_id': messageId,
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $messageBody.find(`[data-message-id="${messageId}"]`).remove();
                            $messageBody.append(response.data.html);
                            $messageBody.scrollTop($messageBody[0].scrollHeight);
                        } else {
                            notify('error', response.message || "@lang('Something went to wrong')");
                        }
                    },
                    error: function() {
                        notify('error', "@lang('Something went wrong.')");
                    }
                }).always(function() {
                    isSubmitting = false;
                    $this.removeClass('loading');
                });
            });

            $messageInput.keydown(function(e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    if (e.shiftKey) {
                        $(this).val($(this).val() + "\n");
                    } else {
                        $(this).closest("form").submit();
                    }
                }
            });

            $messageInput.on("focus click", function() {
                if (!window.conversation_id) return;
                let route = "{{ route('user.inbox.message.status', ':id') }}";
                $.ajax({
                    url: route.replace(':id', window.conversation_id),
                    type: "GET",
                    success: function(response) {
                        if (response.status == 'success') {
                            $('.chat-list__item[data-id="' + window.conversation_id + '"]')
                                .find('.unseen-message').html('');
                            $('.chat-list__item[data-id="' + window.conversation_id + '"]')
                                .find('.last-message').find('.text').removeClass('text--bold');
                        }
                    }
                });
            });


            // Image Preview
            $imageInput.on("change", function(event) {
                previewFile(event, "image");
            });

            // Document Preview
            $documentInput.on("change", function(event) {
                previewFile(event, "document");
            });

            // Video Preview
            $videoInput.on("change", function(event) {
                previewFile(event, "video");
            });

            // Audio
            $audioInput.on("change", function(event) {
                previewFile(event, "audio");
            });

            $voiceRecorderInput.on("change", function(event) {
                previewFile(event, "audio");
            });

            $('.select-url').on('click', function(e) {
                let url = $(this).data('id');
                $urlInput.val(url);
                let name = $(this).data('name');
                previewFile(event, "url", name);
            });

            $('.select-list').on('click', function(e) {
                let list = $(this).data('id');
                $listInput.val(list);
                let name = $(this).data('name');
                previewFile(event, "url", name);
            });

            // Block clicks on labels with media_selector if URL exists
            $('.media_selector').on('click', function(e) {
                if (isInteractiveMessage()) {
                    e.preventDefault();
                    e.stopImmediatePropagation(); // stop the event from reaching input
                    return false;
                }
            });

            $('.media-input').on('click', function(e) {
                if (isInteractiveMessage()) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    return false;
                }
            });

            function isInteractiveMessage() {
                if ($urlInput.val() || $listInput.val()) {
                    notify('error', 'Interactive message do not support anything else.');
                    return true;
                }

                return false;
            }

            function previewFile(event, type, name = null) {

                if (type == 'url' && name) {
                    $('.message-input').attr('readonly', true);
                    $('.chat-url__list').removeClass('show');
                    $('.chat-list__wrapper').removeClass('show');
                    $previewContainer.empty();
                    $previewContainer.append(`
                    <div class="preview-item url-preview text-dark">
                        ${name}
                        <button class="remove-preview">&times;</button>
                    </div>
                    `);

                    return;
                }

                if (type == 'url' && name) {
                    $('.message-input').attr('readonly', true);
                    $('.chat-url__list').removeClass('show');
                    $('.chat-list__wrapper').removeClass('show');
                    $previewContainer.empty();
                    $previewContainer.append(`
                    <div class="preview-item url-preview text-dark">
                        ${name}
                        <button class="remove-preview">&times;</button>
                    </div>
                    `);

                    return;
                }

                const file = event.target.files[0];
                if (!file && !name) return;

                const reader = new FileReader();

                reader.onload = function(e) {
                    $previewContainer.empty();

                    let previewContent = "";

                    if (type === "image") {
                        previewContent =
                            `<img src="${e.target.result}" alt="Image Preview" class="preview-image preview-item">`;
                    } else if (type === "document") {
                        let parts = file.name.split('.');
                        let name = parts[0];
                        let ext = parts[1];
                        let shortName = name.slice(0, 10);

                        let result = shortName + '.' + ext;
                        previewContent =
                            `<a href="${e.target.result}" target="_blank" class="file-preview">${result}</a>`;
                    } else if (type === "video") {
                        previewContent = `<video controls class="preview-item preview-video">
                        <source src="${e.target.result}" type="${file.type}">
                            Your browser does not support the video tag.
                        </video>`;
                    } else if (type === "audio") {
                        previewContent = `<audio controls class="preview-item preview-audio">
                        <source src="${e.target.result}" type="${file.type}">
                            Your browser does not support the audio tag.
                        </audio>`;
                    }

                    $previewContainer.append(`
                    <div class="preview-item image-preview">
                        ${previewContent}
                        <button class="remove-preview">&times;</button>
                    </div>
                    `);
                };

                reader.readAsDataURL(file);
            }

            $previewContainer.on("click", ".remove-preview", function() {
                $(this).closest(".image-preview").remove();
                clearImagePreview();
                $('.message-input').attr('readonly', false);
                $('.chat-url__list').removeClass('show');
                $('.chat-list__wrapper').removeClass('show');
                window.animation = '';
            });

            function clearImagePreview() {
                $previewContainer.empty();
                $imageInput.val("");
                $documentInput.val("");
                $videoInput.val("");
                $audioInput.val("");
                $voiceRecorderInput.val("");
                $urlInput.val("");
            }

            window.clearImagePreview = clearImagePreview;
            window.isInteractiveMessage = isInteractiveMessage;
            window.__inboxRecorder = {
                $messageForm,
                $voiceRecorderInput,
                $voiceRecorderPanel,
                $voiceRecorderTimer,
                $voiceRecorderLabel,
                $voiceRecorderWave,
                $voiceRecorderStart,
                $voiceRecorderStop,
                $voiceRecorderSend,
                getIsSubmitting: () => isSubmitting,
                maxRecordingSeconds,
                mediaRecorder,
                recordingStream,
                recordingChunks,
                recordingTimerInterval,
                recordingStopTimeout,
                recordingSeconds,
                recordingShouldAutoSend,
                recordingWasCancelled,
                hasRecordedPreview: false
            };

            $(document).on('click', '.voice_recorder', function(e) {
                e.preventDefault();
                e.stopPropagation();
                window.openVoiceRecorderPanel();
            });

            $voiceRecorderStart.on('click', function() {
                window.startVoiceRecording();
            });

            $voiceRecorderStop.on('click', function() {
                window.stopVoiceRecording();
            });

            $voiceRecorderSend.on('click', function() {
                window.sendRecordedVoice();
            });

            $voiceRecorderCancel.on('click', function() {
                window.cancelVoiceRecording();
            });

            const pusherConnection = (eventName, whatsapp) => {
                const channelName = `private-${eventName}-${whatsapp}`;
                const channel = pusher.subscribe(channelName);

                channel.bind(eventName, function(data) {
                    $("body").find('.empty-conversation').remove();
                    $("body").find(".chatbox-area__body").removeClass('d-none');
                    const {
                        messageId
                    } = data.data;

                    if ($messageBody.find(`[data-message-id="${messageId}"]`)
                        .length) {
                        $messageBody.find(
                                `[data-message-id="${data.data.messageId}"]`)
                            .find('.message-status').html(data.data.statusHtml);
                    } else {

                        if (data.data.conversationId == window.conversation_id) {
                            $messageBody.append(data.data.html);
                            setTimeout(() => {
                                $messageBody.scrollTop($messageBody[0]
                                    .scrollHeight);
                            }, 50);
                        }

                        if (data.data.newContact) {
                            window.conversation_id = data.data.conversationId;
                            window.fetchChatList("", true);
                        } else {
                            let targetConversation = $('body').find(
                                `.chat-list__item[data-id="${data.data.conversationId}"]`
                            );

                            if (data.data.lastMessageHtml) {
                                targetConversation.find('.last-message').html(
                                    data.data.lastMessageHtml);

                                targetConversation.find('.unseen-message').html(
                                    `<span class="number">${data.data.unseenMessage}</span>`
                                );
                                targetConversation.find('.last-message-at').text(
                                    data.data.lastMessageAt);
                            }
                        }
                    }

                });

                channel.bind('pusher:subscription_error', function(error) {
                    console.error(`Pusher subscription failed for ${channelName}`, error);
                });
            };

            // TODO:: Uncomment this.
            @if (request('channel') && request('channel') == Status::CHANNEL_TELEGRAM && $teleGramInstalled)
                pusherConnection('receive-message', `telegram-${window.telegram_bot_id}`);
            @else
                pusherConnection('receive-message', "{{ $whatsappAccount->id }}");
            @endif

            $('.chat-media__btn, .chat-media__list').on('click', function() {
                $('.chat-media__list').toggleClass('show');
            });

            $('.chat-media__btn').on('click', function() {
                $('.chat-url__list').removeClass('show');
                $('.chat-list__wrapper').removeClass('show');
            });

            const input = document.getElementById("searchBox");
            const hasGoogleMapsApiKey = @json((bool) gs('google_maps_api'));

            $('.location-modal-btn').on('click', function() {
                if (!hasGoogleMapsApiKey) {
                    $('.google-map').html(
                        `<div class="empty-preview"><h6 class="empty-preview__title">@lang('Google Maps preview is currently unavailable.')</h6></div>`
                    );
                    input.remove();
                } else {
                    initMap();
                }
                input.value = "";
                $locationModal.find('form')[0].reset();
                $locationModal.modal('show');
            });

            let map, marker;
            let autocompleteService, placesService;

            $locationModal.on("shown.bs.modal", function() {
                if (input) {
                    input.dataset.attached = "";
                }
            });

            function attachSearchBox() {
                if (input && !input.dataset.attached) {
                    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
                    input.dataset.attached = "true";

                    input.addEventListener("input", () => {
                        const query = input.value;
                        if (!query || query.length < 4) return;

                        autocompleteService.getPlacePredictions({
                            input: query
                        }, (predictions, status) => {
                            if (status !== google.maps.places.PlacesServiceStatus.OK || !predictions)
                                return;

                            const prediction = predictions[0];
                            placesService.getDetails({
                                placeId: prediction.place_id
                            }, (place, status) => {
                                if (status !== google.maps.places.PlacesServiceStatus.OK || !
                                    place.geometry) return;

                                const location = place.geometry.location;
                                if (marker) marker.setMap(null);

                                map.panTo(location);
                                map.setZoom(15);
                            });
                        });
                    });
                }
            }

            function initMap() {
                const defaultCenter = {
                    lat: 0,
                    lng: 0
                };

                map = new google.maps.Map(document.getElementById("map"), {
                    center: defaultCenter,
                    zoom: 12,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                });

                autocompleteService = new google.maps.places.AutocompleteService();
                placesService = new google.maps.places.PlacesService(map);

                attachSearchBox();

                // Click on map
                map.addListener("click", (event) => {
                    const clickedLocation = event.latLng;

                    if (marker) marker.setMap(null);
                    marker = new google.maps.Marker({
                        map,
                        position: clickedLocation
                    });

                    geocodeLocation(clickedLocation);
                });
            }

            function fillLocationValues(lat, lng, address) {
                $locationModal.find('input[name="latitude"]').val(lat);
                $locationModal.find('input[name="longitude"]').val(lng);
                $locationModal.find('input[name="address"]').val(address);
            }

            function geocodeLocation(location) {
                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                    location: location
                }, (results, status) => {
                    if (status === "OK" && results[0]) {
                        fillLocationValues(location.lat(), location.lng(), results[0].formatted_address);
                    }
                });
            }

            function markMapFromInputs() {
                if (!hasGoogleMapsApiKey || !window.google?.maps || !map) return;

                const latVal = parseFloat($locationModal.find('input[name="latitude"]').val());
                const lngVal = parseFloat($locationModal.find('input[name="longitude"]').val());

                if (!isNaN(latVal) && !isNaN(lngVal)) {
                    const location = new google.maps.LatLng(latVal, lngVal);

                    if (marker) marker.setMap(null);
                    marker = new google.maps.Marker({
                        map,
                        position: location
                    });

                    geocodeLocation(location);

                    map.panTo(location);
                    map.setZoom(15);
                }
            }

            $locationModal.find('input[name="latitude"], input[name="longitude"]').on('input', markMapFromInputs);

            $('.cta-url-btn').on('click', function(e) {
                $('.chat-url__list').toggleClass('show');
            });

            $('.interactive_list_btn').on('click', function(e) {
                $('.chat-list__wrapper').toggleClass('show');
            });

            $("select[name=whatsapp_account_id]").parent().find('.select2.select2-container').addClass('mb-2');

            function messageSendSvg() {
                return `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M22 2L15 22L11 13L2 9L22 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M22 2L11 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>`
            }

            $(document).on('click', '.ai-response-button', function(e) {
                e.preventDefault();
                e.stopPropagation();

                let $message = $(this).data('customer-message');

                if (!$message) return;

                if (isSubmitting) return;
                isSubmitting = true;
                $messageInput.attr('readonly', true).attr("placeholder", "@lang('Generating response from AI...')");

                $.ajax({
                    url: "{{ route('user.inbox.message.generate') }}",
                    type: "POST",
                    data: {
                        message: $message,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $messageInput.val(response.data.ai_response);
                        } else {
                            notify('error', response.message || "@lang('Something went to wrong')");
                        }
                    },
                    complete: function() {
                        isSubmitting = false;
                        $messageInput.attr('readonly', false).attr('placeholder',
                            '@lang('Type your message here message...')');
                    }
                });
            });

            $(document).on('click', '.ai-translate-button', function(e) {
                e.preventDefault();
                e.stopPropagation();

                let $message = $(this).data('message-text');
                let $messageContent = $(this).closest('.message-content');

                if (!$message) return;

                let translateDiv = $(
                    '<p class="message-text translate-text text--base text-muted">Translating message...</p>'
                );
                if ($messageContent.find('.translate-text').length) {
                    $messageContent.find('.translate-text').text('Translating message...').addClass(
                        'text--base text-muted');
                } else {
                    $messageContent.append(translateDiv);
                }

                $.ajax({
                    url: "{{ route('user.inbox.message.translate') }}",
                    type: "POST",
                    data: {
                        message: $message,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $messageContent.find('.translate-text').text(response.data.ai_response)
                                .removeClass('text--base text-muted');
                        } else {
                            notify('error', response.message || "@lang('Something went to wrong')");
                            $messageContent.find('.translate-text').remove();
                        }
                    }
                });
            });

            // Send template message
            const $templateModal = $('.templateModal');
            const $templateForm = $('#template-form');
            const $buttonPrevContainer = $(".button-preview");

            $('body').on('click', ".template_button", function() {
                $templateModal.modal('show');
            });

            $templateModal.on('hide.bs.modal', function() {
                $templateForm.trigger('reset');
                $templateForm.find('select[name=template_id]').trigger('change');
            });

            $templateForm.on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const $progress = $('#import-progress');
                const $bar = $progress.find('.progress-bar');

                formData.append('conversation_id', window.conversation_id);
                $progress.removeClass('d-none');
                $bar.css('width', '0%');

                $.ajax({
                    url: $templateForm.attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    xhr: function() {
                        const xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                                const percent = (e.loaded / e.total) * 100;
                                $bar.css('width', percent + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function(res) {
                        $progress.addClass('d-none');
                        $bar.css('width', '0%');

                        if (res.status === 'success') {
                            $templateForm.trigger('reset');
                            $messageBody.append(res.data.html);

                            if (res.data.conversationId && res.data.lastMessageHtml) {
                                $(`.chat-list__item[data-id="${res.data.conversationId}"]`)
                                    .find('.last-message').html(res.data.lastMessageHtml);
                            }

                            setTimeout(() => {
                                $messageBody.scrollTop($messageBody[0].scrollHeight);
                            }, 50);
                        } else {
                            notify('error', res.message || "Something went wrong");
                        }
                    },
                    error: function(xhr) {
                        $progress.addClass('d-none');
                        $bar.css('width', '0%');

                        let errorMessage = "Something went wrong";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        notify('error', errorMessage);
                    },
                    complete: function() {
                        $templateModal.modal('hide');
                    }
                });
            });


            $('select[name=template_id]').on('change', function() {
                generateParamsField.call(this);
                showTemplatePreview.call(this);
            }).trigger('change');

            function templateButtonHtml(btnType, btnText = undefined) {
                let btnIcon = "";
                if (btnType == 'QUICK_REPLY') {
                    btnIcon = `<i class="las la-undo"></i>`;
                    btnText = btnText || "Quick Reply Button";
                } else if (btnType == "PHONE_NUMBER") {
                    btnIcon = `<i class="las la-phone"></i>`;
                    btnText = btnText || "CTA Button";
                } else if (btnType == 'URL') {
                    btnIcon = `<i class="las la-globe"></i>`;
                    btnText = btnText || "Website Button";
                } else if (btnType == 'OTP') {
                    btnIcon = `<i class="las la-copy"></i>`;
                    btnText = btnText || "Copy Button";
                }

                return `<button type="button" class="btn btn--template bg-white w-100" data-type="${btnType}">
                                ${btnIcon} ${btnText}
                            </button>`
            }

            function templateButtonVariables(buttons) {
                // only buttons that need a value at send time get an input; the index is
                // the button position in the template, which is what the API expects
                return (Array.isArray(buttons) ? buttons : []).map(function(button, index) {
                    return {
                        index: index,
                        button: button
                    };
                }).filter(function(item) {
                    const type = (item.button?.type ?? '').toUpperCase();
                    if (type === 'URL') return /\{\{\s*\d+\s*\}\}/.test(item.button?.url ?? '');
                    return type === 'COPY_CODE';
                });
            }

            function generateParamsField() {
                let templateBody = $(this).find(':selected').data('template-body');
                let templateHeader = $(this).find(':selected').data('template-header');
                let templateHeaderText = templateHeader?.text ?? null;
                let templateCategory = ($(this).find(':selected').data('category') ?? '').toString().toUpperCase();

                let totalBodyMatches = templateBody ? templateBody.match(/\{\{\d+\}\}/g) : [];
                let totalHeaderMatches = templateHeaderText ? templateHeaderText.match(/\{\{\d+\}\}/g) : [];
                let buttonVariables = templateCategory === 'AUTHENTICATION' ? [] :
                    templateButtonVariables($(this).find(':selected').data('buttons'));

                $('.otp-template-note').toggleClass('d-none', templateCategory !== 'AUTHENTICATION');

                if (totalHeaderMatches && totalHeaderMatches.length > 0) {
                    let html = ``;
                    $.each(totalHeaderMatches, function(index, value) {
                        html += `
                        <div class="form-group">
                            <label class="label-two">Variable ${value}</label>
                            <input type="text" data-name="${value}" name="header_variables[${value}]" class="form--control form-two dynamic-filed"  placeholder="Enter value for ${value}" required>
                        </div>`;
                    });
                    $('#template-header-variables').html(html);
                    $('.header-variable-area').removeClass('d-none');
                } else {
                    $('#template-header-variables').html('');
                    $('.header-variable-area').addClass('d-none');
                }

                $('#template-body-variables').empty();

                if (totalHeaderMatches && totalHeaderMatches.length > 0 || totalBodyMatches && totalBodyMatches.length >
                    0 || buttonVariables.length > 0) {
                    $('.variable-code-wrapper').removeClass('d-none');
                } else {
                    $('.variable-code-wrapper').addClass('d-none');
                }

                if (totalBodyMatches && totalBodyMatches.length > 0) {
                    let html = ``;
                    $.each(totalBodyMatches, function(index, value) {
                        html += `
                        <div class="form-group">
                            <label class="label-two">Variable ${value}</label>
                            <input type="text" data-name="${value}" name="body_variables[${value}]" class="form--control form-two dynamic-filed"  placeholder="Enter value for ${value}" required>
                        </div>`;
                    });
                    $('#template-body-variables').html(html);
                    $('.body-variable-area').removeClass('d-none');
                } else {
                    $('#template-body-variables').html('');
                    $('.body-variable-area').addClass('d-none');
                }

                if (buttonVariables.length > 0) {
                    let html = ``;
                    $.each(buttonVariables, function(key, item) {
                        const type = (item.button?.type ?? '').toUpperCase();
                        const label = item.button?.text ?? `Button ${item.index + 1}`;
                        const hint = type === 'COPY_CODE' ? "@lang('coupon code')" : "@lang('url suffix')";
                        html += `
                        <div class="form-group">
                            <label class="label-two">${label} (${hint})</label>
                            <input type="text" name="button_variables[${item.index}]" class="form--control form-two dynamic-filed"  placeholder="Enter value for ${label}" required>
                        </div>`;
                    });
                    $('#template-button-variables').html(html);
                    $('.button-variable-area').removeClass('d-none');
                } else {
                    $('#template-button-variables').html('');
                    $('.button-variable-area').addClass('d-none');
                }
            }

            function showTemplatePreview() {
                const $selected = $('select[name=template_id] :selected');
                const $carouselPreview = $('.carousel-cards');
                const templateBody = $selected.data('template-body') ?? "@lang('Template body')";
                const footer = $selected.data('template-footer');
                const templateHeaderText = $selected.data('template-header')?.text;
                const headerMediaPath = $selected.data('header-media');
                const headerFormat = $selected.data('header-format');
                const carouselCards = $selected.data('cards') ?? [];
                const templateButtons = $selected.data('buttons') ?? [];

                if (carouselCards.length > 0) {
                    $carouselPreview.removeClass('d-none');

                    $carouselPreview.empty();

                    $.each(carouselCards, function(index, card) {
                        const cardHtml = templateCardHtml(card, index);
                        $carouselPreview.append(cardHtml);
                    });

                } else {
                    $carouselPreview.addClass('d-none');
                }

                $('.body_text').text(templateBody);
                if (footer) {
                    $('.footer_text').text(footer).removeClass('d-none');
                } else {
                    $('.footer_text').text('').addClass('d-none');
                }
                const $headerMedia = $('.header_media').empty();
                const $headerText = $('.header_text');

                if (headerFormat === 'IMAGE' && headerMediaPath) {
                    $headerText.text('');
                    $headerMedia.html(`<img src="${headerMediaPath}" alt="Template header">`);
                } else if (headerFormat === 'VIDEO' && headerMediaPath) {
                    $headerText.text('');
                    $headerMedia.html(`
                    <video controls>
                        <source src="${headerMediaPath}" type="video/mp4">
                    </video>
                    `);
                } else if (headerFormat === 'DOCUMENT' && headerMediaPath) {
                    $headerText.text('');
                    $headerMedia.html(`
                    <embed class="pdf-embed" src="${headerMediaPath}" type="application/pdf" width="100%" height="200px" style="border: none">
                    `);
                } else {
                    $headerMedia.empty();
                    if (templateHeaderText == null) {
                        $headerText.text('').addClass('d-none');
                    } else {
                        $headerText.text(templateHeaderText);
                    }
                }

                if (templateButtons.length > 0) {
                    $buttonPrevContainer.removeClass('d-none');
                    $buttonPrevContainer.empty();
                    $.each(templateButtons, function(index, button) {
                        const buttonHtml = templateButtonHtml(button.type, button.text);
                        $buttonPrevContainer.append(buttonHtml);
                    });
                } else {
                    $buttonPrevContainer.addClass('d-none');
                }
            }

            function templateCardHtml(card, index) {
                const headerFormat = card.header_format;
                const basePath = "{{ asset('assets/images/template_card_header') }}";
                const mediaPath = basePath + '/' + card.media_path;

                const mediaHtml = headerFormat === 'IMAGE' ? `<img src="${mediaPath}" alt="image">` :
                    `<video controls><source src="${mediaPath}" type="video/mp4"></video>`;
                const bodyHtml = card.body ? `<p class="card-item__desc py-2">${card.body}</p>` : '';
                var buttonHtml = "";

                if (card.buttons.buttons && card.buttons.buttons.length > 0) {
                    $.each(card.buttons.buttons, function(index, button) {
                        let text = button.text;
                        if (button.type === 'QUICK_REPLY') {
                            buttonHtml += `
                             <button type="button" class="btn btn--template bg-white w-100" data-type="QUICK_REPLY">
                                <i class="las la-reply"></i> <span class="text">${text}</span>
                            </button>
                            `;
                        } else if (button.type === 'URL') {
                            buttonHtml += `
                             <button type="button" class="btn btn--template bg-white w-100" data-type="URL">
                                <i class="la la-external-link-alt"></i> <span class="text">${text}</span>
                            </button>
                            `;
                        } else {
                            buttonHtml += `
                             <button type="button" class="btn btn--template bg-white w-100" data-type="PHONE_NUMBER">
                                <i class="la la-phone"></i> <span class="text">${text}</span>
                            </button>
                            `;
                        }
                    });
                }

                const cardHtml = `
                    <div class="card-item col-12" data-card-index="${index}">
                        <div class="card-item__thumb">
                            ${mediaHtml}
                        </div>
                        ${bodyHtml}
                        <div class="button-preview mt-2 d-flex gap-2 flex-column">
                            ${buttonHtml}
                        </div>
                    </div>
                `;
                return cardHtml;
            }

            $(document).on('mousedown', '.code-btn', function(e) {
                e.preventDefault();
                let code = $(this).data('code');
                let focusedInput = $('.dynamic-filed:focus');
                if (focusedInput.length) {
                    focusedInput.val(code).trigger('change');
                }
            });

            // send list message
            const $sectionWrapper = $('.message-preview-list__section_wrapper');
            $(document).on('click', '.list-message-btn', function(e) {
                const interactiveList = $(this).data('list');
                const sections = interactiveList.sections;

                $sectionWrapper.html('');
                sections.forEach((section, index) => {
                    const sectionHtml = `
                    <div class="message-preview-list__section" data-section-index="${index}">
                        <p class="message-preview-list__section-title">${section.title}</p>
                        <div class="row_wrapper">
                            ${section.rows.map(row => {
                                return `
                                                                                                                                                                                                                                                                                                        <div class="message-preview-list__row">
                                                                                                                                                                                                                                                                                                            <div class="message-preview-list__text">
                                                                                                                                                                                                                                                                                                                <p class="title">${row.title}</p>
                                                                                                                                                                                                                                                                                                                <p class="desc">${row.description}</p>
                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                            <span class="message-preview-list__radio"></span>
                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                        `;
                            }).join('')}
                        </div>
                    </div>
                    `;
                    $sectionWrapper.append(sectionHtml);
                });

                $listMessageModal.find('.header_text').text(interactiveList.header?.text ?? '');
                $listMessageModal.find('.body_text').text(interactiveList.body?.text ?? '');
                $listMessageModal.find('.footer_text').text(interactiveList.footer?.text ?? '');
                $listMessageModal.find('.modal-title').text(interactiveList.button_text ?? '');

                $listMessageModal.modal('show');
            });

            $listMessageModal.on('hide.bs.modal', function() {
                setTimeout(() => {
                    $sectionWrapper.html('');
                }, 500);
            });

            $(document).on('click', '.message-inbox-btn', () => $('.chatbox-area').toggleClass('sidebar-left-show'));

        })(jQuery);
    </script>
@endpush





@push('style')
    <style>
        .scrollable-list {
            max-height: 300px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .scrollable-list::-webkit-scrollbar {
            width: 5px;
        }

        .scrollable-list::-webkit-scrollbar-thumb {
            background: hsl(var(--base));
            border-radius: 10px;
        }


        .list-item {
            padding: 10px 10px 10px 0px;
            color: #333;
            font-size: 16px;
            background-color: #fff;
            line-height: 1.4;
        }

        .list-item:not(:last-child) {
            border-bottom: 1px solid #ddd;

        }



        .scrollable-list {
            padding: 0px !important;
            border: none !important;
            max-height: 300px;
            overflow-y: auto;
        }

        /*  */

        .message-input {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .resender {
            cursor: pointer !important;
        }

        .resender.loading {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .media-item {
            position: relative;
        }

        .image-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            top: 12px !important;
            cursor: pointer;
        }

        .media-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer !important;
        }

        .image-upload-btn,
        .image-upload-btn i {
            cursor: pointer;
        }

        .emoji-container {
            position: absolute;
            display: none;
            z-index: 999;
            bottom: 55px;
            left: 13px;
            max-width: 100%;
        }

        .file-preview {
            height: 56px;
            padding-left: 5px;
            font-size: 14px;
        }

        .preview-item,
        .image-preview img {
            max-width: 105px;
            max-height: 55px;
            border-radius: 5px;
            border: 1px solid #ddd;
            object-fit: cover;
        }

        .image-preview-container {
            display: flex;
            align-items: flex-end;

        }

        @media (max-width: 424px) {
            .image-preview-container {
                display: flex;
                align-items: flex-end;
                position: absolute;
                left: 72px;
                top: 50%;
                transform: translateY(-50%);
            }

            .preview-item,
            .image-preview img {
                width: 50px;
                height: 50px;
            }

            .file-preview {
                height: 50px;
                overflow-y: auto;
                background: #fff;
            }
        }

        .image-preview {
            position: relative;
            display: inline-block;
        }

        .url-preview {
            position: relative;
            display: inline-block;
            height: 100%;
            max-width: 200px !important;
            width: 80px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
        }

        .voice-recorder-panel {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .voice-recorder-panel__backdrop {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top, rgba(18, 140, 126, 0.2), rgba(10, 14, 22, 0.72));
            backdrop-filter: blur(8px);
        }

        .voice-recorder-panel__content {
            position: relative;
            width: min(100%, 420px);
            padding: 28px 24px;
            border-radius: 24px;
            background: linear-gradient(180deg, #ffffff 0%, #f2fbf8 100%);
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            text-align: center;
        }

        .voice-recorder-panel__close {
            position: absolute;
            top: 14px;
            right: 14px;
            z-index: 3;
            width: 38px;
            height: 38px;
            border: 0;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(17, 33, 29, 0.08);
            color: #11211d;
            transition: .2s ease;
        }

        .voice-recorder-panel__close:hover {
            background: rgba(17, 33, 29, 0.14);
        }

        .voice-recorder-panel__content::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(18, 140, 126, 0.08), transparent 58%);
            pointer-events: none;
        }

        .voice-recorder-panel__pulse {
            position: relative;
            width: 92px;
            height: 92px;
            margin: 0 auto 18px;
            display: grid;
            place-items: center;
        }

        .voice-recorder-panel__pulse::after {
            content: "\f130";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #128c7e, #25d366);
            color: #fff;
            font-size: 28px;
            position: relative;
            z-index: 2;
            box-shadow: 0 14px 32px rgba(37, 211, 102, 0.35);
        }

        .voice-recorder-panel__pulse span {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 2px solid rgba(18, 140, 126, 0.22);
            animation: voicePulse 2.2s ease-out infinite;
        }

        .voice-recorder-panel__pulse span:nth-child(2) {
            animation-delay: .55s;
        }

        .voice-recorder-panel__pulse span:nth-child(3) {
            animation-delay: 1.1s;
        }

        .voice-recorder-panel__title {
            margin-bottom: 6px;
            font-size: 22px;
            font-weight: 700;
            color: #11211d;
        }

        .voice-recorder-panel__subtitle {
            margin-bottom: 18px;
            font-size: 14px;
            line-height: 1.6;
            color: #5c6f68;
        }

        .voice-recorder-panel__status {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(18, 140, 126, 0.08);
            color: #0f5d53;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .voice-recorder-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ff4d4f;
            box-shadow: 0 0 0 0 rgba(255, 77, 79, 0.55);
            animation: voiceDotBlink 1.2s infinite;
        }

        .voice-recorder-timer {
            min-width: 48px;
            font-variant-numeric: tabular-nums;
        }

        .voice-recorder-panel__wave {
            height: 58px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 6px;
            margin-bottom: 22px;
        }

        .voice-recorder-panel__wave span {
            width: 8px;
            border-radius: 999px;
            background: linear-gradient(180deg, #25d366 0%, #128c7e 100%);
            animation: voiceWave 1.1s ease-in-out infinite;
        }

        .voice-recorder-panel__wave.is-idle span {
            animation-play-state: paused;
            opacity: .45;
        }

        .voice-recorder-panel__wave span:nth-child(1) {
            height: 20px;
            animation-delay: .05s;
        }

        .voice-recorder-panel__wave span:nth-child(2) {
            height: 34px;
            animation-delay: .18s;
        }

        .voice-recorder-panel__wave span:nth-child(3) {
            height: 44px;
            animation-delay: .32s;
        }

        .voice-recorder-panel__wave span:nth-child(4) {
            height: 28px;
            animation-delay: .46s;
        }

        .voice-recorder-panel__wave span:nth-child(5) {
            height: 50px;
            animation-delay: .12s;
        }

        .voice-recorder-panel__wave span:nth-child(6) {
            height: 26px;
            animation-delay: .38s;
        }

        .voice-recorder-panel__wave span:nth-child(7) {
            height: 42px;
            animation-delay: .24s;
        }

        .voice-recorder-panel__wave span:nth-child(8) {
            height: 22px;
            animation-delay: .52s;
        }

        .voice-recorder-panel__actions {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        body.voice-recorder-active {
            overflow: hidden;
        }

        @keyframes voicePulse {
            0% {
                transform: scale(.7);
                opacity: .8;
            }

            100% {
                transform: scale(1.55);
                opacity: 0;
            }
        }

        @keyframes voiceWave {

            0%,
            100% {
                transform: scaleY(.45);
                opacity: .55;
            }

            50% {
                transform: scaleY(1.1);
                opacity: 1;
            }
        }

        @keyframes voiceDotBlink {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 77, 79, 0.55);
            }

            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(255, 77, 79, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 77, 79, 0);
            }
        }

        @media (max-width: 575px) {
            .voice-recorder-panel__content {
                padding: 24px 18px;
                border-radius: 20px;
            }

            .voice-recorder-panel__title {
                font-size: 20px;
            }
        }

        .remove-preview {
            position: absolute;
            top: -5px;
            right: -5px;
            background: red;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .chatbody:has(.empty-message) {
            min-height: calc(100% - 180px);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .chatbox-wrapper:has(.empty-message) {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .body-right.contact__details:has(.empty-message) {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .empty-conversation {
            display: flex;
            justify-content: center;
            align-items: center;
            width: calc(100% - 370px) !important;
        }

        @media screen and (max-width: 1399px) {
            .empty-conversation {
                width: calc(100% - 280px) !important;
            }
        }

        @media screen and (max-width: 767px) {
            .empty-conversation {
                width: 100% !important;
            }
        }

        .empty-conversation img {
            max-width: 300px;
        }

        @media screen and (max-width: 575px) {
            .empty-conversation img {
                max-width: 200px;
            }
        }

        .template_button,
        .clear_button {
            cursor: pointer;
        }

        .templateModal .preview-item,
        .templateModal .image-preview img {
            max-height: unset !important;
        }

        .template-info-container__right .preview-item__content {
            padding: 24px;
            display: flex;
            flex-direction: column;
        }

        .locationModal .template-info-container__right .preview-item__content {
            padding: unset !important;
        }

        .template-info-container__right .preview-item__content .card-item {
            width: 100%;
        }

        .template-info-container__right .preview-item__content .card-item__thumb {
            width: 100%;
            max-height: 210px;
            overflow: hidden;
            border-radius: 10px;
        }

        @media (max-width: 424px) {

            .preview-item,
            .image-preview img {
                width: unset;
                height: unset;
            }
        }

        .csv-form-wrapper__left {
            align-self: start;
        }

        .blocked-message {
            background: #f2f2f2;
            border: 1px solid #e0e0e0;
            border-radius: 50px;
            color: #6c757d;
            font-size: 0.9rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(4px);
        }

        .blocked-message i {
            color: #6c757d;
        }

        .google-map {
            width: 100% !important;
            height: 400px !important;
            color: hsl(var(--base)) !important;
        }

        .empty-preview {
            width: 100%;
            height: 250px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            color: hsl(var(--base));
        }

        .empty-preview__title {
            color: hsl(var(--title-color)/0.6) !important;
            font-weight: 400 !important;
        }

        #searchBox {
            position: absolute;
            width: 200px;
            top: 10px;
            right: 70px;
            background: #fff;
            border: none;
            margin-top: 6px;
            border-radius: 4px;
            font-size: 15px;
            padding: 12px;
            height: 30px;
            z-index: 999999;
            outline: none;
        }

        .message-preview-list {
            overflow: auto !important;
            background: hsl(var(--white));
            scrollbar-width: thin;
            scrollbar-color: hsl(var(--base) / 0.8) hsl(var(--black) / 0.1);
        }

        .message-preview-list::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .close-preview-btn {
            position: absolute;
            left: 16px;
            top: 12px;
            font-size: 20px;
            cursor: pointer;
        }

        .message-preview-list__body {
            padding: 8px 0 16px;
        }

        .message-preview-list__section_wrapper {
            display: flex;
            flex-direction: column;
        }

        .message-preview-list__section-title {
            font-size: 18px;
            font-weight: 500;
            color: #383636;
            padding: 12px 16px 0px 10px;
            text-transform: lowercase;
        }

        .message-preview-list__row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 8px;
        }

        .message-preview-list__row:hover {
            background: rgba(0, 0, 0, 0.04);
        }

        .message-preview-list__text .title {
            font-size: 13px;
            font-weight: 500;
            margin: 0;
            color: #111;
        }

        .message-preview-list__text .desc {
            font-size: 13px;
            color: #8696a0;
            margin: 2px 0 0;
        }

        .message-preview-list__radio {
            width: 15px;
            height: 15px;
            border: 2px solid #8696a0;
            border-radius: 50%;
            flex-shrink: 0;
            margin-left: 12px;
        }

        .message-preview-list__row.selected .message-preview-list__radio {
            border: 5px solid #00a884;
        }

        @media screen and (max-width: 424px) {
            .woo-commerce-btn-group {
                flex-wrap: wrap;
            }
        }

        .message-reply-button {
            position: absolute !important;
            right: -20px;
            top: 50%;
            transform: translate(50%, -30%);
            cursor: pointer;
        }

        .parent-message-wrapper {
            position: relative;
            width: 100%;
            min-width: 0;
            margin-bottom: 8px;
            padding: 8px 10px 8px 12px;
            overflow: hidden;
            border-radius: 6px;
            background: #f0f2f5;
            color: #667781;
            cursor: pointer;
        }

        .parent-message-wrapper:focus-visible {
            outline: 2px solid hsl(var(--base));
            outline-offset: 2px;
        }

        .replied-message-highlight .message-content {
            animation: replied-message-highlight 1.5s ease-out;
        }

        @keyframes replied-message-highlight {

            0%,
            35% {
                box-shadow: 0 0 0 4px hsl(var(--base) / 0.35);
            }

            100% {
                box-shadow: 0 0 0 0 hsl(var(--base) / 0);
            }
        }

        .message--right .parent-message-wrapper {
            background: rgba(255, 255, 255, 0.58);
        }

        .parent-message-wrapper::before {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            width: 4px;
            background: hsl(var(--base));
            content: '';
        }

        .parent-message-text {
            display: flex;
            min-width: 0;
            flex-direction: column;
            gap: 2px;
        }

        .parent-message-title {
            color: hsl(var(--base));
            font-size: 13px;
            font-weight: 600;
            line-height: 1.35;
        }

        .parent-message-content {
            display: -webkit-box;
            overflow: hidden;
            color: #667781;
            font-size: 12px;
            line-height: 1.4;
            overflow-wrap: anywhere;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }
    </style>
@endpush
