@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Send bulk message to your contact list.')</p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <a href="{{ route('user.template.index') }}" class="btn btn--dark">@lang('Back')</a>
                    <button type="submit" form="message-form" class="btn btn--base btn-shadow">@lang('Send')</button>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <div class="information-wrapper">
                <h5 class="title page-title">@lang('Send template message  - ') {{ __(@$selectedTemplate->name) }}</h5>
                <div class="template-info-container">
                    <div class="template-info-container__left">
                        <form action="{{ route('user.template.message.send') }}" method="POST" id="message-form">
                            @csrf
                            <div class="row gy-2">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Choose template')</label>
                                        <select name="template_id" class="form--control select2 form-two"
                                            data-minimum-results-for-search="-1" required>
                                            <option value="" disabled selected>@lang('Select one')</option>
                                            @foreach (@$templates as $template)
                                                <option value="{{ @$template->whatsapp_template_id }}"
                                                    data-name="{{ @$template->name }}" data-template="{{ @$template }}"
                                                    data-body="{{ @$template->body }}" @selected($selectedTemplate->whatsapp_template_id == $template->whatsapp_template_id)>
                                                    {{ @$template->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div id="template-variables"></div>
                                </div>
                                <div class="col-12">
                                    @foreach (variableShortCodes() as $key => $value)
                                        <span class="btn btn--sm btn--dark code-btn"
                                            data-code="{{ $value }}">{{ $value }}</span>
                                    @endforeach
                                </div>
                                <div class="col-xxl-6 col-xl-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Country')</label>
                                        <select class="form--control select2 form-two" name="country">
                                            @foreach ($countries as $key => $country)
                                                <option data-mobile_code="{{ $country->dial_code }}"
                                                    value="{{ $country->country }}" data-code="{{ $key }}">
                                                    {{ __($country->country) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-xl-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Mobile')</label>
                                        <div class="input-group ">
                                            <span class="input-group-text mobile-code">
                                            </span>
                                            <input type="hidden" name="mobile_code">
                                            <input type="hidden" name="country_code">
                                            <input type="number" name="mobile" value="{{ old('mobile') }}"
                                                class="form-control form--control form-two checkContact">
                                        </div>
                                        <span class="contact-exists-error d-none"></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Choose contact')</label>
                                        <select name="contact_lists[]" class="form--control select2 form-two contactList"
                                            data-minimum-results-for-search="-1" data-placeholder="Choose contact list"
                                            multiple>
                                            @foreach ($contactLists as $list)
                                                <option value="{{ $list->id }}">
                                                    {{ __(@$list->name) . ' (' . $list->contact->count() . ')' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="template-info-container__right">
                        <div class="preview-item">
                            <div class="preview-item__header">
                                <h5 class="preview-item__title template__name">@lang('Template preview')</h5>
                            </div>
                            <div class="preview-item__content">
                                <div class="preview-item__shape">
                                    <img src="{{ getImage($activeTemplateTrue . 'images/preview-1.png') }}" alt="img">
                                </div>
                                <div class="card-item">
                                    <div class="card-item__thumb">
                                        <img src="{{ getImage($activeTemplateTrue . 'images/preview-1.png') }}"
                                            alt="img">
                                    </div>
                                    <div class="card-item__content">
                                        <p class="card-item__title template__header">@lang('Template header')</p>
                                        <p class="card-item__desc template__body">
                                            @lang('Template body')
                                        </p>
                                        <p class="text-wrapper">
                                            <span class="text template__footer">@lang('Template footer')</span>
                                        </p>
                                    </div>
                                    <div class="card-item__bottom">
                                        <a href="#" class="btn-link">
                                            <span class="btn-link__icon"> <i class="fa-solid fa-globe"></i> </span>
                                            @lang('Visit Website')
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

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
    </link>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            let dynamicVariableFiledElement = null;

            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif

            $('.select2').select2();

            $('select[name=country]').on('change', function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
                var mobile = $('[name=mobile]').val();
            });

            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));

            function generateParamsField() {
                let templateName = $(this).find(':selected').data('name');
                let templateBody = $(this).find(':selected').data('body');

                let matches = templateBody.match(/\{\{\d+\}\}/g);
                let totalMatches = matches ? matches : [];
                $('.page-title').text('Send template message - ' + templateName);

                $('#template-variables').empty();

                if (totalMatches.length > 0) {
                    let html = ``;
                    $.each(totalMatches, function(index, value) {
                        html += `
                            <div class="form-group">
                                <label class="label-two">Variable ${value}</label>
                                <input type="text" data-name="${value}" name="variables[${value}]" class="form--control form-two dynamic-filed"  placeholder="Enter value for ${value}" required>
                            </div>
                        `
                    });
                    $('#template-variables').html(html);
                }
            }

            $("body").on('focusout', ".dynamic-filed", function(e) {
                dynamicVariableFiledElement = $(this);
            });

            $('select[name=template_id]').on('change', function() {
                templatePreview();
                generateParamsField.call(this);
            });

            generateParamsField.call($('select[name=template_id]'));

            $('.code-btn').on('click', function() {
                let code = $(this).data('code');
                if (dynamicVariableFiledElement == null) {
                    dynamicVariableFiledElement = $('body').find(`.dynamic-filed`).first();
                }
                dynamicVariableFiledElement.val(code);
            });

            function templatePreview(body = null) {
                let template = $('select[name=template_id] :selected').data('template');
                $('.template__name').text(template.name);
                $('.template__header').text(template.header);
                if (body) {
                    $('.template__body').text(body);
                } else {
                    $('.template__body').text(template.body);
                }
                $('.template__footer').text(template.footer);
            }

            templatePreview();

            $('body').on('input', '.dynamic-filed', function() {
                let key = $(this).data('name');
                let value = $(this).val();
                if (value == '') return;
                let templateBody = $('select[name=template_id] :selected').data('body');
                templateBody = templateBody.replace(key, value);
                templatePreview(templateBody);
            });

            $('#campaign-form').on("submit", function(e) {
                e.preventDefault();
                let form = $(this);
                let url = form.attr('action');
                let data = form.serialize();
                $.post(url, data, function(response) {
                    if (!response.success) {
                        notify('error', response.message);
                    } else {
                        form.find('select').val('').trigger('change');
                        form.trigger('reset');
                        notify('success', response.message);
                    }
                });
            });

        })(jQuery);
    </script>
@endpush
@push('style')
    <style>
        .select2+.select2-container .select2-selection.select2-selection--multiple {
            background: hsl(var(--section-bg));
            border-radius: 8px !important;
        }

        .dashboard-container .select2+.select2-container .select2-selection.select2-selection--multiple {
            border: 1px solid #c1c9d033 !important;
        }

        .dashboard-container .select2+.select2-container.select2-container--open .select2-selection__rendered,
        .dashboard-container .select2+.select2-container.select2-container--focus .select2-selection.select2-selection--multiple,
        .dashboard-container .select2+.select2-container.select2-container--open .select2-selection.select2-selection--multiple {
            border: 1px solid hsl(var(--base)) !important;
        }

        .select2+.select2-container .select2-selection--multiple .select2-search.select2-search--inline {
            line-height: 28px;
        }

        .select2+.select2-container .select2-selection--multiple .select2-selection__rendered {
            line-height: 25px;
            box-shadow: unset !important;
            background: transparent !important;
            padding-right: 8px;
        }

        .dashboard-container .select2+.select2-container .select2-selection--multiple .select2-selection__rendered {
            border: 0 !important;
        }

        .select2-container--default .select2-search__field {
            border-radius: 4px;
        }

        .select2-container--open .select2-dropdown {
            border-radius: 4px !important;
        }

        .select2-results__options::-webkit-scrollbar {
            width: 0px;
        }

        .select2-search__field {
            background-color: hsl(var(--section-bg)) !important;
        }

        .select2-selection--multiple .select2-search__field {
            background-color: transparent !important;
        }

        .select2+.select2-container:has(.select2-selection.select2-selection--multiple) {
            height: auto;
        }
    </style>
@endpush
