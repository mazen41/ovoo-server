@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="alert alert--info alert-dismissible mb-3 template-requirements" role="alert">
        <div class="alert__content">
            <h4 class="alert__title"><i class="las la-info-circle"></i> @lang('CTA URL Information')</h4>
            <ul class="ms-4">
                <li class="mb-0 text-dark">@lang('The header can be Image or Text.The maximum text length is 60 characters.')</li>
                <li class="mb-0 text-dark">@lang('Body text will be hyperlinked to the website URL automatically. Maximum text length is 1024 characters.')</li>
                <li class="mb-0 text-dark">@lang('Button text can be contained a maximum of 20 characters.')</li>
                <li class="mb-0 text-dark">@lang('The URL will be your choice, please make sure it\'s a valid one. This URL will be opened in customers browsers.')</li>
            </ul>
        </div>
    </div>
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Creating a user-friendly CTA URL is an excellent way to communicate with customers and prospects.')</p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <a href="{{ route('user.cta-url.index') }}" class="btn btn--dark">
                        <i class="las la-undo"></i>
                        @lang('Back')
                    </a>
                    <button class="btn btn--base btn-shadow submitBtn" type="submit" form="cta-form">
                        <i class="lab la-telegram"></i> @lang('Save CTA URL')
                    </button>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <div class="template-info-container">
                <div class="template-info-container__left">
                    <form action="{{ route('user.cta-url.store') }}" method="POST" id="cta-form"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="label-two">@lang('CTA URL Name')</label>
                                    <input type="text" class="form--control form-two" name="cta_url_name"
                                        placeholder="@lang('Enter a unique name')" value="{{ old('cta_url_name') }}" maxlength="20"
                                        required>
                                    <div class="d-flex justify-content-end fs-12 pt-2 text-muted">
                                        <span class="character-count" data-limit="20">0</span>
                                        <span>/ 20</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="label-two">
                                        @lang('URL')
                                        <span class="las la-question-circle" data-bs-toggle="tooltip"
                                            data-bs-title="@lang('Please enter a valid URL that will be used to redirect customers after clicking on the button.')"></span>
                                    </label>
                                    <input type="text" class="form--control form-two" name="cta_url"
                                        placeholder="@lang('Enter the URL')" value="{{ old('cta_url') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="my-4">
                                <div class="row justify-content-center">
                                    <div class="col-lg-6">
                                        <div class="auth-devider p-0 text-center">
                                            <span> @lang('HEADER')</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="label-two">@lang('Header Type')</label>
                                <select name="header_format" class="form--control select2"
                                    data-minimum-results-for-search="-1">
                                    <option value="IMAGE">@lang('Image')</option>
                                    <option value="TEXT">@lang('Text')</option>
                                </select>
                            </div>
                            <div class="header-filed"></div>
                        </div>
                        <div class="col-12">
                            <div class="my-4">
                                <div class="row justify-content-center">
                                    <div class="col-lg-6">
                                        <div class="auth-devider p-0 text-center">
                                            <span> @lang('BODY CONTENT')</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="label-two">@lang('Body Content')</label>
                                <div class="body-content">
                                    <textarea class="form--control form-two" name="message_body" id="message_body" maxlength="1024"
                                        placeholder="@lang('Write your message body.')" required>{{ old('message_body') }}</textarea>
                                </div>
                                <div class="d-flex justify-content-end fs-12 text-muted">
                                    <span class="character-count" data-limit="1024">0</span>
                                    <span>/ 1024</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="my-4">
                                <div class="row justify-content-center">
                                    <div class="col-lg-6">
                                        <div class="auth-devider p-0 text-center">
                                            <span> @lang('FOOTER')</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="title" class="form--label label-two">@lang('Footer Text')</label>
                                <input type="text" class="form--control form-two" name="footer" maxlength="60"
                                    placeholder="@lang('Enter footer text')" value="{{ old('footer') }}">
                                <div class="d-flex justify-content-end fs-12 pt-2 text-muted">
                                    <span class="character-count" data-limit="60">0</span>
                                    <span>/ 60</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="my-4">
                                <div class="row justify-content-center">
                                    <div class="col-lg-6">
                                        <div class="auth-devider p-0 text-center">
                                            <span> @lang('BUTTON')</span>
                                        </div>
                                    </div>
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
                                    <div class="card-item__thumb header_media">
                                        <img src="{{ getImage($activeTemplateTrue . 'images/preview-1.png') }}"
                                            alt="image">
                                    </div>
                                    <div class="card-item__content">
                                        <p class="card-item__title header_text">@lang('Message Header')</p>
                                        <p class="card-item__desc body_text fs-12">@lang('Message body')</p>
                                        <p class="text-wrapper">
                                            <span class="text footer_text">@lang('Footer text')</span>
                                            <span class="text time-preview">{{ date('h:i A') }}</span>
                                        </p>
                                    </div>
                                    <div class="button-preview mt-2 border-top text-center p-2">
                                        <a href="#" class="button-text-preview" target="_blank">
                                            <svg viewBox="0 0 19 18" height="18" width="19"
                                                preserveAspectRatio="xMidYMid meet" version="1.1">
                                                <path
                                                    d="M14,5.41421356 L9.70710678,9.70710678 C9.31658249,10.0976311 8.68341751,10.0976311 8.29289322,9.70710678 C7.90236893,9.31658249 7.90236893,8.68341751 8.29289322,8.29289322 L12.5857864,4 L10,4 C9.44771525,4 9,3.55228475 9,3 C9,2.44771525 9.44771525,2 10,2 L14,2 C15.1045695,2 16,2.8954305 16,4 L16,8 C16,8.55228475 15.5522847,9 15,9 C14.4477153,9 14,8.55228475 14,8 L14,5.41421356 Z M14,12 C14,11.4477153 14.4477153,11 15,11 C15.5522847,11 16,11.4477153 16,12 L16,13 C16,14.6568542 14.6568542,16 13,16 L5,16 C3.34314575,16 2,14.6568542 2,13 L2,5 C2,3.34314575 3.34314575,2 5,2 L6,2 C6.55228475,2 7,2.44771525 7,3 C7,3.55228475 6.55228475,4 6,4 L5,4 C4.44771525,4 4,4.44771525 4,5 L4,13 C4,13.5522847 4.44771525,14 5,14 L13,14 C13.5522847,14 14,13.5522847 14,13 L14,12 Z"
                                                    fill="currentColor" fill-rule="nonzero"></path>
                                            </svg>
                                            <span class="button-text text--base">@lang('Button text')</span>
                                        </a>
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

            const generateHtml = {
                templateHeaderTypeHtml: function(selectedType) {
                    if (selectedType == 'TEXT') {
                        $('body').find('.header_media').addClass('d-none');
                        $('body').find('.header_text').removeClass('d-none');
                        return `<div class="form-group position-relative">
                        <label class="form--label label-two">@lang('Header Text')</label>
                        <input type="text" class="form--control form-two header-input" name="header[text]" placeholder="@lang('Enter header text')" maxlength="60">
                        <div class="d-flex justify-content-end fs-12 pt-2 text-muted">
                            <span class="character-count" data-limit="60">0</span>
                            <span>/ 60</span>
                        </div>
                    </div>`;
                    } else {
                        $('body').find('.header_media').removeClass('d-none');
                        $('body').find('.header_text').addClass('d-none');
                        return `<div class="form-group">
                        <label class="form--label label-two required">@lang('Header image')</label>
                        <input type="file" class="form--control form-two" name="header[image]" accept="image/*" required>
                    </div>`;
                    }
                }
            }

            const handleInput = (selector, callback) => $('body').on('input paste', selector, callback);

            handleInput('input[name=cta_url]', function(e) {
                $('.button-preview').find('a').attr('href', $(this).val());
            });

            handleInput('input[name=button_text]', function(e) {
                $('.button-text').text($(this).val());
            });

            handleInput("input[name='header[text]']", function(e) {
                $('.header_text').text($(this).val() || "Message Header");
            });

            handleInput('textarea[name=message_body]', function(e) {
                $('.body_text').text($(this).val() || "Message Body");
            });

            handleInput('input[name=footer]', function(e) {
                $('.footer_text').text($(this).val() || "Message Footer");
            });

            $('select[name=header_format]').on('change', function() {
                $('.header-filed').html(generateHtml.templateHeaderTypeHtml($(this).val()));
            }).change();

            $(document).on('input change', 'input[name="header[image]"]', function() {
                const fileInput = this;

                if (fileInput.files && fileInput.files[0]) {
                    const file = fileInput.files[0];

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $('.header_media').html(
                                `<img src="${e.target.result}" alt="Image" width="100%">`
                            );
                        };
                        reader.readAsDataURL(file);
                    } else {
                        notify('error', "@lang('Please select a valid image.')");
                        $('.header_media').html('');
                    }
                } else {
                    $('.header_media').html('');
                }
            });

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

            $(document).on('input paste keyup change',
                'input[name="header[text]"], input[name="footer"], input[name="button_text"], input[name="cta_url_name"], textarea[name="message_body"]',
                function() {
                    updateCharacterCount($(this));
                });

            $('input[name="header[text]"], input[name="footer"], textarea[name="message_body"]').each(function() {
                updateCharacterCount($(this));
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .form--control[type=file] {
            line-height: 1 !important;
            padding: 8px 2px !important;
            height: 40px;
        }

        .form--control[type=file]::-webkit-file-upload-button {
            padding: unset !important;
        }

        .form--control[type=file]::file-selector-button {
            padding: unset !important;
        }

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

        .dropdown-menu {
            background: hsl(var(--section-bg));
            border-radius: 6px;
            border: 0;
            padding: 0 !important;
            overflow: hidden;
        }

        .dropdown-menu li .dropdown-item {
            color: hsl(var(--black)) !important;
            cursor: pointer;
            margin: 0;
            padding: 8px 14px;
            border-bottom: 1px solid hsl(var(--black)/.1);
            transition: .2s linear;
        }

        .dropdown-menu li:last-child .dropdown-item {
            border-bottom: 0;
        }

        .dropdown-menu li .dropdown-item:hover {
            background-color: hsl(var(--base)/.2);
        }

        .custom-attribute-wrapper {
            display: flex;
            width: 100%;
            gap: 10px;
            align-items: flex-end;
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

        .template-info-container__right .preview-item {
            position: sticky !important;
            top: calc(45vh - 150px) !important;
        }
    </style>
@endpush
