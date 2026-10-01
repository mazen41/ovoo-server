@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Organize and manage your contact tags for easily manage your contacts.')</p>
            </div>
            <x-permission_check permission="add contact tag">
                <div class="container-top__right">
                    <div class="btn--group">
                        <button class="btn btn--base add-btn btn-shadow">
                            <i class="las la-plus"></i>
                            @lang('Add New')
                        </button>
                    </div>
                </div>
            </x-permission_check>
        </div>
        <div class="dashboard-container__body">
            <div class="body-top">
                <div class="body-top__left">
                    <form class="search-form">
                        <input type="search" class="form--control" placeholder="@lang('Search tag')..." name="search"
                            value="{{ request()->search }}">
                        <span class="search-form__icon"> <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                    </form>
                </div>
            </div>
            <div class="dashboard-table">
                <table class="table table--responsive--md">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Total Contacts')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contactTags as $contactTag)
                            <tr>
                                <td>{{ __(@$contactTag->name) }}</td>
                                <td>
                                    <a href="{{ route('user.contact.list') }}?tag_id={{ @$contactTag->id }}">
                                        {{ $contactTag->contacts_count }}
                                    </a>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <x-permission_check permission="edit contact tag">
                                            <button type="button" class="action-btn edit-btn"
                                                data-contact-tag='@json($contactTag)' data-bs-toggle="tooltip"
                                                data-bs-placement="top" data-bs-title="@lang('Edit')">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                        </x-permission_check>
                                        <x-permission_check permission="delete contact tag">
                                            <button type="button" class="action-btn delete-btn confirmationBtn"
                                                data-question="@lang('Are you sure to remove this contact tag?')"
                                                data-action="{{ route('user.contacttag.delete', $contactTag->id) }}"data-bs-toggle="tooltip"
                                                data-bs-placement="top" data-bs-title="@lang('Delete')">
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
            {{ paginateLinks($contactTags) }}
        </div>
    </div>
    <x-confirmation-modal isFrontend="true" />

    <div class="modal fade custom--modal add-modal" id="add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Contact Tag')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="label-two">@lang('Name')</label>
                            <input name="name" class="form--control form-two" placeholder="@lang('Enter tag name')"
                                required />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn--base w-100"><i class="lab la-telegram"></i>
                                @lang('Submit')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        "use strict";
        (function($) {

            const $modal = $('.add-modal');

            $('.add-btn').on('click', function() {
                $modal.find('.modal-title').text('Add Contact Tag');
                $modal.find('form').attr('action', "{{ route('user.contacttag.save') }}");
                $modal.find('form').trigger('reset');
                $modal.modal('show');
            });

            $('.edit-btn').on('click', function() {
                let contactTag = $(this).data('contact-tag');
                let route = "{{ route('user.contacttag.update', ':id') }}";
                $modal.find('.modal-title').text('Edit Contact Tag');
                $modal.find('form').attr('action', route.replace(':id', contactTag.id));
                $modal.find('input[name=name]').val(contactTag.name);
                $modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
