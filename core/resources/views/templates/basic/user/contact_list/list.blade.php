@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Organize and manage your contact list for easily manage your contacts.')</p>
            </div>
            <x-permission_check permission="add contact list">
                <div class="container-top__right">
                    <div class="btn--group">
                        <button class="btn btn--base btn-shadow add-btn"><i class="las la-plus"></i>
                            @lang('Add New')</button>
                    </div>
                </div>
            </x-permission_check>
        </div>
        <div class="dashboard-container__body">
            <div class="body-top">
                <div class="body-top__left">
                    <form class="search-form">
                        <input type="search" class="form--control" placeholder="@lang('Search here')..." name="search"
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
                            <th>@lang('Contacts')</th>
                            <th>@lang('Created At')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contactLists as $contactList)
                            <tr>
                                <td>{{ __(@$contactList->name) }}</td>
                                <td>{{ @$contactList->contact()->count() }}
                                    {{ str()->plural('contact', @$contactList->contact()->count()) }}</td>
                                <td>{{ showDateTime(@$contactList->created_at) }} </td>
                                <td>
                                    <div class="action-buttons">
                                        <x-permission_check permission="edit contact list">
                                            <button type="button" class="action-btn edit-btn text--base"
                                                data-contact-list='@json($contactList)' data-bs-toggle="tooltip"
                                                data-bs-title="@lang('Edit')"> <i class="fas fa-pen"></i>
                                            </button>
                                        </x-permission_check>
                                        <x-permission_check permission="view list contact">
                                            <a class="text--info"
                                                href="{{ route('user.contactlist.view', $contactList->id) }}"
                                                data-bs-toggle="tooltip" data-bs-title="@lang('View')">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </x-permission_check>
                                        <x-permission_check permission="delete contact list">
                                            <button type="button" class="action-btn delete-btn confirmationBtn"
                                                data-question="@lang('Are you sure to remove this contact from the contact list?')"
                                                data-action="{{ route('user.contactlist.delete', $contactList->id) }}"
                                                data-bs-toggle="tooltip" data-bs-title="@lang('Delete')">
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
            {{ paginateLinks($contactLists) }}
        </div>
    </div>

    <x-confirmation-modal isFrontend="true" />

    <div class="modal fade custom--modal add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('New Contact List')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="label-two">@lang('Name')</label>
                            <input type="text" class="form--control form-two" name="name"
                                placeholder="@lang('Enter list name')" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn--base w-100"><i class="lab la-telegram"></i>
                                @lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            const $modal = $('.add-modal');

            $('.add-btn').on('click', function() {
                $modal.find('form').trigger('reset');
                $modal.find('form').attr('action', "{{ route('user.contactlist.save') }}");
                $modal.find('.modal-title').text("@lang('New Contact List')");
                $modal.modal('show');
            });

            $('.edit-btn').on('click', function() {
                let contactList = $(this).data('contact-list');
                let route = "{{ route('user.contactlist.update', ':id') }}";
                $modal.find('form').attr('action', route.replace(':id', contactList.id));
                $modal.find('input[name=name]').val(contactList.name);
                $modal.find('.modal-title').text("@lang('Edit Contact List')");
                $modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
