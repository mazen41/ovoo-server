@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">
            <x-admin.ui.card class="table-has-filter">
                <x-admin.ui.card.body :paddingZero="true">
                    <x-admin.ui.table.layout :renderTableFilter="false">
                        <x-admin.ui.table>
                            <x-admin.ui.table.header>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Code')</th>
                                    <th>@lang('Discount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Active Duration')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse($coupons as $coupon)
                                    <tr>
                                        <td>{{ __(@$coupon->name) }}</td>
                                        <td>{{ @$coupon->code }}</td>
                                        <td>
                                            <div>
                                                <span>{{ showAmount(@$coupon->amount, currencyFormat: false) }}</span>
                                                <span>{{ $coupon->type == Status::COUPON_TYPE_FIXED ? gs('cur_text') : '%' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($coupon->status == Status::COUPON_EXPIRED)
                                                <span class="badge badge--danger">@lang('Expired')</span>
                                            @else
                                                <x-admin.other.status_switch :status="$coupon->status" :action="route('admin.coupon.status', $coupon->id)"
                                                    title="coupon" />
                                            @endif
                                        </td>
                                        <td>{{ strPlural(@$coupon->duration_days, 'day') }}</td>
                                        <td>
                                            <div data-coupon='@json($coupon)'
                                                data-duration="{{ @$coupon->duration }}">
                                                <button class="btn btn-outline--primary table-action-btn editBtn">
                                                    <i class="las la-edit"></i> @lang('Edit')
                                                </button>
                                                <button type="button"
                                                    class="btn  btn-outline--info ms-1 table-action-btn detailsBtn">
                                                    <i class="las la-info-circle"></i> @lang('Details')
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <x-admin.ui.table.empty_message />
                                @endforelse
                            </x-admin.ui.table.body>
                        </x-admin.ui.table>
                        @if ($coupons->hasPages())
                            <x-admin.ui.table.footer>
                                {{ paginateLinks($coupons) }}
                            </x-admin.ui.table.footer>
                        @endif
                    </x-admin.ui.table.layout>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>

    <x-admin.ui.modal id="couponModal">
        <x-admin.ui.modal.header>
            <div>
                <h4 class="modal-title"></h4>
            </div>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form method="POST" class="row">
                @csrf
                <div class="form-group col-md-6">
                    <label class="form-label">@lang('Name')</label>
                    <input class="form-control" type="text" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label class="form-label">
                        @lang('Code')
                        <span data-bs-toggle="tooltip" title="@lang('The coupon code may only contain uppercase letters, numbers, underscores, and dashes (no spaces).')"><i class="las la-info-circle"></i></span>
                    </label>
                    <input class="form-control" type="text" name="code" value="{{ old('code') }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label class="form-label">@lang('Discount Type')</label>
                    <select name="type" class="form-control select2" required data-minimum-results-for-search="-1">
                        <option value="{{ Status::COUPON_TYPE_PERCENTAGE }}">@lang('Percentage')</option>
                        <option value="{{ Status::COUPON_TYPE_FIXED }}">@lang('Fixed')</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label class="form-label">@lang('Amount')</label>
                    <div class="input-group input--group">
                        <input class="form-control" type="number" name="amount" value="{{ old('amount') }}" required>
                        <span class="input-group-text type-text">%</span>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="form-label">@lang('Minimum Purchase Amount')</label>
                    <div class="input-group input--group">
                        <input class="form-control" type="number" name="min_purchase_amount"
                            value="{{ old('min_purchase_amount') }}" required>
                        <span class="input-group-text"> {{ __(gs('cur_text')) }} </span>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="form-label">
                        @lang('Total Use Limit')
                        <span data-bs-toggle="tooltip" title="@lang('Enter value -1 if you don\'t for unlimited use.')"><i class="las la-info-circle"></i></span>
                    </label>
                    <div class="input-group input--group">
                        <input class="form-control" type="number" name="use_limit" value="{{ old('use_limit') }}"
                            required>
                        <span class="input-group-text">@lang('Times')</span>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="form-label">
                        @lang('Per User Use Limit')
                        <span data-bs-toggle="tooltip" title="@lang('Enter value -1 if you don\'t for unlimited use.')"><i class="las la-info-circle"></i></span>
                    </label>
                    <div class="input-group input--group">
                        <input class="form-control" type="number" name="per_user_limit"
                            value="{{ old('per_user_limit') }}" required>
                        <span class="input-group-text">@lang('Times')</span>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="form-label">@lang('Date')</label>
                    <input name="date" type="search" class="date-picker form-control" placeholder="@lang('Start Date - End Date')"
                        autocomplete="off" value="" required>
                </div>
                <div class="form-group col-lg-12">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>

    <x-admin.ui.modal id="detailModal">
        <x-admin.ui.modal.header>
            <div class="d-flex gap-1 flex-wrap align-items-center coupon">
                <h4 class="modal-title">
                    @lang('Coupon Details')
                </h4>
                <button class="text--primary editBtn coupon" type="button" data-coupon="">
                    <i class="las la-edit"></i>
                </button>
            </div>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <ul class="plan-details list-group list-group-flush">
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('Name')</span>
                    <span class="name"></span>
                </div>
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('Coupon Code')</span>
                    <span class="code fw-bold"></span>
                </div>
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('Discount Type')</span>
                    <span class="discount_type"></span>
                </div>
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('Amount')</span>
                    <span class="amount"></span>
                </div>
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('Minimum Purchase')</span>
                    <span class="min_purchase_amount"></span>
                </div>
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('Duration')</span>
                    <span class="duration"></span>
                </div>
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('Total Use Limit')</span>
                    <span class="use_limit"></span>
                </div>
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('Per User Limit')</span>
                    <span class="per_user_limit"></span>
                </div>
            </ul>
        </x-admin.ui.modal.body>

    </x-admin.ui.modal>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-admin.permission_check permission="add coupon">
        <div class="d-flex flex-wrap gap-3 flex-fill">
            <button type="button" class="btn btn-outline--primary flex-fill addBtn">
                <i class="la la-plus"></i> @lang('Add New')
            </button>
        </div>
    </x-admin.permission_check>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/flatpickr.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/global/css/flatpickr.min.css') }}">
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            const $createModal = $('#couponModal');
            const $detailModal = $('#detailModal');

            $('.addBtn').on('click', function() {
                let action = "{{ route('admin.coupon.store') }}";
                $createModal.find('form').trigger('reset');
                $createModal.find('form').attr('action', action);
                $createModal.find('.modal-title').text("@lang('Add Coupon')");
                $createModal.modal('show');
            });

            $('.editBtn').on('click', function() {
                $detailModal.modal('hide');

                let coupon = $(this).parent().data('coupon');

                let route = "{{ route('admin.coupon.update', ':id') }}";

                $createModal.find('form').attr('action', route.replace(':id', coupon.id));
                $createModal.find('.modal-title').text("@lang('Edit Coupon')");

                $createModal.find('input[name=name]').val(coupon.name);
                $createModal.find('input[name=code]').val(coupon.code);
                $createModal.find('select[name=type]').val(coupon.type).trigger('change');
                $createModal.find('input[name=amount]').val(parseFloat(coupon.amount).toFixed(2));
                $createModal.find('input[name=min_purchase_amount]').val(parseFloat(coupon.min_purchase_amount)
                    .toFixed(2));
                $createModal.find('input[name=duration]').val(coupon.duration);
                $createModal.find('input[name=use_limit]').val(coupon.use_limit);
                $createModal.find('input[name=per_user_limit]').val(coupon.per_user_limit);
                let startDate = coupon.start_date ? coupon.start_date.split(' ')[0] : null;
                let endDate = coupon.end_date ? coupon.end_date.split(' ')[0] : null;

                let defaultDates = (startDate && endDate) ? [startDate, endDate] : null;

                $('.date-picker').flatpickr({
                    mode: "range",
                    dateFormat: "Y-m-d",
                    defaultDate: defaultDates,
                });

                $createModal.modal('show');
            });

            $('.detailsBtn').on('click', function() {
                let coupon = $(this).parent().data('coupon');
                let duration = $(this).parent().data('duration');
                $detailModal.find('.coupon').data('coupon', coupon);
                let listItem = $detailModal.find('.plan-details');
                listItem.find('.name').text(coupon.name);
                listItem.find('.code').text(coupon.code);
                listItem.find('.discount_type').text(coupon.type ==
                    '{{ Status::COUPON_TYPE_PERCENTAGE }}' ? "{{ __('Percentage') }}" :
                    "{{ __('Fixed') }}");
                if (coupon.type == '{{ Status::COUPON_TYPE_PERCENTAGE }}') {
                    listItem.find('.amount').text(parseFloat(coupon.amount).toFixed(2) + " %");
                } else {
                    listItem.find('.amount').text(parseFloat(coupon.amount).toFixed(2) +
                        " {{ gs('cur_text') }}");
                }
                listItem.find('.min_purchase_amount').text(parseFloat(coupon.min_purchase_amount).toFixed(2) +
                    " {{ gs('cur_text') }}");
                listItem.find('.duration').text(duration);
                listItem.find('.use_limit').text(coupon.use_limit == '{{ Status::UNLIMITED }}' ?
                    "{{ __('Unlimited') }}" : coupon.use_limit + ' ' + "{{ __('times') }}");
                listItem.find('.per_user_limit').text(coupon.per_user_limit == '{{ Status::UNLIMITED }}' ?
                    "{{ __('Unlimited') }}" : coupon.per_user_limit + ' ' + "{{ __('times') }}");
                $detailModal.modal('show');
            });

            $('select[name="type"]').on('change', function() {
                toggleTypeText();
            });

            function toggleTypeText() {
                let type = $('select[name="type"]').val();
                if (type == '{{ Status::COUPON_TYPE_FIXED }}') {
                    $('.type-text').text("{{ __(gs('cur_text')) }}");
                    $('input[name="amount"]').attr('placeholder', "{{ __('Enter fixed amount') }}");
                } else {
                    $('.type-text').text("%");
                    $('input[name="amount"]').attr('placeholder', "{{ __('Enter percentage amount') }}");
                }
            }

            toggleTypeText();

            const $useLimit = $('input[name="use_limit"]');
            const $perUserLimit = $('input[name="per_user_limit"]');

            function checkPerUserLimit() {
                let totalUse = +$useLimit.val();
                let perUser = +$perUserLimit.val();

                if (isNaN(totalUse) || isNaN(perUser)) return;

                if (totalUse == -1) {
                    return;
                }

                if (perUser == -1) {
                    notify('error', "@lang('Per user limit cannot be unlimited when total use is limited')");
                    return;
                }

                if (perUser > totalUse) {
                    notify('error', "@lang('Per user limit cannot be greater than total use limit')");
                    return;
                }
            }

            $perUserLimit.on('input', checkPerUserLimit);
            $useLimit.on('input', checkPerUserLimit);


            // Date picker
            $(".date-picker").flatpickr({
                mode: 'range',
                minDate: new Date(),
                dateFormat: "Y-m-d"
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .verification-switch {
            grid-template-columns: unset;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-left: 10px !important;
        }

        .list-group-item {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding-left: 0;
        }
    </style>
@endpush
