<div class="col-xl-8">
    <x-admin.ui.card class="tc-card">
        <x-admin.ui.card.header class="flex-between gap-2 py-3">
            <h5 class="card-title mb-0 fs-16">@lang('Transactions Report')</h5>
            <div class="d-flex gap-2 flex-wrap flex-md-nowrap">
                <select class="form-select form-control form-select-sm">
                    <option value="daily" selected>@lang('Daily')</option>
                    <option value="monthly">@lang('Monthly')</option>
                    <option value="yearly">@lang('Yearly')</option>
                    <option value="date_range">@lang('Date Range')</option>
                </select>
                <div class="date-picker-wrapper d-none w-100">
                    <input type="text" class="form-control form-control-sm date-picker" name="date"
                        placeholder="@lang('Select Date')">
                </div>
            </div>
        </x-admin.ui.card.header>
        <x-admin.ui.card.body>
            <div id="transactionChartArea"></div>
        </x-admin.ui.card.body>
    </x-admin.ui.card>
</div>

@push('script')
    <script>
        "use strict";
        (function($) {

            let tcChart = barChart(
                document.querySelector("#transactionChartArea"),
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
            const transactionChart = (startDate, endDate) => {
                const url = @json(route('admin.chart.transaction'));
                const timePeriod = $(".tc-card").find('select').val();
                if (timePeriod == 'date_range') {
                    $(".tc-card").find('.date-picker-wrapper').removeClass('d-none')
                } else {
                    $(".tc-card").find('.date-picker-wrapper').addClass('d-none')
                }
                const date = $(".tc-card").find('input[name=date]').val();
                const data = {
                    time_period: timePeriod,
                    date: date
                }

                $.get(url, data,
                    function(data, status) {
                        if (data.success) {
                            const plusAmount = Object.values(data.data).map(item => item.plus_amount);
                            const minusAmount = Object.values(data.data).map(item => item.minus_amount);
                            const updatedData = [{
                                    name: "Plus Transactions",
                                    data: plusAmount,
                                },
                                {
                                    name: "Minus Transactions",
                                    data: minusAmount,
                                }
                            ]

                            tcChart.updateSeries(updatedData);
                            tcChart.updateOptions({
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
            transactionChart();

            $(".tc-card").on('change', 'select', function(e) {
                transactionChart();
            });
            $(".tc-card").on('change', '.date-picker', function(e) {
                transactionChart();
            });
        })(jQuery);
    </script>
@endpush
