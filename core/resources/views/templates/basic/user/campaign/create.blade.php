@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Start your next campaign by completing the form below.')</p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <a href="{{ route('user.campaign.index') }}" class="btn btn--dark">
                        <i class="las la-undo"></i>
                        @lang('Back')
                    </a>
                    <button type="submit" form="campaign-form" class="btn btn--base btn-shadow">
                        <i class="lab la-telegram"></i> @lang('Save Campaign')
                    </button>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <div class="information-wrapper">
                <form action="{{ route('user.campaign.save') }}" method="POST" class="information-main-form"
                    id="campaign-form">
                    @csrf
                    <div class="row gy-2">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="label-two">@lang('Title')</label>
                                <input type="text" class="form--control form-two" name="title"
                                    placeholder="@lang('Enter campaign title')" required value="{{ old('title') }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="label-two">@lang('Whatsapp Account')</label>
                                <x-whatsapp_account />
                                <span class="fs-12 text-dark">
                                    <i>@lang('Please select the WhatsApp account from which the message will be sent or the campaign will be started')</i>
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="label-two">@lang('Contact From List')</label>
                                <select name="contact_lists[]" class="form--control select2 form-two contactList"
                                    data-minimum-results-for-search="-1" data-placeholder="Choose contact list" multiple>
                                    @foreach ($contactLists as $list)
                                        <option value="{{ $list->id }}" @selected(in_array($list->id, old('contact_lists', [])))>
                                            {{ __(@$list->name) . ' (' . $list->contact->count() . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="label-two">@lang('Contact From Tag')</label>
                                <select name="contact_tags[]" class="form--control select2 form-two contactTag"
                                    data-minimum-results-for-search="-1" data-placeholder="Choose contact tags" multiple>
                                    @foreach ($contactTags as $contactTag)
                                        <option value="{{ $contactTag->id }}" @selected(in_array($contactTag->id, old('contact_tags', [])))>
                                            {{ __(@$contactTag->name) . ' (' . $contactTag->contacts->count() . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12" id="schedule">
                            <div class="form-group">
                                <label class="label-two">@lang('Send At')</label>
                                <select name="schedule" class="form--control form-two select2"
                                    data-minimum-results-for-search="-1" required>
                                    <option value="off">@lang('Send Now')</option>
                                    <option value="on" selected>@lang('Schedule At')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 d-none" id="date">
                            <div class="form-group">
                                <label class="label-two">@lang('Scheduled At')</label>
                                <input type="date" class="form--control form-two date" name="scheduled_at">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="label-two">@lang('Choose template')</label>
                                <select name="template_id" class="form--control select2 form-two"
                                    data-minimum-results-for-search="-1" required>
                                    <option value="" disabled>@lang('Please select whatsapp account first')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 header-variable-area d-none">
                            <div class="row justify-content-center">
                                <div class="col-lg-6">
                                    <div class="auth-devider text-center">
                                        <span> @lang('HEADER VARIABLES')</span>
                                    </div>
                                </div>
                            </div>
                            <div id="template-header-variables"></div>
                        </div>
                        <div class="col-12 body-variable-area d-none">
                            <div class="row justify-content-center">
                                <div class="col-lg-6">
                                    <div class="auth-devider text-center">
                                        <span> @lang('BODY VARIABLES')</span>
                                    </div>
                                </div>
                            </div>
                            <div id="template-body-variables"></div>
                        </div>
                        <div class="col-12 d-none variable-code-wrapper">
                            @foreach (variableShortCodes() as $key => $value)
                                <span class="btn btn--sm btn--dark code-btn"
                                    data-code="{{ $value }}">{{ $value }}</span>
                            @endforeach
                        </div>
                    </div>
                </form>
                <div class="template-info-container__right">
                    <div class="preview-item">
                        <div class="preview-item__header">
                            <h5 class="preview-item__title">@lang('Template Preview')</h5>
                        </div>
                        <div class="preview-item__content">
                            <div class="preview-item__shape">
                                <img src="{{ getImage($activeTemplateTrue . 'images/preview-1.png') }}" alt="image">
                            </div>
                            <div>
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
                                </div>
                                <div class="button-preview mt-2 d-flex gap-2 flex-column">
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
@endsection

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/global/css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}" />
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/flatpickr.min.js') }}"></script>
@endpush


@push('script')
    <script>
        (function($) {
            "use strict";

            $("select[name=schedule]").on('change', function(e) {
                toggleDateField();
            });

            function toggleDateField() {
                let schedule = $("select[name=schedule]").val();
                if (schedule == 'on') {
                    $("#schedule").removeClass('col-md-12')
                    $("#schedule").addClass('col-md-6');
                    $("#date").removeClass('d-none');
                } else {
                    $("#schedule").addClass('col-md-12')
                    $("#schedule").removeClass('col-md-6');
                    $("#date").addClass('d-none');
                }
            }

            toggleDateField();

            $('#campaign-form').on("submit", function(e) {
                e.preventDefault();
                let form = $(this);
                let route = "{{ route('user.campaign.save') }}";

                $.ajax({
                    url: route,
                    method: 'POST',
                    data: form.serialize(),
                    success: function(res) {
                        notify(res.status, res.message);
                    },
                    error: function(err) {
                        notify('error', 'Something went wrong! Please try again later');
                    },
                    complete: function() {
                        form.trigger('reset');
                        form.find('select').trigger('change');
                    }
                });
            });

            $(".date").flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today",
                defaultDate: new Date(),
                minuteIncrement: 10,
                allowInput: false,
                onClose: function(selectedDates, dateStr, instance) {
                    let date = selectedDates[0];
                    if (date) {
                        let minutes = date.getMinutes();
                        if (minutes % 10 !== 0) {
                            date.setMinutes(Math.round(minutes / 10) * 10);
                            instance.setDate(date, true);
                        }
                    }
                },
                onReady: function(selectedDates, dateStr, instance) {
                    instance._input.removeAttribute("readonly");
                }
            });

            $(document).on('mousedown', '.code-btn', function(e) {
                e.preventDefault();
                let code = $(this).data('code');
                let focusedInput = $('.dynamic-filed:focus');
                if (focusedInput.length) {
                    focusedInput.val(code);
                }
            });

            function generateParamsField() {
                let templateBody = $(this).find(':selected').data('template-body');
                let templateHeader = $(this).find(':selected').data('template-header');
                let templateHeaderText = templateHeader?.text ?? null;

                let totalBodyMatches = templateBody ? templateBody.match(/\{\{\d+\}\}/g) : [];
                let totalHeaderMatches = templateHeaderText ? templateHeaderText.match(/\{\{\d+\}\}/g) : [];

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
                    $('.variable-code-wrapper').addClass('d-none');
                }

                $('#template-body-variables').empty();

                if (totalHeaderMatches && totalHeaderMatches.length > 0 || totalBodyMatches && totalBodyMatches.length >
                    0) {
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
            }

            $('select[name=template_id]').on('change', function() {
                generateParamsField.call(this);
                showTemplatePreview.call(this);
            });

            $('select[name=whatsapp_account_id]').on('change', function() {
                let $this = $(this);
                let getWhatsappAccountId = $this.val();
                if (!getWhatsappAccountId) return;
                getWhatsappAccountTemplates(getWhatsappAccountId);
            }).trigger('change');

            function getWhatsappAccountTemplates() {
                let id = $('select[name=whatsapp_account_id]').val();
                let route = "{{ route('user.template.get', ':id') }}";
                $.ajax({
                    url: route.replace(':id', id),
                    type: "POST",
                    data: {
                        whatsapp_account_id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status != 'success') {
                            notify('error', response.message);
                            return;
                        }
                        $('select[name=template_id]').empty();
                        $('select[name=template_id]').append(
                            `<option value="" selected>@lang('Select template')</option>`);
                        let templates = response.data.templates ?? [];
                        if (templates.length != 0) {
                            $('select[name=template_id]').empty();
                        };
                        let headerBasePath = "{{ asset(getFilePath('templateHeader')) }}";
                        $.each(templates, function(index, template) {
                            const mediaUrl = `${headerBasePath}/${template.header_media}`;
                            $('select[name=template_id]').append(`
                            <option value="${template.id}" data-template-header='${JSON?.stringify(template.header)}' data-cards='${JSON?.stringify(template.cards)}' data-template-body="${template.body}" data-template-footer="${template.footer}" data-header-format="${template.header_format}" data-header-media="${mediaUrl}">
                                ${template.name}
                            </option>`);
                        });
                        $('select[name=template_id]').trigger('change');
                    }
                });
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
                    $('.footer_text').text(footer);
                } else {
                    $('.footer_text').remove();
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
                        $headerText.text(templateHeaderText).removeClass('d-none');
                    }
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


        })(jQuery);
    </script>
@endpush


@push('style')
    <style>
        .datepickers-container {
            z-index: 9999999999;
        }

        .divider-title::after {
            position: absolute;
            content: '';
            top: 14px;
            left: 0px;
            background: #6b6b6b65;
            height: 2px;
            width: 100%;
        }

        .divider-title .text {
            background: hsl(var(--white));
            position: relative;
            z-index: 1;
            padding: 0 10px;
        }

        .information-main-form {
            width: calc(100% - 500px);
        }

        .information-wrapper {
            display: flex;
            gap: 16px;
        }

        @media screen and (max-width: 1199px) {
            .information-wrapper {
                flex-direction: column;
            }

            .information-main-form {
                width: 100%;
            }
        }

        .template-info-container__right .preview-item {
            position: sticky !important;
            top: calc(45vh - 150px) !important;
        }
    </style>
@endpush
