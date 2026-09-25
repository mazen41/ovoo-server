@props(['form' => null, 'generateTitle', 'formTitle', 'formSubtitle', 'generateSubTitle', 'randerbtn' => false])

<x-admin.ui.card>
    <x-admin.ui.card.body class="p-0">
        <div class="row responsive-row mb-0">
            <div class="col-lg-5">
                <div class="form-field__wrapper">
                    <div class="form-field__header">
                        <h5 class="mb-0">{{ __(@$formTitle) }}</h5>
                        @if (@$formSubtitle)
                            <p class="mt-1 mb-0 text-muted">{{ __($formSubtitle) }}</p>
                        @endif
                    </div>
                    <div class="addedField simple_with_drop">
                        @if ($form && $form->form_data)
                            @foreach ($form->form_data as $formData)
                                <div class="form-field-wrapper" id="{{ $loop->index }}">
                                    <input type="hidden" name="form_generator[is_required][]"
                                        value="{{ $formData->is_required }}">
                                    <input type="hidden" name="form_generator[extensions][]"
                                        value="{{ $formData->extensions }}">
                                    <input type="hidden" name="form_generator[options][]"
                                        value="{{ implode(',', $formData->options) }}">
                                    <input type="hidden" name="form_generator[form_width][]"
                                        value="{{ @$formData->width }}">
                                    <input type="hidden" name="form_generator[form_label][]" class="form-control"
                                        value="{{ $formData->name }}">
                                    <input type="hidden" name="form_generator[instruction][]" class="form-control"
                                        value="{{ @$formData->instruction }}">
                                    <input type="hidden" name="form_generator[form_type][]" class="form-control"
                                        value="{{ $formData->type }}">
                                    @php
                                        $jsonData = json_encode([
                                            'type' => $formData->type,
                                            'is_required' => $formData->is_required,
                                            'instruction' => @$formData->instruction,
                                            'label' => $formData->name,
                                            'extensions' => explode(',', $formData->extensions) ?? 'null',
                                            'options' => $formData->options,
                                            'width' => @$formData->width,
                                            'old_id' => '',
                                        ]);
                                    @endphp
                                    <div class="form-field">
                                        <div class="form-field__icon">
                                            <i class="las la-braille"></i>
                                        </div>
                                        <div>
                                            <p class="form-field__name-title">{{ __(@$formData->name) }}</p>
                                            <div class="flex-align gap-md-3 gap-2">
                                                @if ($formData->is_required == 'required')
                                                    <span class="badge badge--success">@lang('Required')</span>
                                                @else
                                                    <span class="badge badge--dark">@lang('Optional')</span>
                                                @endif
                                                <div class="form-field__item gap-0 align-self-end">
                                                    <div class="form-field__info">
                                                        <p class="title">@lang('Type: ')</p>
                                                        <p class="value">{{ __(ucfirst($formData->type)) }}</p>
                                                    </div>
                                                    <div class="form-field__info">
                                                        <p class="title">@lang('Width: ')</p>
                                                        <p class="value">
                                                            @if (@$formData->width == '12')
                                                                @lang('100%')
                                                            @elseif(@$formData->width == '6')
                                                                @lang('50%')
                                                            @elseif(@$formData->width == '4')
                                                                @lang('33%')
                                                            @elseif(@$formData->width == '3')
                                                                @lang('25%')
                                                            @else
                                                                -
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-field__item">
                                            <button type="button" class="btn btn--primary  editFormData"
                                                data-form_item="{{ $jsonData }}"
                                                data-update_id="{{ $loop->index }}">
                                                <i class="las la-pen me-0"></i>
                                            </button>
                                            <button type="button" class="btn btn--danger  removeFormData">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <div
                            class="p-5 flex-center flex-column h-100 empty-message-wrapper @if ($form && $form->form_data) d-none @endif">
                            <img src="{{ asset('assets/images/empty_box.png') }}" class="empty-message">
                            <span class="d-block fs-13 text-muted">@lang('There are no available fields to display on this form at the moment.')</span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-7">
                <div class="form-edit__wrapper">
                    <div class="form-edit__header pb-0">
                        <h5 class='form-edit__header-title mb-0'>{{ __(@$generateTitle) }}</h5>
                        @if (@$generateSubTitle)
                            <p class="mt-1 mb-0 text-muted">{{ __($generateSubTitle) }}</p>
                        @endif
                    </div>
                    <div class="form-generator-filed-area position-relative">
                        <div class="form-edit__body">
                            <input type="hidden" form="generate-form" name="update_id" value="" />
                            <div class="form-group">
                                <label>@lang('Type')</label>
                                <select form="generate-form" name="form_type" class="form-control select2"
                                    data-minimum-results-for-search="-1" required>
                                    <option value="">@lang('Select One')</option>
                                    <option value="text">@lang('Text')</option>
                                    <option value="email">@lang('Email')</option>
                                    <option value="number">@lang('Number')</option>
                                    <option value="url">@lang('URL')</option>
                                    <option value="datetime">@lang('Date & Time')</option>
                                    <option value="date">@lang('Date')</option>
                                    <option value="time">@lang('Time')</option>
                                    <option value="textarea">@lang('Textarea')</option>
                                    <option value="select">@lang('Select')</option>
                                    <option value="checkbox">@lang('Checkbox')</option>
                                    <option value="radio">@lang('Radio')</option>
                                    <option value="file">@lang('File')</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('Is Required')</label>
                                <select form="generate-form" name="is_required" class="form-control select2"
                                    data-minimum-results-for-search="-1" required>
                                    <option value="">@lang('Select One')</option>
                                    <option value="required">@lang('Required')</option>
                                    <option value="optional">@lang('Optional')</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('Label')</label>
                                <input type="text" form="generate-form" name="form_label" class="form-control"
                                    required>
                            </div>
                            <div class="form-group extra_area">

                            </div>
                            <div class="form-group">
                                <label>@lang('Width')</label>
                                <select form="generate-form" name="form_width" class="form-control select2"
                                    data-minimum-results-for-search="-1" required>
                                    <option value="">@lang('Select One')</option>
                                    <option value="12">@lang('100%')</option>
                                    <option value="6">@lang('50%')</option>
                                    <option value="4">@lang('33%')</option>
                                    <option value="3">@lang('25%')</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('Instruction') <small>@lang('(if any)')</small></label>
                                <input type="text" form="generate-form" name="instruction" class="form-control">
                            </div>
                            <button form="generate-form" type="submit"
                                class="btn btn--primary btn-large generatorSubmit">
                                <i class="las la-plus"></i> @lang('Add')
                            </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($randerbtn)
            <div class="p-3">
                <x-admin.ui.btn.submit />
            </div>
        @endif
    </x-admin.ui.card.body>
</x-admin.ui.card>

@push('script-lib')
    <script src="{{ asset('assets/admin/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/form_generator.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict"
        $("body").append(`
        <form class="generate-form no-submit-loader position-relative" id="generate-form">
        </form>`);

        var formGenerator = new FormGenerator();
        @if ($form)
            formGenerator.totalField = {{ $form ? count((array) $form->form_data) : 0 }}
        @endif

        $(".simple_with_drop").sortable({
            stop: function(event, ui) {
                var start = ui.item.data('start');
                var end = ui.item.index();
                if (start !== end) {
                    $('.submitRequired').removeClass('d-none');
                }
            },
            start: function(event, ui) {
                ui.item.data('start', ui.item.index());
            },
            containment: $(".addedField"),
        });
    </script>
    <script src="{{ asset('assets/global/js/form_actions.js') }}"></script>
@endpush

@push('style')
    <style>
        .form-edit__header-title {
            margin-bottom: 10px;
        }

        .form-edit__header-field {
            font-size: 16px;
            color: hsl(var(-secondary));
            font-family: var(--heading-font);
        }

        .form-field__header,
        .form-field__footer,
        .form-edit__body,
        .form-edit__footer,
        .form-edit__header {
            padding: var(--space);
        }

        .form-field__icon {
            font-size: 1.5rem;
        }

        .form-field {
            display: flex;
            align-items: center;
            border-bottom: 1px solid hsl(var(--border-color));
            padding: 15px 24px;
            border-radius: 5px;
            cursor: grab;
            background: hsl(var(--white));
            gap: 16px;
            position: relative;
        }
        [data-theme=dark]   .form-field {
            
            background: hsl(var(--light));
       }

        .active .form-field {
            background: hsl(var(--primary)/0.05)
        }

        .active .form-field::before {
            position: absolute;
            content: '';
            right: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: hsl(var(--primary));
        }

        .form-field .value {
            font-size: 14px;
            color: hsl(var(--black));
            font-weight: 500;
        }

        .form-field .title {
            font-size: 14px;
            color: hsl(var(--secondary));
            font-weight: 500;
        }

        .form-field__item {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .addedField.simple_with_drop {
            overflow-x: auto;
            max-width: 90vw;
        }

        .form-field-wrapper {
            min-width: 600px;

        }

        .form-field .form-field__item:last-child {
            text-align: right;
            margin-left: auto;
        }

        .form-field__info {
            display: flex;
            align-items: flex-end;
            font-size: 14px;
            gap: 5px;
        }

        .form-field__info:not(:last-child) {
            padding-right: 12px;
            border-right: 1px solid hsl(var(--border-color));
            margin-right: 12px
        }

        .submitRequired {
            cursor: unset;
        }

        .form-field__wrapper {
            overflow-x: auto;
            margin-bottom: 10px;
        }

        .form-field__name-title {
            font-size: 1rem;
            font-weight: 500;
            font-family: var(--heading-font);
            margin-bottom: 8px;
        }

        .form-field__wrapper {
            border-right: 1px solid hsl(var(--border-color));
        }
    </style>
@endpush
