@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Quickly add new contacts by completing the simple form below.')</p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <a class="btn btn--dark btn-shadow" href="{{ route('user.contact.list') }}"><i class="las la-list"></i>
                        @lang('Contact List')
                    </a>
                    <button class="btn btn--base btn-shadow" form="information-form" type="submit">
                        <i class="lab la-telegram"></i> @lang('Save Contact')
                    </button>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <div class="information-wrapper">
                <div class="row">
                    <div class="col-xxl-8">
                        <form id="information-form" action="{{ route('user.contact.store') }}" method="POST">
                            @csrf
                            <div class="row gy-2">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('First Name')</label>
                                        <input class="form--control form-two" name="firstname" type="text"
                                            value="{{ old('firstname') }}" placeholder="@lang('Enter firstname')" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Last Name')</label>
                                        <input class="form--control form-two" name="lastname" type="text"
                                            value="{{ old('lastname') }}" placeholder="@lang('Enter lastname')" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Country')</label>
                                        <select class="form--control select2  img-select2 form-two" name="mobile_code">
                                            @foreach ($countries as $key => $country)
                                                <option
                                                    data-src="{{ asset('assets/images/country/' . strtolower($key) . '.svg') }}"
                                                    value="{{ $country->dial_code }}" @selected(old('mobile_code'))>
                                                    {{ __($country->country) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label-two">@lang('Mobile')</label>
                                        <div class="input-group">
                                            <span class="input-group-text mobile-code">
                                            </span>
                                            <input class="form-control form--control form-two" name="mobile" type="number"
                                                value="{{ old('mobile') }}" value="{{ old('mobile') }}" required
                                                placeholder="@lang('Enter mobile number')">
                                        </div>
                                        <span class="contact-exists-error d-none"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between">
                                            <label class="label-two">@lang('Contact Tags')</label>
                                            <button class="add-btn fs-14 text-decoration-underline"
                                                data-title="@lang('Create New Tag')"
                                                data-route="{{ route('user.contacttag.save') }}" type="button">
                                                @lang('Add New')
                                            </button>
                                        </div>
                                        <select class="form--control select2 form-two contact-tag" name="tags[]"
                                            data-minimum-results-for-search="-1" data-placeholder="@lang('Choose contact tag')"
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
                                            <label class="label-two">@lang('Contact Lists')</label>
                                            <button class="add-btn fs-14 text-decoration-underline"
                                                data-title="@lang('Create New Contact List')"
                                                data-route="{{ route('user.contactlist.save') }}"
                                                type="button">@lang('Add New')
                                            </button>
                                        </div>
                                        <select class="form--control select2 form-two contact-list" name="lists[]"
                                            data-minimum-results-for-search="-1" data-placeholder="@lang('Choose contact list')"
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
                                    <div class="d-flex justify-content-between mb-1 flex-wrap gap-2">
                                        <label class="label-two d-inline-flex align-items-center">
                                            @lang('Custom Attributes')
                                            <i class="las la-info-circle text--info ms-1" data-bs-toggle="tooltip"
                                                title="@lang('Custom attributes are dynamic fields that let you store personalized or extra information').">
                                            </i>
                                        </label>
                                        <button class="fs-14 text-decoration-underline add-custom-attribute" type="button">
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
                                                        <input class="form--control form-two"
                                                            name="custom_attributes[name][]" type="text"
                                                            value="{{ $customAttribute['name'][$i] }}"
                                                            placeholder="@lang('Field Name')">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input class="form--control form-two"
                                                            name="custom_attributes[value][]" type="text"
                                                            value="{{ $customAttribute['value'][$i] }}"
                                                            placeholder="@lang('Field Value')">
                                                    </div>
                                                    <div class="col-md-1 d-flex align-items-center">
                                                        <button class="btn btn--danger remove-attribute w-100"
                                                            type="button">
                                                            <i class="las la-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endfor
                                        @else
                                            <div class="row custom-attribute-wrapper g-2 align-items-center mb-2">
                                                <div class="col-md-5">
                                                    <input class="form--control form-two" name="custom_attributes[name][]"
                                                        type="text" placeholder="@lang('Field Name')">
                                                </div>
                                                <div class="col-md-6">
                                                    <input class="form--control form-two"
                                                        name="custom_attributes[value][]" type="text"
                                                        placeholder="@lang('Field Value')">
                                                </div>
                                                <div class="col-md-1 d-flex align-items-center">
                                                    <button class="btn btn--danger remove-attribute w-100" type="button">
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
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <form class="no-submit-loader" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="label-two">@lang('Name')</label>
                            <input class="form--control form-two" name="name" type="text" required>
                        </div>
                        <div class="form-group">
                            <button class="btn btn--base w-100" type="submit">
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
    <link href="{{ asset('assets/global/css/select2.min.css') }}" rel="stylesheet">
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            const $modal = $('.add-modal');


            $('select[name=mobile_code]').on('change', function() {
                $('.mobile-code').text("+" + $(this).val());
            }).change();

            $('.add-custom-attribute').on('click', function(e) {
                e.preventDefault();

                let html = `<div class="row custom-attribute-wrapper g-2 align-items-center mb-2">
                        <div class="col-md-5">
                            <input type="text" name="custom_attributes[name][]" class="form--control form-two" placeholder="Filed Name">
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
