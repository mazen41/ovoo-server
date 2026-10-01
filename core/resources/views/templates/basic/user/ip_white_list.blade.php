@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">
                    @lang('Please add your IP address to the IP whitelist in the dashboard. API access is restricted to whitelisted IPs only; requests from non-whitelisted IP addresses will be denied.')
                </p>
            </div>
            <x-permission_check permission="ip configuration action">
                <div class="container-top__right">
                    <div class="btn--group">
                        <button class="btn btn--base btn-shadow add-btn">
                            <i class="las la-plus"></i> @lang('Add New')
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
            </div>
            <div class="dashboard-table">
                <table class="table table--responsive--md">
                    <thead>
                        <tr>
                            <th>@lang('S.N.')</th>
                            <th>@lang('IP Address')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ips as $ip)
                            <tr>
                                <td>{{ $ips->firstItem() + $loop->index }}</td>
                                <td>{{ $ip->ip }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <x-permission_check permission="ip configuration action">
                                            <button type="button" class="action-btn edit-btn" data-ip='{{ $ip }}'
                                                data-action="{{ route('user.ip.white.list.store', $ip->id) }}"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                data-bs-title="@lang('Edit')">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                        </x-permission_check>
                                        <x-permission_check permission="ip configuration action">
                                            <button type="button" class="action-btn delete-btn confirmationBtn"
                                                data-question="@lang('Are you sure to remove this ip address?')"
                                                data-action="{{ route('user.ip.white.list.delete', $ip->id) }}"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
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
            {{ paginateLinks($ips) }}
        </div>
    </div>

    <div class="modal fade custom--modal add-modal" id="add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Ip Address')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="label-two">@lang('IP Address')</label>
                            <input name="ip" class="form--control form-two" placeholder="@lang('Enter ip address')" required
                                value="{{ old('ip') }}" />
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

    <x-confirmation-modal isFrontend="true" />
@endsection


@push('topbar_tabs')
    @include('Template::partials.profile_tab')
@endpush

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

            const $modal = $('.add-modal');

            $('.add-btn').on('click', () => {
                const $form = $modal.find('form');

                $modal.find('.modal-title').text(`@lang('Add New IP Address')`);
                $form.attr('action', "{{ route('user.ip.white.list.store') }}");
                $form[0].reset();

                const $version = $form.find('[name=version]');
                $version.val($version.find('option:first').val()).trigger('change');

                $modal.modal('show');
            });

            $('.edit-btn').on('click', e => {
                const $btn = $(e.currentTarget);
                const ip = $btn.data('ip');
                const action = $btn.data('action');

                $modal.find('.modal-title').text(`@lang('Edit IP Address')`);
                $modal.find('[name=version]').val(ip.version).trigger('change');
                $modal.find('[name=ip]').val(ip.ip);
                $modal.find('form').attr('action', action);
                $modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush