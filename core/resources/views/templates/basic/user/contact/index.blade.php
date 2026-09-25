@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Organize and manage your contact with effortless ease.')</p>
            </div>
            <x-permission_check permission="add contact">
                <div class="container-top__right">
                    <div class="btn--group">
                        <a href="{{ route('user.contact.create') }}" class="btn btn--base btn-shadow">
                            <i class="las la-plus"></i> @lang('Add New')
                        </a>
                        <button type="button" class="btn btn--info btn-shadow importBtn">
                            <i class="las la-upload"></i>
                            @lang('Import Contacts')
                        </button>
                        <button type="button" class="btn btn--danger btn-shadow  disabled bulk-delete confirmationBtn"
                            disabled data-question="@lang('Are you sure to delete selected contacts?')"
                            data-action="{{ route('user.contact.bulk.delete') }}">
                            <i class="las la-trash"></i>
                            @lang('Delete Selected')
                        </button>
                    </div>
                </div>
            </x-permission_check>
        </div>
        <div class="dashboard-container__body">
            <div class="body-top">
                <div class="body-top__left">
                    <form class="search-form">
                        <input type="search" class="form--control" name="search" placeholder="@lang('Search here...')"
                            value="{{ request()->search }}" autocomplete="off">
                        <span class="search-form__icon"> <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                    </form>
                </div>
                <div class="body-top__right">
                    <form class="select-group filter-form">
                        <select class="form-select form--control select2" name="tag_id">
                            <option selected value="">@lang('Filter Tag')</option>
                            @foreach ($contactTags as $tag)
                                <option value="{{ $tag->id }}" @selected(request()->tag_id == $tag->id)>
                                    {{ __(@$tag->name) }}
                                </option>
                            @endforeach
                        </select>
                        <select class="form-select form--control select2" name="paginate"
                            data-minimum-results-for-search="-1">
                            <option selected value="">@lang('Filter Per Page')</option>
                            <option value="20" @selected(request()->paginate == 20)>20</option>
                            <option value="40" @selected(request()->paginate == 40)>40</option>
                            <option value="60" @selected(request()->paginate == 60)>60</option>
                            <option value="80" @selected(request()->paginate == 80)>80</option>
                            <option value="100" @selected(request()->paginate == 100)>100</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="dashboard-table">
                <table class="table table--responsive--md">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="check-all">
                            </th>
                            <th class="text-start">@lang('Name')</th>
                            <th>@lang('Mobile')</th>
                            <th>@lang('Tags')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contacts as $contact)
                            <tr>
                                <td>
                                    <input type="checkbox" class="contact-checkbox" name="contact_ids[]"
                                        value="{{ $contact->id }}" form="confirmationForm">
                                </td>
                                <td>
                                    <div
                                        class="d-flex align-items-center gap-2 flex-wrap justify-content-center justify-content-md-start">
                                        @include('Template::user.contact.thumb')
                                        {{ __(@$contact->fullName) }}
                                    </div>
                                </td>
                                <td>+{{ @$contact->mobileNumber }}</td>
                                <td>
                                    <ul class="tag-list">
                                        @forelse ($contact->tags->take(3) as $tag)
                                            <li>
                                                <a href="{{ appendQuery('tag_id', $tag->id) }}"
                                                    class="tag-list__link">{{ __(@$tag->name) }}</a>
                                            </li>
                                        @empty
                                            <li>
                                                <span class="text-muted">@lang('N/A')</span>
                                            </li>
                                        @endforelse
                                        @if ($contact->tags->count() > 3)
                                            <li>
                                                <button type="button" data-tags="{{ $contact->tags }}"
                                                    class="more_tags_btn text--base">@lang('See More...')
                                                </button>
                                            </li>
                                        @endif
                                    </ul>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <x-permission_check permission="edit contact">
                                            <a href="{{ route('user.contact.edit', $contact->id) }}"
                                                class="action-btn text--base" data-bs-toggle="tooltip"
                                                data-bs-title="@lang('Edit')">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                        </x-permission_check>
                                        <x-permission_check permission="send message">
                                            <a href="{{ route('user.inbox.list') }}?contact_id={{ $contact->id }}"
                                                class="action-btn text--info" data-bs-toggle="tooltip"
                                                data-bs-title="@lang('Send Message')"><i class="fa-solid fa-paper-plane"></i>
                                            </a>
                                        </x-permission_check>
                                        <x-permission_check permission="delete contact">
                                            <button type="button" class="action-btn confirmationBtn text--danger"
                                                data-bs-toggle="tooltip" data-question="@lang('Are you sure to remove this contact?')"
                                                data-action="{{ route('user.contact.delete', @$contact->id) }}"
                                                data-bs-title="@lang('Delete')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </x-permission_check>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @include('Template::partials.empty_message')
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ paginateLinks(@$contacts) }}
        </div>
    </div>

    <div class="modal custom--modal fade importModal progressModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div id="import-progress" class="progress  h-1 w-full d-none">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated h-full w-0"></div>
                </div>
                <div class="modal-header">
                    <div class="modal-header__left">
                        <h5 class="title">@lang('Import Contacts')</h5>
                        <p class="text">@lang('Select your file to import contacts')</p>
                    </div>
                    <div class="modal-header__right">
                        <div class="btn--group">
                            <button class="btn--white btn" data-bs-dismiss="modal"
                                aria-label="Close">@lang('Cancel Import')</button>
                            <button class="btn--base btn btn-shadow" form="import-form">@lang('Import Contacts')</button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="csv-form-wrapper">
                        <div class="csv-form-wrapper__left">
                            <div class="thumb-form-wrapper">
                                <form action="{{ route('user.contact.import') }}" method="POST" id="import-form"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label class="label-two">
                                            @lang('Contact List')
                                            <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="@lang('By specifying a contact list, all imported contacts will be added to the selected list. If left global, the contacts will not be added to any contact list.')">
                                                <i class="las la-question-circle"></i>
                                            </span>
                                        </label>
                                        <select class="form--control select2" name="contact_list_id"
                                            data-minimum-results-for-search="-1">
                                            <option value="0"> @lang('Global') </option>
                                            @foreach ($contactLists as $list)
                                                <option value="{{ $list->id }}"> {{ __(@$list->name) }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="thumb-form">
                                        <input type="file" class="form--control drag-drop" name="file"
                                            accept=".csv,xlsx">
                                        <label for="file">
                                            <span class="thumb-form__icon">
                                                <i class="las la-cloud-upload-alt"></i>
                                            </span>
                                            <p class="thumb-form__text file-name text-center">
                                                <span class="">
                                                    <span
                                                        class="cursor-pointer text-decoration-underline text--info">@lang('Click to Upload')</span>
                                                    @lang('or drag and drop here')
                                                </span>
                                                <span class="text-muted fs-14">
                                                    @lang('Supported Files:')
                                                    <b>@lang('.csv'), @lang('.xlsx')</b>
                                                    @lang('& Maximum file size') <b>2</b>@lang('MB')
                                                </span>
                                            </p>
                                        </label>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="csv-form-wrapper__right">
                            <div class="instruction-wrapper">
                                <p class="title">@lang('Steps to Import Your Contacts')</p>
                                <ol class="instruction-list">
                                    <li class="instruction-list__item">@lang('Download the sample template to ensure the correct format.')</li>
                                    <li class="instruction-list__item">@lang('Fill in the required customer details: firstname, lastname, mobile_code, and mobile number.')</li>
                                    <li class="instruction-list__item">@lang('Save the completed file in either .csv or .xlsx format.')</li>
                                    <li class="instruction-list__item">@lang('Upload the file by selecting it manually or dragging and dropping it into the upload area.')</li>
                                </ol>
                                <div class="download-btn">
                                    <a href="{{ route('user.contact.csv.download') }}" class="btn--white btn btn--sm">
                                        <i class="la la-download"></i> @lang('Download Sample File')
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade custom--modal tags-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Contact Tags')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="tag-list contact-tags">
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal isFrontend="true" />
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

            const $modal = $('.importModal');
            const $form = $('#import-form');
            const $drop = $('.thumb-form');
            const $input = $drop.find('input[type="file"]');
            const $progress = $('#import-progress');
            const $bar = $progress.find('.progress-bar');

            $('.importBtn').on('click', () => $modal.modal('show'));

            $('.filter-form select').on('change', function() {
                $(this).closest('form').submit();
            });

            $form.on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                $progress.removeClass('d-none');
                $bar.css('width', '0%');

                $.ajax({
                    url: $form.attr('action'),
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
                            notify('success', res.message);
                            setTimeout(() => location.reload(), 1000);
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
                    }
                });
            });


            $(document).on('dragover dragenter drop', e => e.preventDefault());

            $drop.on('dragover', () => $drop.addClass('drag-over'))
                .on('dragleave drop', () => $drop.removeClass('drag-over'));

            $drop.on('drop', e => {
                const files = e.originalEvent.dataTransfer.files;
                if (files.length) {
                    $input[0].files = files;
                    $('.file-name').text($input[0].files[0].name);
                }
            });

            $input.on('change', () => {
                $('.file-name').text($input[0].files[0].name);
            })

            $('.more_tags_btn').on('click', function() {
                const contactTags = $(this).data('tags');
                $('.contact-tags').html('');
                contactTags.forEach(tag => {
                    $('.contact-tags').append(`
                    <li>
                        <a href = "?tag_id=${tag.id}" class = "tag-list__link">${tag.name}</a>
                    </li>`);
                });
                $('.tags-modal').modal('show');
            });

            $('body').on('click', '.check-all', function(e) {
                const $this = $(this);
                const $checkboxes = $this.closest('table').find('input[type="checkbox"]');
                $checkboxes.prop('checked', $this.prop('checked'));
                $checkboxes.closest('tr').find('td').toggleClass('row-selected');
                toggleBulkDeleteBtn();
            });

            $('body').on('click', '.contact-checkbox', function(e) {
                const $this = $(this);
                $this.closest('tr').find('td').toggleClass('row-selected');
                toggleBulkDeleteBtn();
            });

            function toggleBulkDeleteBtn() {
                const selectedContacts = $('input.contact-checkbox:checked').length;
                const $bulkDeleteBtn = $('.bulk-delete');

                if (selectedContacts > 0) {
                    $bulkDeleteBtn.removeClass('disabled').removeAttr('disabled');
                } else {
                    $bulkDeleteBtn.addClass('disabled').attr('disabled', 'disabled');
                }
            }

        })(jQuery);
    </script>
@endpush


@push('style')
    <style>
        .row-selected {
            background-color: hsl(var(--success)/0.09) !important;
        }
    </style>
@endpush
