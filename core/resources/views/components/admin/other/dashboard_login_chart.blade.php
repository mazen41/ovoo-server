<x-admin.ui.card class="tc-card h-100">
    <x-admin.ui.card.header class="py-4">
        <h5 class="card-title fs-16">@lang('User Login by Browser')</h5>
    </x-admin.ui.card.header>
    <x-admin.ui.card.body>
        <div id="userBrowserChart"></div>
    </x-admin.ui.card.body>
</x-admin.ui.card>

@push('script')
    <script>
        "use strict";
        (function($) {
            (function() {
                const labels = @json($userLogin->pluck('browser')->toArray());
                const data = @json($userLogin->pluck('total')->toArray());
                const total = data.reduce((a, b) => a + b, 0);

                const legendLabels = labels.map((label, index) => {
                    const percent = ((data[index] / total) * 100).toFixed(2);
                    return `<div class=" d-flex  flex-column gap-1  align-items-start mb-3 me-1"><span>${percent}%</span> <span>${label}</span> </div>`;
                });
                const options = {
                    series: data,
                    chart: {
                        type: 'donut',
                        height: 420,
                        width: '100%'
                    },
                    labels: labels,
                    dataLabels: {
                        enabled: false,

                    },
                    legend: {
                        position: 'bottom',
                        markers: {
                            show: false // Hide the default markers
                        },
                        formatter: function(seriesName, opts) {
                            return legendLabels[opts.seriesIndex];
                        }
                    }
                };
                new ApexCharts(document.getElementById('userBrowserChart'), options).render();
            })()
        })(jQuery);
    </script>
@endpush
