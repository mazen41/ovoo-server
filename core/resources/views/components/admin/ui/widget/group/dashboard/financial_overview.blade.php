@props(['widget'])
<div class="row responsive-row">
    <div class="col-xxl-6">
        <div class="card shadow-none h-100">
            <div class="card-header d-flex justify-content-between align-items-center gap-3 flex-wrap border-0">
                <h5 class="card-title">@lang('Financial Overview')</h5>
                <ul class="nav nav-pills payment-history" id="pills-tab" role="tablist">
                    <x-admin.permission_check permission="view deposit">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pills-deposit"
                                type="button" role="tab">
                                @lang('Deposit')
                            </button>
                        </li>
                    </x-admin.permission_check>
                    <x-admin.permission_check permission="view withdraw">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-withdraw"
                                type="button" role="tab" aria-controls="pills-withdraw" aria-selected="false">
                                @lang('Withdrawals')
                            </button>
                        </li>
                    </x-admin.permission_check>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="pills-tabContent">
                    <x-admin.permission_check permission="view deposit">
                        <div class="tab-pane fade show active" id="pills-deposit" role="tabpanel">
                            <div class="widget-card-wrapper custom-widget-wrapper">
                                <div class="row g-0">
                                    <div class="col-sm-6">
                                        <div class="widget-card widget--success">
                                            <a href="{{ route('admin.deposit.list') }}" class="widget-card-link"></a>
                                            <div class="widget-card-left">
                                                <span class="widget-icon">
                                                    <i class="fas fa-hand-holding-usd"></i>
                                                </span>
                                                <div class="widget-card-content">
                                                    <p class="widget-title fs-14">@lang('Total Deposits')</p>
                                                    <h6 class="widget-amount">
                                                        {{ gs('cur_sym') }}{{ showAmount($widget['total_deposit_amount'], currencyFormat: false) }}
                                                        <span class="currency">{{ __(gs('cur_text')) }}</span>
                                                    </h6>
                                                </div>
                                            </div>
                                            <span class="widget-card-arrow">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="widget-card widget--warning">
                                            <a href="{{ route('admin.deposit.pending') }}" class="widget-card-link"></a>
                                            <div class="widget-card-left">
                                                <div class="widget-icon">
                                                    <i class="fas fa-spinner"></i>
                                                </div>
                                                <div class="widget-card-content">
                                                    <p class="widget-title fs-14">@lang('Pending Deposits')</p>
                                                    <h6 class="widget-amount">
                                                        {{ gs('cur_sym') }}{{ showAmount($widget['total_deposit_pending'], currencyFormat: false) }}
                                                        <span class="currency">{{ __(gs('cur_text')) }}</span>
                                                    </h6>
                                                </div>
                                            </div>
                                            <span class="widget-card-arrow">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="widget-card widget--danger">
                                            <a href="{{ route('admin.deposit.rejected') }}"
                                                class="widget-card-link"></a>
                                            <div class="widget-card-left">
                                                <div class="widget-icon">
                                                    <i class="fas fa-ban"></i>
                                                </div>
                                                <div class="widget-card-content">
                                                    <p class="widget-title fs-14">@lang('Rejected Deposits')</p>
                                                    <h6 class="widget-amount">
                                                        {{ gs('cur_sym') }}{{ showAmount($widget['total_deposit_rejected'], currencyFormat: false) }}
                                                        <span class="currency">{{ __(gs('cur_text')) }}</span>
                                                    </h6>
                                                </div>
                                            </div>
                                            <span class="widget-card-arrow">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="widget-card widget--primary">
                                            <a href="{{ route('admin.deposit.list') }}" class="widget-card-link"></a>
                                            <div class="widget-card-left">
                                                <div class="widget-icon ">
                                                    <i class="fas fa-percentage"></i>
                                                </div>
                                                <div class="widget-card-content">
                                                    <p class="widget-title fs-14">@lang('Deposited Charge')</p>
                                                    <h6 class="widget-amount">
                                                        {{ gs('cur_sym') }}{{ showAmount($widget['total_deposit_charge'], currencyFormat: false) }}
                                                        <span class="currency">{{ __(gs('cur_text')) }}</span>
                                                    </h6>
                                                </div>
                                            </div>
                                            <span class="widget-card-arrow">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="widget-card widget--warning">
                                            <a href="{{ route('admin.deposit.pending') }}"
                                                class="widget-card-link"></a>
                                            <div class="widget-card-left">
                                                <div class="widget-icon">
                                                    <i class="fas fa-spinner"></i>
                                                </div>
                                                <div class="widget-card-content">
                                                    <p class="widget-title fs-14">@lang('Pending Deposit Count')</p>
                                                    <h6 class="widget-amount">
                                                        {{ $widget['total_deposit_pending_count'] }}
                                                    </h6>
                                                </div>
                                            </div>
                                            <span class="widget-card-arrow">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="widget-card widget--danger">
                                            <a href="{{ route('admin.deposit.rejected') }}"
                                                class="widget-card-link"></a>
                                            <div class="widget-card-left">
                                                <div class="widget-icon">
                                                    <i class="fas fa-ban"></i>
                                                </div>
                                                <div class="widget-card-content">
                                                    <p class="widget-title fs-14">@lang('Rejected Deposit Count')</p>
                                                    <h6 class="widget-amount">
                                                        {{ $widget['total_deposit_rejected_count'] }}
                                                    </h6>
                                                </div>
                                            </div>
                                            <span class="widget-card-arrow">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-admin.permission_check>
                    <x-admin.permission_check permission="view withdraw">
                        <div class="tab-pane fade" id="pills-withdraw" role="tabpanel">
                            <div class="widget-card-wrapper custom-widget-wrapper">
                                <div class="row g-0">
                                    <div class="col-sm-6">
                                        <div class="widget-card widget--success">
                                            <a href="{{ route('admin.withdraw.data.all') }}"
                                                class="widget-card-link"></a>
                                            <div class="widget-card-left">
                                                <div class="widget-icon">
                                                    <i class="fas fa-hand-holding-usd"></i>
                                                </div>
                                                <div class="widget-card-content">
                                                    <p class="widget-title fs-14">@lang('Total Withdrawal')</p>
                                                    <h6 class="widget-amount">
                                                        {{ gs('cur_sym') }}{{ showAmount($widget['total_withdraw_amount'], currencyFormat: false) }}
                                                        <span class="currency">{{ __(gs('cur_text')) }}</span>
                                                    </h6>
                                                </div>
                                            </div>
                                            <span class="widget-card-arrow">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="widget-card widget--warning">
                                            <a href="{{ route('admin.withdraw.data.pending') }}"
                                                class="widget-card-link"></a>
                                            <div class="widget-card-left">
                                                <div class="widget-icon">
                                                    <i class="fas fa-spinner"></i>
                                                </div>
                                                <div class="widget-card-content">
                                                    <p class="widget-title fs-14">@lang('Pending Withdrawal')</p>
                                                    <h6 class="widget-amount">
                                                        {{ gs('cur_sym') }}{{ showAmount($widget['total_withdraw_pending'], currencyFormat: false) }}
                                                        <span class="currency">{{ __(gs('cur_text')) }}</span>
                                                    </h6>
                                                </div>
                                            </div>
                                            <span class="widget-card-arrow">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">

                                        <div class="widget-card widget--danger">
                                            <a href="{{ route('admin.withdraw.data.rejected') }}"
                                                class="widget-card-link"></a>
                                            <div class="widget-card-left">
                                                <div class="widget-icon">
                                                    <i class="fas fa-ban"></i>
                                                </div>
                                                <div class="widget-card-content">
                                                    <p class="widget-title fs-14">@lang('Rejected Withdrawal')</p>
                                                    <h6 class="widget-amount">
                                                        {{ gs('cur_sym') }}{{ showAmount($widget['total_withdraw_rejected'], currencyFormat: false) }}
                                                        <span class="currency">{{ __(gs('cur_text')) }}</span>
                                                    </h6>
                                                </div>
                                            </div>
                                            <span class="widget-card-arrow">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">

                                        <div class="widget-card widget--primary">
                                            <a href="{{ route('admin.withdraw.data.all') }}"
                                                class="widget-card-link"></a>
                                            <div class="widget-card-left">
                                                <div class="widget-icon">
                                                    <i class="fas fa-percentage"></i>
                                                </div>
                                                <div class="widget-card-content">
                                                    <p class="widget-title fs-14">@lang('Withdrawal Charge')</p>
                                                    <h6 class="widget-amount">
                                                        {{ gs('cur_sym') }}{{ showAmount($widget['total_withdraw_charge'], currencyFormat: false) }}
                                                        <span class="currency">{{ __(gs('cur_text')) }}</span>
                                                    </h6>
                                                </div>
                                            </div>
                                            <span class="widget-card-arrow">
                                                <i class="fas fa-chevron-right"></i>'
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">

                                        <div class="widget-card widget--warning">
                                            <a href="{{ route('admin.withdraw.data.pending') }}"
                                                class="widget-card-link"></a>
                                            <div class="widget-card-left">
                                                <div class="widget-icon">
                                                    <i class="fas fa-spinner"></i>
                                                </div>
                                                <div class="widget-card-content">
                                                    <p class="widget-title fs-14">@lang('Pending Withdrawal Count')</p>
                                                    <h6 class="widget-amount">
                                                        {{ $widget['total_withdraw_pending_count'] }}
                                                    </h6>
                                                </div>
                                            </div>
                                            <span class="widget-card-arrow">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">

                                        <div class="widget-card widget--danger">
                                            <a href="{{ route('admin.withdraw.data.rejected') }}"
                                                class="widget-card-link"></a>
                                            <div class="widget-card-left">
                                                <div class="widget-icon">
                                                    <i class="fas fa-ban"></i>
                                                </div>
                                                <div class="widget-card-content">
                                                    <p class="widget-title fs-14">@lang('Rejected Withdrawal Count')</p>
                                                    <h6 class="widget-amount">
                                                        {{ $widget['total_withdraw_rejected_count'] }}
                                                    </h6>
                                                </div>
                                            </div>
                                            <span class="widget-card-arrow">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-admin.permission_check>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-6">
        <x-admin.ui.card class="shadow-none h-100 dw-card">
            <x-admin.ui.card.header class="flex-between py-3 gap-2">
                <h5 class="card-title mb-0 fs-16">@lang('Deposit & Withdraw Report')</h5>
                <div class="d-flex gap-2 flex-wrap flex-md-nowrap">
                    <select class="form-select form-select-sm  form-control">
                        <option value="daily" selected>@lang('Daily')</option>
                        <option value="monthly">@lang('Monthly')</option>
                        <option value="yearly">@lang('Yearly')</option>
                        <option value="date_range">@lang('Date Range')</option>
                    </select>
                    <div class="date-picker-wrapper d-none w-100">
                        <input type="text" class="form-control-sm date-picker form-control" name="date"
                            placeholder="@lang('Select Date')">
                    </div>
                </div>
            </x-admin.ui.card.header>
            <x-admin.ui.card.body>
                <div id="dwChartArea"> </div>
            </x-admin.ui.card.body>
        </x-admin.ui.card>
    </div>
</div>


@push('script')
    <script>
        "use strict";
        (function($) {

            let dwChart = barChart(
                document.querySelector("#dwChartArea"),
                @json(__(gs('cur_text'))),
                [{
                        name: 'Deposited',
                        data: []
                    },
                    {
                        name: 'Withdrawn',
                        data: []
                    }
                ],
                [],
            );
            const depositWithdrawChart = (startDate, endDate) => {
                const url = @json(route('admin.chart.deposit.withdraw'));
                const timePeriod = $(".dw-card").find('select').val();

                if (timePeriod == 'date_range') {
                    $(".dw-card").find('.date-picker-wrapper').removeClass('d-none')
                } else {
                    $(".dw-card").find('.date-picker-wrapper').addClass('d-none')
                }
                const date = $(".dw-card").find('input[name=date]').val();
                const data = {
                    time_period: timePeriod,
                    date: date
                }

                $.get(url, data,
                    function(data, status) {
                        if (data.success) {
                            const updatedData = ['Deposited', 'Withdrawn'].map(name => ({
                                name,
                                data: Object.values(data.data).map(item => item[name.toLowerCase() +
                                    '_amount'])
                            }));

                            dwChart.updateSeries(updatedData);
                            dwChart.updateOptions({
                                xaxis: {
                                    categories: Object.keys(data.data),
                                }
                            });
                        } else {
                            notify('error', data.message);
                        }
                    }
                );
            }
            depositWithdrawChart();

            $(".dw-card").on('change', 'select', function(e) {
                depositWithdrawChart();
            });

            $(".dw-card").on('change', '.date-picker', function(e) {
                depositWithdrawChart();
            });

            let $tabLinks = $('#pills-tab .nav-link:visible');

            if ($tabLinks.length === 1) {
                $tabLinks.addClass('active');
                $tabLinks.attr('aria-selected', 'true');
            }

            let $tabs = $('#pills-tab .nav-link:visible');

            if ($tabs.length == 1) {
                $tabs.addClass('active').attr('aria-selected', 'true');
                let target = $tabs.data('bs-target');
                $(target).addClass('show active');
            }


        })(jQuery);
    </script>
@endpush
