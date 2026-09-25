@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Create and manage your Automation Flow Builder to create and manage structured conversation flows for WhatsApp Business accounts.')</p>
            </div>
            <x-permission_check permission="add flow builder">
                <div class="container-top__right">
                    <div class="btn--group">
                        @if ($userWhatsAppAccounts->count() > 0)
                            <button type="button" class="btn btn--base btn-shadow account-select-btn">
                                <i class="las la-plus"></i>
                                @lang('Add New')
                            </button>
                        @else
                            <a href="{{ route('user.whatsapp.account.add') }}" class="btn btn--base btn-shadow add-btn">
                                <i class="las la-plus"></i>
                                @lang('Add New')
                            </a>
                        @endif
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
                            <th>@lang('Trigger Type')</th>
                            <th>@lang('Created At')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($flows as $flow)
                            <tr>
                                <td>{{ __(@$flow->name) }}</td>
                                <td>{{ @$flow->getTriggerType }}</td>
                                <td>{{ showDateTime(@$flow->created_at) }} </td>
                                <td>
                                    @if (auth()->user()->hasAgentPermission('edit flow builder'))
                                        <div class="form--switch two">
                                            <input class="form-check-input status-switch" data-id="{{ $flow->id }}"
                                                type="checkbox" role="switch" @checked(@$flow->status)>
                                        </div>
                                    @else
                                        @php
                                            echo $flow->statusBadge;
                                        @endphp
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <x-permission_check permission="edit flow builder">
                                            <a class="text--info cursor-pointer"
                                                href="{{ route('user.flow.builder.edit', $flow->id) }}?account={{ $flow->whatsapp_account_id }}"
                                                data-bs-toggle="tooltip" data-bs-title="@lang('Edit')">
                                                <i class="las la-edit fs-16"></i>
                                            </a>
                                        </x-permission_check>
                                        <x-permission_check permission="delete flow builder">
                                            <button type="button" class="text--danger confirmationBtn"
                                                data-bs-toggle="tooltip" data-bs-title="@lang('Delete')"
                                                data-action="{{ route('user.flow.builder.delete', $flow->id) }}"
                                                data-question="@lang('Are you sure to delete this Flow?')">
                                                <i class="las la-trash fs-16"></i>
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
            {{ paginateLinks($flows) }}
        </div>
    </div>

    <x-confirmation-modal isFrontend="true" />

    <div class="modal fade custom--modal account-select-modal" id="accountSelectModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Select Your WhatsApp Account')</h5>
                    <button class="btn--white btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <div class="alert alert--warning flex-column  border border-1 border--warning">
                            <div class="alert__content">
                                <p class="mb-2">
                                    <i class="las la-info-circle"></i>
                                    <strong>@lang('You have multiple WhatsApp accounts. Please select the WhatsApp account for which you want to create a new automation flow.')</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="whatsappAccount">@lang('WhatsApp Account')</label>
                        <select class="form--control select2" id="whatsappAccount" required>
                            <option value="">@lang('Select an account')</option>
                            @foreach ($userWhatsAppAccounts as $account)
                                <option value="{{ route('user.flow.builder.create') }}?account={{ $account->id }}">
                                    {{ __(@$account->business_name) }} - {{ __(@$account->phone_number) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <button type="button" class="btn btn--base btn-shadow w-100 proceedBtn">
                            <i class="la la-telegram-plane"></i> @lang('Proceed')
                        </button>
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
        "use strict";
        (function($) {
            $('.status-switch').on('change', function() {
                let route = "{{ route('user.flow.builder.status', ':id') }}";
                let flowId = $(this).data('id');
                $.ajax({
                    url: route.replace(':id', flowId),
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        notify(response.status, response.message);
                    }
                });
            });
            $('.account-select-btn').on('click', function() {
                $('#accountSelectModal').modal('show');
            });
            $('.proceedBtn').on('click', function() {
                let selectedRoute = $('#whatsappAccount').val();
                if (selectedRoute) {
                    window.location.href = selectedRoute;
                } else {
                    notify('error', "{{ __('Please select a WhatsApp account.') }}");
                }
            });
        })(jQuery);
    </script>
@endpush
