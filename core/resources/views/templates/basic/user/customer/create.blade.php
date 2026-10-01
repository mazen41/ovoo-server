@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Quickly add new customer by completing the simple form below.')</p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <a href="{{ route('user.customer.list') }}" class="btn btn--dark btn-shadow"><i class="las la-list"></i>
                        @lang('Customer List')
                    </a>
                    <button type="submit" form="information-form" class="btn btn--base btn-shadow">
                        <i class="lab la-telegram"></i> @lang('Save Customer')
                    </button>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <div class="information-wrapper">
                <div class="row">
                    <div class="col-xxl-8">
                        <form action="{{ route('user.customer.store') }}" method="POST" id="information-form">
                            @csrf
                            <div class="row gy-2">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('First Name')</label>
                                        <input type="text" class="form--control form-two" name="firstname"
                                            placeholder="@lang('Enter firstname')" value="{{ old('firstname') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Last Name')</label>
                                        <input type="text" class="form--control form-two" name="lastname"
                                            placeholder="@lang('Enter lastname')" required value="{{ old('lastname') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Country')</label>
                                        <select class="form--control select2 form-two" name="mobile_code">
                                            @foreach ($countries as $key => $country)
                                                <option value="{{ $country->dial_code }}" @selected(old('mobile_code'))>
                                                    {{ __($country->country) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Mobile')</label>
                                        <div class="input-group ">
                                            <span class="input-group-text mobile-code">
                                            </span>
                                            <input type="number" name="mobile" value="{{ old('mobile') }}"
                                                class="form-control form--control form-two" value="{{ old('mobile') }}"
                                                required placeholder="@lang('Enter mobile number')">
                                        </div>
                                        <span class="contact-exists-error d-none"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between">
                                            <label class="label-two">@lang('Customer Tags')</label>
                                            <button type="button" data-title="@lang('Create New Tag')"
                                                data-route="{{ route('user.contacttag.save') }}"
                                                class="add-btn fs-14 text-decoration-underline">
                                                @lang('Add New')
                                            </button>
                                        </div>
                                        <select name="tags[]" class="form--control select2 form-two contact-tag"
                                            data-minimum-results-for-search="-1" data-placeholder="@lang('Choose customer tag')"
                                            multiple>
                                            @foreach ($contactTags as $tag)
                                                <option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tags', [])))>
                                                    {{ __(@$tag->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between">
                                            <label class="label-two">@lang('Customer Lists')</label>
                                            <button type="button" data-title="@lang('Create New Customer List')"
                                                class="add-btn fs-14 text-decoration-underline"
                                                data-route="{{ route('user.contactlist.save') }}">@lang('Add New')
                                            </button>
                                        </div>
                                        <select name="lists[]" class="form--control select2 form-two contact-list"
                                            data-minimum-results-for-search="-1" data-placeholder="@lang('Choose customer list')"
                                            multiple>
                                            @foreach ($contactLists as $list)
                                                <option value="{{ $list->id }}" @selected(in_array($list->id, old('lists', [])))>
                                                    {{ __(@$list->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="d-flex gap-2 flex-wrap justify-content-between mb-1">
                                        <label class="label-two d-inline-flex align-items-center">
                                            @lang('Custom Attributes')
                                            <i class="las la-info-circle ms-1 text--info" data-bs-toggle="tooltip"
                                                title="@lang('Custom attributes are dynamic fields that let you store personalized or extra information').">
                                            </i>
                                        </label>
                                        <button type="button" class="fs-14 text-decoration-underline add-custom-attribute">
                                            @lang('More Attribute')
                                        </button>
                                    </div>
                                    <div class="custom-attributes-wrapper">
                                        @if (count(old('custom_attributes', [])))
                                            @for ($i = 0; $i < count(old('custom_attributes')['name']); $i++)
                                                <div class="row custom-attribute-wrapper g-2 align-items-center mb-2">

                                                    @php
                                                        $customAttribute = old('custom_attributes');
                                                    @endphp
                                                    <div class="col-md-5">
                                                        <input type="text" name="custom_attributes[name][]"
                                                            class="form--control form-two" placeholder="@lang('Field Name')"
                                                            value="{{ $customAttribute['name'][$i] }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" name="custom_attributes[value][]"
                                                            class="form--control form-two"
                                                            placeholder="@lang('Field Value')"
                                                            value="{{ $customAttribute['value'][$i] }}">
                                                    </div>
                                                    <div class="col-md-1 d-flex align-items-center">
                                                        <button type="button"
                                                            class="btn btn--danger remove-attribute w-100">
                                                            <i class="las la-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endfor
                                        @else
                                            <div class="row custom-attribute-wrapper g-2 align-items-center mb-2">
                                                <div class="col-md-5">
                                                    <input type="text" name="custom_attributes[name][]"
                                                        class="form--control form-two" placeholder="@lang('Field Name')">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" name="custom_attributes[value][]"
                                                        class="form--control form-two" placeholder="@lang('Field Value')">
                                                </div>
                                                <div class="col-md-1 d-flex align-items-center">
                                                    <button type="button" class="btn btn--danger remove-attribute w-100">
                                                        <i class="las la-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade custom--modal add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="no-submit-loader">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="label-two">@lang('Name')</label>
                            <input type="text" class="form--control form-two" name="name" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn--base w-100">
                                <i class="lab la-telegram"></i> @lang('Submit')
                            </button>
                        </div>
                    </form>
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
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            const $modal = $('.add-modal');

            $('.select2').select2();

            $('select[name=mobile_code]').on('change', function() {
                $('.mobile-code').text("+" + $(this).val());
            }).change();

            $('.add-custom-attribute').on('click', function(e) {
                e.preventDefault();

                let html = `<div class="row custom-attribute-wrapper g-2 align-items-center mb-2">
                        <div class="col-md-5">
                            <input type="text" name="custom_attributes[name][]" class="form--control form-two" placeholder="File Name">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="custom_attributes[value][]" class="form--control form-two" placeholder="Filed Value">
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button type="button" class="btn btn--danger remove-attribute w-100">
                                <i class="las la-trash"></i>
                            </button>
                        </div>
                    </div>`;

                $('.custom-attributes-wrapper').append(html);
            });

            $('.custom-attributes-wrapper').on('click', '.remove-attribute', function(e) {
                e.preventDefault();
                $(this).parent().parent().remove();
            });

            $('.add-btn').on('click', function() {
                const route = $(this).data('route');
                const title = $(this).data('title');
                $modal.find('form').trigger('reset');
                $modal.find('form').attr('action', route);
                $modal.find('.modal-title').text(title);
                $modal.modal('show');
            });

            $modal.on('submit', "form", function(e) {
                e.preventDefault();
                var $this = $(this);
                var data = $this.serialize();
                $.post($this.attr('action'), data, function(response) {
                    const notifyMessage = response.message || "@lang('Contact List')";
                    if (response.status == 'success') {
                        const data = response.data;
                        const option =
                            `<option value="${data.data.id}" selected>${data.data.name}</option`;
                        $(`.${data.type}`).append(option).trigger('change');
                        $modal.modal('hide');
                        $this.trigger('reset');
                        notify('success', notifyMessage);
                    } else {
                        notify('error', notifyMessage);
                    }

                })
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .add-btn {
            cursor: pointer;
        }

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
