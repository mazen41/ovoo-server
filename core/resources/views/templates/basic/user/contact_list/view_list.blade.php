@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Organize and manage your contact list for easily manage your contacts.')</p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <a href="{{ route('user.contactlist.list') }}" class="btn btn--dark"><i class="las la-undo"></i>
                        @lang('Back')</a>
                    <x-permission_check permission="add contact to list">
                        <button class="btn btn--base add-btn"><i class="las la-plus"></i> @lang('Add Contact')</button>
                    </x-permission_check>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <div class="body-top">
                <div class="body-top__left">
                    <form class="search-form">
                        <input type="search" class="form--control" placeholder="@lang('Search here') ..." name="search"
                            value="{{ request()->search }}">
                        <span class="search-form__icon">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                    </form>
                </div>
            </div>
            <div class="dashboard-table">
                <table class="table table--responsive--md">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Mobile Number')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contacts as $listContact)
                            <tr>
                                <td>
                                    <div
                                        class="d-flex align-items-center gap-2 flex-wrap justify-content-end justify-content-md-start">
                                        <div class="table-thumb d-none d-lg-block">
                                            <img src="{{ @$listContact->contact?->image_src }}" alt="image">
                                        </div>
                                        {{ __(@$listContact->contact?->fullName) }}
                                    </div>
                                </td>
                                <td>+{{ @$listContact->contact?->mobileNumber }}</td>
                                <td>
                                    <x-permission_check permission="remove contact from list">
                                        <button type="button" class="action-btn delete-btn confirmationBtn"
                                            data-question="@lang('Are you sure to remove this contact from list?')"
                                            data-action="{{ route('user.contactlist.contact.remove', $listContact->id) }}"
                                            data-bs-toggle="tooltip" data-bs-title="@lang('Delete')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </x-permission_check>
                                </td>
                            </tr>
                        @empty
                            @include('Template::partials.empty_message')
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ paginateLinks($contacts) }}
        </div>
    </div>
    <x-confirmation-modal isFrontend="true" />

    <div class="modal fade custom--modal add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Contact to List')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('user.contactlist.contact.add', $contactList->id) }}">
                        @csrf
                        <div class="form-group mb-3 selection-contact">
                            <label class="label-two">@lang('Select Contact')</label>
                            <select name="contacts[]" class="form--control contacts select2" multiple required></select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn--base w-100"><i class="lab la-telegram"></i>
                                @lang('Submit')</button>
                        </div>
                    </form>
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
            "use strict";
            (function($) {

                const $modal = $('.add-modal');

                $('.add-btn').on('click', function() {
                    $modal.find('form').trigger('reset');
                    $modal.modal('show');
                });

                $modal.on('shown.bs.modal', function() {
                    $('.contacts').select2({
                        ajax: {
                            url: "{{ route('user.contact.search') }}",
                            type: "get",
                            dataType: 'json',
                            delay: 1000,
                            data: function(params) {
                                return {
                                    search: params.term,
                                    page: params.page,
                                    contactListId: "{{ $contactList->id }}"
                                };
                            },
                            processResults: function(response, params) {
                                params.page = params.page || 1;
                                return {
                                    results: $.map(response.data.contacts.data, function(item) {
                                        return {
                                            text: '+' + item.mobile_code + item.mobile,
                                            id: item.id
                                        };
                                    }),
                                    pagination: {
                                        more: response.more
                                    }
                                };
                            },
                            cache: false
                        },
                        allowClear: true,
                        // minimumInputLength: 1,
                        width: "100%",
                        dropdownParent: $('.selection-contact')
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


            .add-modal .select2+.select2-container.select2-container--open .select2-selection__rendered,
            .add-modal .select2+.select2-container.select2-container--focus .select2-selection.select2-selection--multiple,
            .add-modal .select2+.select2-container.select2-container--open .select2-selection.select2-selection--multiple {
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

            .add-modal .select2+.select2-container .select2-selection--multiple .select2-selection__rendered {
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

            .add-modal .select2-search__field:focus {
                border-color: transparent !important;
            }

            .select2-selection--multiple .select2-search__field {
                background-color: transparent !important;
            }

            .select2+.select2-container:has(.select2-selection.select2-selection--multiple) {
                height: auto;
            }
        </style>
    @endpush
