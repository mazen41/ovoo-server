@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="alert alert--info alert-dismissible mb-3 template-requirements" role="alert">
        <div class="alert__content">
            <h4 class="alert__title"><i class="las la-info-circle"></i> @lang('Interactive List Information')</h4>
            <ul class="ms-4">
                <li class="mb-0 text-dark">@lang('The header, footer, and button text must be in text format only. Maximum lengths: header and footer 60 characters, button 20 characters.')</li>
                <li class="mb-0 text-dark">@lang('The body must be in text format only, with a maximum length of 4,096 characters.')</li>
                <li class="mb-0 text-dark">@lang('You can create up to 10 sections in total, and the total number of rows across all sections cannot exceed 10.')</li>
                <li class="mb-0 text-dark">@lang('Each section title can contain up to 24 characters.')</li>
                <li class="mb-0 text-dark">@lang('Each row title can contain up to 24 characters, and each description can contain up to 72 characters.')</li>
            </ul>
        </div>
    </div>
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Creating a user-friendly interactive list is an excellent way to communicate with customers and prospects.')</p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <a href="{{ route('user.interactive-list.index') }}" class="btn btn--dark">
                        <i class="las la-undo"></i>
                        @lang('Back')
                    </a>
                    <button class="btn btn--base btn-shadow submitBtn" type="submit" form="interactive-list-form">
                        <i class="lab la-telegram"></i> @lang('Save List')
                    </button>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <div class="template-info-container">
                <div class="template-info-container__left">
                    <form action="{{ route('user.interactive-list.store') }}" method="POST" id="interactive-list-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="label-two">@lang('List Name')</label>
                                    <input type="text" class="form--control form-two" name="name"
                                        placeholder="@lang('Enter a unique name')" value="{{ old('name') }}" maxlength="20"
                                        required>
                                    <div class="d-flex justify-content-end fs-12 pt-2 text-muted">
                                        <span class="character-count" data-limit="20">0</span>
                                        <span>/ 20</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="label-two">
                                    @lang('Header Text')
                                    <span class="las la-question-circle" data-bs-toggle="tooltip"
                                        data-bs-title="@lang('The header is optional. Leave blank if not needed.')"></span>
                                </label>
                                <input type="text" class="form--control form-two" name="header"
                                    placeholder="@lang('Enter header text')" value="{{ old('header') }}" maxlength="60">
                                <div class="d-flex justify-content-end fs-12 pt-2 text-muted">
                                    <span class="character-count" data-limit="60">0</span>
                                    <span>/ 60</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="label-two">@lang('Body Text')</label>
                                <div class="body-content">
                                    <textarea class="form--control form-two" name="body" id="body" maxlength="4096" placeholder="@lang('Write your list body text...')"
                                        required>{{ old('body') }}</textarea>
                                </div>
                                <div class="d-flex justify-content-end fs-12 text-muted">
                                    <span class="character-count" data-limit="4096">0</span>
                                    <span>/ 4096</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="title" class="form--label label-two">
                                    @lang('Footer Text')
                                    <span class="las la-question-circle" data-bs-toggle="tooltip"
                                        data-bs-title="@lang('The footer is optional. Leave blank if not needed.')"></span>
                                </label>
                                <input type="text" class="form--control form-two" name="footer" maxlength="60"
                                    placeholder="@lang('Enter footer text')" value="{{ old('footer') }}">
                                <div class="d-flex justify-content-end fs-12 pt-2 text-muted">
                                    <span class="character-count" data-limit="60">0</span>
                                    <span>/ 60</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="title" class="form--label label-two">@lang('Button Text')</label>
                                <input type="text" class="form--control form-two" name="button_text" maxlength="20"
                                    placeholder="@lang('Enter button text')" value="{{ old('button_text') }}" required>
                                <div class="d-flex justify-content-end fs-12 pt-2 text-muted">
                                    <span class="character-count" data-limit="20">0</span>
                                    <span>/ 20</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="my-4">
                                <div class="row justify-content-center">
                                    <div class="col-lg-6">
                                        <div class="auth-devider text-center">
                                            <span>@lang('LIST SECTIONS')</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn--base add-section-button mb-2">
                                <i class="las la-plus"></i>
                                @lang('Add Section')
                            </button>
                        </div>

                        <div id="sections-wrapper">

                            <div class="section-block mb-4 p-3 border rounded list--section" data-section-index="0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5>Section 1</h5>
                                    <button class="btn btn--danger btn--sm remove-list-button">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>

                                <div class="form-group">
                                    <label class="label-two">@lang('Title')</label>
                                    <input type="text" class="form--control form-two" data-section-index="0"
                                        name="sections[0][title]" maxlength="24" placeholder="@lang('Enter section title')">
                                </div>

                                <div class="rows-wrapper">

                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="text-dark text--bold">@lang('Rows')</div>
                                        <button class="btn btn--base btn--sm add-row-button" type="button">
                                            <i class="las la-plus"></i>
                                        </button>
                                    </div>

                                    <div class="custom-row-wrapper mb-2">
                                        <div class="row align-items-center gy-3 w-100">
                                            <div class="col-12 d-flex align-items-center gap-2">
                                                <input type="text" name="sections[0][rows][0][title]" maxlength="24"
                                                    class="form--control form-two" data-class-type="title"
                                                    placeholder="@lang('Row title')" required>
                                                <button type="button" class="btn btn--danger remove-attribute">
                                                    <i class="las la-trash"></i>
                                                </button>
                                            </div>
                                            <div class="col-12">
                                                <input type="text" name="sections[0][rows][0][description]"
                                                    maxlength="72" data-class-type="desc" class="form--control form-two"
                                                    placeholder="@lang('Row description')">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="custom-row-wrapper mb-2">
                                        <div class="row align-items-center gy-3 w-100">
                                            <div class="col-12 d-flex align-items-center gap-2">
                                                <input type="text" name="sections[0][rows][1][title]" maxlength="24"
                                                    class="form--control form-two" placeholder="@lang('Row title')"
                                                    data-class-type="title" required>
                                                <button type="button" class="btn btn--danger remove-attribute">
                                                    <i class="las la-trash"></i>
                                                </button>
                                            </div>
                                            <div class="col-12">
                                                <input type="text" name="sections[0][rows][1][description]"
                                                    maxlength="72" class="form--control form-two" data-class-type="desc"
                                                    placeholder="@lang('Row description')">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="template-info-container__right">
                    <div class="preview-item">
                        <div class="preview-item__header">
                            <h5 class="preview-item__title">@lang('Message Preview')</h5>
                        </div>
                        <div class="preview-item__content">
                            <div class="preview-item__shape">
                                <img src="{{ getImage($activeTemplateTrue . 'images/preview-1.png') }}" alt="image">
                            </div>
                            <div>
                                <div class="card-item">
                                    <div class="card-item__content">
                                        <p class="card-item__title header_text fs-14">@lang('Header text')</p>
                                        <p class="card-item__desc body_text fs-12">@lang('Body text')</p>
                                        <p class="text-wrapper">
                                            <span class="text footer_text">@lang('Footer text')</span>
                                            <span class="text time-preview">{{ date('h:i A') }}</span>
                                        </p>
                                    </div>
                                    <div class="button-preview mt-2 border-top text-center p-2">
                                        <div class="button-text-preview text--base w-100">
                                            <i class="las la-list"></i>
                                            <span class="button-text text--base">@lang('Button text')</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="message-preview-list">
                                <div class="message-preview-list__header">
                                    <span class="close-preview-btn"><i class="las la-times"></i></span>
                                    <h6 class="message-preview-list__header__title">@lang('Button text')</h6>
                                </div>

                                <div class="message-preview-list__body">

                                    <div class="message-preview-list__section_wrapper">

                                        <div class="message-preview-list__section" data-section-index="0">
                                            <p class="message-preview-list__section-title">@lang('Section 1 title')</p>

                                            <div class="row_wrapper">

                                                <div class="message-preview-list__row">
                                                    <div class="message-preview-list__text">
                                                        <p class="title">@lang('Row title')</p>
                                                        <p class="desc">@lang('Row description')</p>
                                                    </div>
                                                    <span class="message-preview-list__radio"></span>
                                                </div>

                                                <div class="message-preview-list__row">
                                                    <div class="message-preview-list__text">
                                                        <p class="title">@lang('Row title')</p>
                                                        <p class="desc">@lang('Row description')</p>
                                                    </div>
                                                    <span class="message-preview-list__radio"></span>
                                                </div>

                                            </div>
                                        </div>


                                    </div>
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
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            const handleInput = (selector, callback) => $('body').on('input paste', selector, callback);

            handleInput('input[name=button_text]', function(e) {
                $('.button-text').text($(this).val());
            });

            handleInput("input[name='header']", function(e) {
                $('.header_text').text($(this).val() || "Message Header");
            });

            handleInput('textarea[name=body]', function(e) {
                $('.body_text').text($(this).val() || "Message Body");
            });

            handleInput('input[name=footer]', function(e) {
                $('.footer_text').text($(this).val() || "Message Footer");
            });

            // manage list section
            $(document).on('click', '.add-section-button', function(e) {
                e.preventDefault();

                let count = $('#sections-wrapper .section-block').length;

                if (count >= 10) {
                    notify('error', "@lang('Maximum 10 list sections are allowed')");
                    return;
                }

                const totalRows = $(document).find('.rows-wrapper .custom-row-wrapper').length;

                if (totalRows + 2 > 10) {
                    notify('error', "@lang('Including all sections, a maximum of 10 rows are allowed')");
                    return;
                }

                let newIndex = count;

                let $clone = $('#sections-wrapper .section-block:first').clone();

                let $rowsWrapper = $clone.find('.rows-wrapper');
                let $defaultRows = $rowsWrapper.find('.custom-row-wrapper').slice(0, 2).clone();

                $rowsWrapper.find('.custom-row-wrapper').remove();
                $rowsWrapper.append($defaultRows);

                $clone.find('[name]').each(function() {
                    let name = $(this).attr('name');
                    if (name) {
                        let updated = name.replace(/sections\[\d+\]/, 'sections[' + newIndex + ']');
                        $(this).attr('name', updated);
                    }
                });

                $clone.attr('data-section-index', newIndex);
                $clone.find('[data-section-index]').each(function() {
                    $(this).attr('data-section-index', newIndex);
                });

                $clone.find('h5').text('Section ' + (newIndex + 1));

                $clone.find('input[type=text], input[type=url], input[type=file]').val('');
                $clone.find('textarea').val('');
                $clone.find('select').prop('selectedIndex', 0);

                $('#sections-wrapper').append($clone);

                updateSectionPreview(newIndex);

                $('html, body').animate({
                    scrollTop: $(document).height()
                }, 'slow');
            });


            // manage rows per section
            $(document).on('click', '.add-row-button', function(e) {
                e.preventDefault();

                const $section = $(this).closest('.list--section');
                const index = $section.find('.rows-wrapper .custom-row-wrapper').length;
                const sectionIndex = $section.data('section-index');
                const totalRows = $(document).find('.rows-wrapper .custom-row-wrapper').length;

                if (totalRows >= 10) {
                    notify('error', "@lang('Including all sections, a maximum of 10 rows are allowed')");
                    return;
                }

                $section.find('.rows-wrapper').append(generateRowsHtml(index, sectionIndex));

                updateSectionRowPreview(sectionIndex, index);
            });

            function generateRowsHtml(index, sectionIndex) {
                let html = '';
                let baseName = `sections[${sectionIndex}][rows][${index}]`;
                html = `<div class="custom-row-wrapper mb-2">
                            <div class="row align-items-center gy-3 w-100">
                                <div class="col-12 d-flex align-items-center gap-2">
                                    <input type="text" name="${baseName}[title]" data-class-type="title" maxlength="24"
                                        class="form--control form-two"
                                        placeholder="@lang('Row title')" required>
                                    <button type="button" class="btn btn--danger remove-attribute">
                                        <i class="las la-trash"></i>
                                    </button>
                                </div>
                                <div class="col-12">
                                    <input type="text" name="${baseName}[description]"
                                        maxlength="72"
                                        data-class-type="desc"
                                        class="form--control form-two"
                                        placeholder="@lang('Row description')">
                                </div>
                            </div>
                        </div>`;

                return html;
            }

            function updateCharacterCount($this) {
                const count = $this.val().length;
                const element = $this.closest('.form-group').find('.character-count');
                element.text(count);
                let limit = element.data('limit');
                if (count == limit) {
                    element.addClass('text-danger');
                } else {
                    element.removeClass('text-danger');
                }
            }


            $(document).on('input paste', '.custom-row-wrapper input', function(e) {
                let sectionIndex = $(this).closest('.list--section').data('section-index');
                let index = $(this).closest('.custom-row-wrapper').index() - 1;
                let val = $(this).val();
                let className = $(this).data('class-type');

                updateSectionRowTextPreview(sectionIndex, index, val, className);
            });

            function updateSectionRowTextPreview(sectionIndex, index, val, className) {
                let $section = $(
                    `.message-preview-list__section_wrapper .message-preview-list__section[data-section-index="${sectionIndex}"]`
                );
                let $row = $section.find('.row_wrapper .message-preview-list__row').eq(index);
                let $title = $row.find(`.${className}`);
                let $desc = $row.find(`.${className}`);

                $title.text(val);
                $desc.text(val);
            }

            $(document).on('input paste keyup change',
                'input[name="footer"], input[name="name"], input[name="button_text"], input[name="header"], textarea[name="body"]',
                function() {
                    updateCharacterCount($(this));
                });

            $(document).on('input paste keyup change', 'input[name^="sections"][name$="[title]"]', function() {
                let sectionIndex = $(this).data('section-index');
                updateSectionTitlePreview(sectionIndex, $(this).val());
            });

            function updateSectionTitlePreview(sectionIndex, title) {
                $(`.message-preview-list__section_wrapper .message-preview-list__section[data-section-index="${sectionIndex}"] .message-preview-list__section-title`)
                    .text(title);
            }

            function updateSectionPreview(sectionIndex) {
                const sectionWrapper = $('.message-preview-list__section_wrapper');

                let newSection = `
                <div class="message-preview-list__section" data-section-index="${sectionIndex}">
                <p class="message-preview-list__section-title">Section 2 title</p>
                    <div class="row_wrapper">
                        <div class="message-preview-list__row">
                            <div class="message-preview-list__text">
                                <p class="title">Row title</p>
                                <p class="desc">Row description</p>
                            </div>
                            <span class="message-preview-list__radio"></span>
                        </div>

                        <div class="message-preview-list__row">
                            <div class="message-preview-list__text">
                                <p class="title">Row title</p>
                                <p class="desc">Row description</p>
                            </div>
                            <span class="message-preview-list__radio"></span>
                        </div>
                    </div>
                </div>
                `;
                sectionWrapper.append(newSection);
            }

            function updateSectionRowPreview(sectionIndex, rowIndex) {
                const $section = $(
                    `.message-preview-list__section_wrapper .message-preview-list__section[data-section-index="${sectionIndex}"]`
                ).find('.row_wrapper');

                let newRow = `
                <div class="message-preview-list__row">
                    <div class="message-preview-list__text">
                        <p class="title">Row title</p>
                        <p class="desc">Row description</p>
                    </div>
                    <span class="message-preview-list__radio"></span>
                </div>
                `;
                $section.append(newRow);
            }

            $(document).on('click', '.remove-list-button', function(e) {
                e.preventDefault();

                let sectionItem = $(this).closest('.list--section');
                let totalSection = $('body .list--section').length;

                if (totalSection <= 1) {
                    notify('error', "@lang('At least one list section is required')");
                    return;
                }
                let sectionIndex = sectionItem.data('section-index');

                sectionItem.remove();

                $('.message-preview-list__section_wrapper .message-preview-list__section[data-section-index="' +
                    sectionIndex + '"]').remove();
            });

            $(".button-text-preview").on("click", function() {
                $(".message-preview-list").addClass("active");
            });

            $(".close-preview-btn").on("click", function() {
                $(".message-preview-list").removeClass("active");
            });

            $('.preview-item__shape').on('click', function() {
                $(".message-preview-list").removeClass("active");
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .body-content {
            position: relative;
        }

        .add-variable {
            position: absolute;
            top: 5px;
            right: 5px;
            z-index: 1;
            height: 30px;
            width: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 1px solid #e8e8e8;
            border-radius: 50% !important;
        }

        .custom-row-wrapper {
            display: flex;
            width: 100%;
            gap: 10px;
            align-items: flex-end;
        }

        .template-info-container__right .preview-item__content {
            height: 400px !important;
        }

        .template-info-container__right .preview-item__content .card-item {
            width: 100%;
            border-radius: 10px;
        }

        .divider-title::after {
            position: absolute;
            content: '';
            top: 14px;
            right: -40px;
            background: #6b6b6b65;
            height: 2px;
            width: 80px;
        }

        .divider-title::before {
            position: absolute;
            content: '';
            top: 14px;
            left: -40px;
            background: #6b6b6b65;
            height: 2px;
            width: 80px;
        }

        .button-text-preview i {
            font-size: 18px !important;
        }

        .button-text-preview {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 5px !important;
            cursor: pointer !important;
        }

        .template-info-container__right .preview-item {
            position: sticky !important;
            top: calc(45vh - 150px) !important;
        }

        .message-preview-list {
            background: hsl(var(--white));
            position: absolute;
            top: 25px;
            left: 0;
            height: 100%;
            width: 100%;
            border-radius: 12px;
            overflow: auto;
            transform: translateY(100%);
            opacity: 0;
            transition: all 0.3s ease;
            pointer-events: none;
            scrollbar-width: thin;
            scrollbar-color: hsl(var(--base) / 0.8) hsl(var(--black) / 0.1);
        }

        .message-preview-list::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .message-preview-list.active {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        .message-preview-list__header {
            position: relative;
            padding: 12px 16px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        }

        .close-preview-btn {
            position: absolute;
            left: 16px;
            top: 12px;
            font-size: 20px;
            cursor: pointer;
        }

        .message-preview-list__header__title {
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }

        .message-preview-list__body {
            padding: 8px 0 16px;
        }

        .message-preview-list__section_wrapper {
            display: flex;
            flex-direction: column;
        }

        .message-preview-list__section-title {
            font-size: 16px;
            font-weight: 500;
            color: #555;
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
    </style>
@endpush
