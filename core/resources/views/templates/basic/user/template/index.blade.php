@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Easily create & manage whatsapp message templates for easy communication.')</p>
            </div>
            <x-permission_check permission="add template">
                <div class="container-top__right">
                    <div class="btn--group">
                        <a href="{{ route('user.template.create.carousel') }}" class="btn btn--base btn-shadow">
                            <i class="las la-plus"></i>
                            @lang('Carousel Template')
                        </a>
                        <a href="{{ route('user.template.create') }}" class="btn btn--base btn-shadow">
                            <i class="las la-plus"></i>
                            @lang('Add New')
                        </a>
                        <button type="button" class="btn btn--base btn-shadow template-fetch-btn">
                            <i class="fa-regular fa-paper-plane deg-190"></i>
                            @lang('Fetch Templates')
                        </button>
                    </div>
                </div>
            </x-permission_check>
        </div>
        <div class="dashboard-container__body">
            <div class="body-top">
                <div class="body-top__left">
                    <form class="search-form">
                        <input type="search" class="form--control" name="search" placeholder="@lang('Search by ID or name...')"
                            autocomplete="off" value="{{ request()->search }}">
                        <span class="search-form__icon"> <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                    </form>
                </div>
                <div class="body-top__right">
                    <form class="select-group filter-form">
                        <select class="form-select form--control select2" name="status">
                            <option selected value="">@lang('Filter Status')</option>
                            <option value="{{ Status::TEMPLATE_PENDING }}" @selected(request()->status == (string) Status::TEMPLATE_PENDING)>
                                @lang('Pending')
                            </option>
                            <option value="{{ Status::TEMPLATE_APPROVED }}" @selected(request()->status == Status::TEMPLATE_APPROVED)>
                                @lang('Approved')
                            </option>
                            <option value="{{ Status::TEMPLATE_REJECTED }}" @selected(request()->status == Status::TEMPLATE_REJECTED)>
                                @lang('Rejected')
                            </option>
                            <option value="{{ Status::TEMPLATE_DISABLED }}" @selected(request()->status == Status::TEMPLATE_DISABLED)>
                                @lang('Disabled')
                            </option>
                        </select>
                        <select class="form-select form--control select2" name="category_id">
                            <option selected value="">@lang('Filter Category')</option>
                            @foreach ($templateCategories as $templateCategory)
                                <option value="{{ @$templateCategory->id }}" @selected(request()->category_id == $templateCategory->id)>
                                    {{ __(@$templateCategory->label) }}
                                </option>
                            @endforeach
                        </select>
                        <select class="form--control select2 form-two" required name="whatsapp_account_id">
                            @foreach ($whatsappAccounts as $whatsappAccount)
                                <option value="{{ $whatsappAccount->id }}" @selected($whatsappAccount->id == old('whatsapp_account_id', request()->whatsapp_account_id))>
                                    {{ __($whatsappAccount->business_name) }} ({{ $whatsappAccount->phone_number }})
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
            <div class="dashboard-table">
                <table class="table table--responsive--xl">
                    <thead>
                        <tr>
                            <th>@lang('Template ID')</th>
                            <th>@lang('Template')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Created Date')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($templates as $template)
                            <tr>
                                <td>{{ @$template->whatsapp_template_id }}</td>
                                <td>
                                    <div class="dashboard-table__info">
                                        <span class="title">{{ $template->name }}</span>
                                        <br>
                                        <span class="text-muted fs-13">{{ strLimit($template->body, 40) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        @php echo $template->verificationStatus() @endphp
                                        @if ($template->status == Status::TEMPLATE_PENDING)
                                            <a href="{{ route('user.template.verification.check', $template->id) }}">
                                                <i class="las la-redo-alt"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ showDateTime(@$template->created_at, 'd M Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="action-btn text--info copyTemplate"
                                            data-bs-toggle="tooltip" data-bs-title="Copy Template"
                                            data-name="{{ $template->name }}">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <x-permission_check permission="delete template">
                                            <button type="button" class="action-btn text--danger confirmationBtn"
                                                data-bs-toggle="tooltip" data-bs-title="Delete Template"
                                                data-action="{{ route('user.template.delete', $template->id) }}"
                                                data-question="@lang('Are you sure to delete this template?')">
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
            {{ paginateLinks(@$templates) }}
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
                    <form action="{{ route('user.template.fetch') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <div class="alert alert--warning flex-column  border border-1 border--warning">
                                <div class="alert__content">
                                    <p class="mb-2">
                                        <i class="las la-info-circle"></i>
                                        <strong>@lang('You have multiple WhatsApp accounts. Please select the specific account from which you would like to fetch the templates from the meta.')</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="whatsappAccount">@lang('WhatsApp Account')</label>
                            <select class="form--control select2" id="whatsappAccount" required name="whatsapp_account">
                                <option value="">@lang('Select an account')</option>
                                @foreach ($whatsappAccounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ __(@$account->business_name) }} - {{ __(@$account->phone_number) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn--base btn-shadow w-100 proceedBtn">
                                <i class="la la-telegram-plane"></i> @lang('Proceed')
                            </button>
                        </div>
                    </form>
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

@push('style')
    <style>
        .table tbody tr td:first-child {
            font-weight: unset !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.filter-form').find('select').on('change', function() {
                $('.filter-form').submit();
            });
            $('.copyTemplate').on('click', function() {
                let templateName = $(this).data('name');
                navigator.clipboard.writeText(templateName)
                    .then(() => {
                        notify('success', 'Template name copied to clipboard');
                    })
                    .catch(err => {
                        notify('error', 'Failed to copy template to clipboard');
                    });
            });

            $(".template-fetch-btn").on('click', function() {
                $('#accountSelectModal').modal('show');
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .deg-190 {
            rotate: 190deg;
        }
    </style>
@endpush
