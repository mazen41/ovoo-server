@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Track performance and manage your transactions effortlessly.')</p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <a href="{{ route('user.deposit.index') }}" class="btn btn--base btn-shadow"> <i class="las la-plus"></i>
                        @lang('New Deposit')</a>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <div class="body-top">
                <div class="body-top__left">
                    <form class="search-form">
                        <input type="search" class="form--control" name="search" value="{{ request()->search }}"
                            placeholder="@lang('Search transactions...')" autocomplete="off">
                        <span class="search-form__icon"> <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                    </form>
                </div>
                <div class="body-top__right">
                    <span class="text"> @lang('Filter by') : </span>
                    <form class="select-group filter-form">
                        <select class="form-select form--control select2" name="status">
                            <option value="">@lang('Status')</option>
                            <option value="{{ Status::PAYMENT_PENDING }}" @selected(request()->status == Status::PAYMENT_PENDING)>@lang('Pending')
                            </option>
                            <option value="{{ Status::PAYMENT_SUCCESS }}" @selected(request()->status == Status::PAYMENT_SUCCESS)>@lang('Success')
                            </option>
                            <option value="{{ Status::PAYMENT_REJECT }}" @selected(request()->status == Status::PAYMENT_REJECT)>@lang('Rejected')
                            </option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="dashboard-table">
                <table class="table table--responsive--lg">
                    <thead>
                        <tr>
                            <th>@lang('Gateway | Transaction')</th>
                            <th>@lang('Initiated')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Conversion')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Details')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deposits as $deposit)
                            <tr>
                                <td>
                                    <div>
                                        <span class="fw-bold">
                                            <span class="text-primary">
                                                @if ($deposit->method_code < 5000)
                                                    {{ __(@$deposit->gateway->name) }}
                                                @else
                                                    @lang('Google Pay')
                                                @endif
                                            </span>
                                        </span>
                                        <br>
                                        <small> {{ $deposit->trx }} </small>
                                    </div>
                                </td>

                                <td class="text-lg-center">
                                    {{ showDateTime($deposit->created_at) }}<br>{{ diffForHumans($deposit->created_at) }}
                                </td>
                                <td class="text-lg-center">
                                    <div>{{ showAmount($deposit->amount) }} + <span class="text--danger"
                                            data-bs-toggle="tooltip"
                                            title="@lang('Processing Charge')">{{ showAmount($deposit->charge) }}
                                        </span>
                                        <br>
                                        <strong data-bs-toggle="tooltip" title="@lang('Amount with charge')">
                                            {{ showAmount($deposit->amount + $deposit->charge) }}
                                        </strong>
                                    </div>
                                </td>
                                <td class="text-lg-center">
                                    <div>
                                        {{ showAmount(1) }} =
                                        {{ showAmount($deposit->rate, currencyFormat: false) }}
                                        {{ __($deposit->method_currency) }}
                                        <br>
                                        <strong>{{ showAmount($deposit->final_amount, currencyFormat: false) }}
                                            {{ __($deposit->method_currency) }}</strong>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @php echo $deposit->statusBadge @endphp
                                </td>
                                @php
                                    $details = [];
                                    if ($deposit->method_code >= 1000 && $deposit->method_code <= 5000) {
                                        foreach (@$deposit->detail ?? [] as $key => $info) {
                                            $details[] = $info;
                                            if ($info->type == 'file') {
                                                $details[$key]->value = route(
                                                    'user.download.attachment',
                                                    encrypt(getFilePath('verify') . '/' . $info->value),
                                                );
                                            }
                                        }
                                    }
                                @endphp
                                <td>
                                    @if ($deposit->method_code >= 1000 && $deposit->method_code <= 5000)
                                        <a href="javascript:void(0)" class="btn btn--base  detailBtn"
                                            data-info="{{ json_encode($details) }}"
                                            @if ($deposit->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $deposit->admin_feedback }}" @endif>
                                            <i class="fas fa-desktop"></i>
                                        </a>
                                    @else
                                        <button type="button" class="btn btn--base" data-bs-toggle="tooltip"
                                            title="@lang('Automatically processed')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            @include('Template::partials.empty_message')
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ paginateLinks($deposits) }}
        </div>
    </div>
    <!-- APPROVE MODAL -->
    <div id="detailModal" class="modal fade custom--modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData  list-group-flush">
                    </ul>
                    <div class="feedback"></div>
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
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');

                var userData = $(this).data('info');
                var html = '';
                if (userData) {
                    userData.forEach(element => {
                        if (element.type != 'file') {
                            html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${element.name}</span>
                                <span">${element.value}</span>
                            </li>`;
                        } else {
                            html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${element.name}</span>
                                <span"><a href="${element.value}"><i class="fa-regular fa-file"></i> @lang('Attachment')</a></span>
                            </li>`;
                        }
                    });
                }

                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);


                modal.modal('show');
            });

            $('.filter-form').find('select').on('change', function() {
                $('.filter-form').submit();
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

        })(jQuery);
    </script>
@endpush
