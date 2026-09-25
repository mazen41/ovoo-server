@extends($activeTemplate . 'layouts.master')
@section('content')
    @php
        function divisionValue($value1, $value2)
        {
            if ($value2 == 0 || $value1 == 0) {
                return 0;
            }
            return ($value1 / $value2) * 100;
        }
        $sentRatio = divisionValue($widget['total_sent'], $widget['total_message']);
        $deliveryRatio = divisionValue($widget['total_delivered'], $widget['total_sent']);
        $readRatio = divisionValue($widget['total_read'], $widget['total_sent']);
        $failureRatio = divisionValue($widget['total_failed'], $widget['total_sent']);
    @endphp
    <div class="container-fluid">
        <div class="row gy-4">
            <div class="col-12">
                <p class="text">
                    @lang('Here’s your overview of campaign : :campaign_title', ['campaign_title' => $campaign->title])
                </p>
            </div>
            <div class="col-xxl-4 col-xl-6">
                <div class="stat-card primary">
                    <div class="stat-card-header">
                        <div class="stat-card-content">
                            <div class="stat-card-title">@lang('Campaign Start')</div>
                            <div class="stat-card-value">{{ showDateTime($campaign->send_at, 'd M Y') }}</div>
                        </div>
                        <div class="stat-card-icon">
                            <i class="fa-regular fa-calendar"></i>
                        </div>
                    </div>
                    <div class="stat-card-footer">
                        <span>
                            @if (now()->parse($campaign->send_at)->isFuture())
                                @lang('Scheduled')
                            @else
                                {{ diffForHumans($campaign->send_at) }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-6">
                <div class="stat-card info">
                    <div class="stat-card-header">
                        <div class="stat-card-content">
                            <div class="stat-card-title">@lang('Total Messages')</div>
                            <div class="stat-card-value">{{ getAmount($widget['total_message']) }}</div>
                        </div>
                        <div class="stat-card-icon">
                            <i class="fa-regular fa-rectangle-list"></i>
                        </div>
                    </div>
                    <div class="stat-card-footer">
                        @if ($widget['total_message'] > $lastCampaignMessageCount)
                            <span class="trend-badge positive">
                                @lang('Last Campaign Was') {{ $lastCampaignMessageCount }}
                            </span>
                        @else
                            <span class="trend-badge negative">
                                @lang('Last Campaign Was') {{ $lastCampaignMessageCount }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xxl-4 col-xl-6">
                <div class="stat-card primary">
                    <div class="stat-card-header">
                        <div class="stat-card-content">
                            <div class="stat-card-title">@lang('Total Sent')</div>
                            <div class="stat-card-value">{{ getAmount($widget['total_sent']) }}</div>
                        </div>
                        <div class="stat-card-icon">
                            <i class="fa-regular fa-paper-plane"></i>
                        </div>
                    </div>
                    <div class="stat-card-footer">
                        <span class="trend-badge  @if ($sentRatio <= 50) negative @else positive @endif">
                            {{ getAmount($sentRatio) }}%</span>
                        <span>@lang('Sent Ratio')</span>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-6">
                <div class="stat-card success">
                    <div class="stat-card-header">
                        <div class="stat-card-content">
                            <div class="stat-card-title">@lang('Total Delivered')</div>
                            <div class="stat-card-value">{{ getAmount($widget['total_delivered']) }}</div>
                        </div>
                        <div class="stat-card-icon">
                            <i class="fa-regular fa-circle-check"></i>
                        </div>
                    </div>
                    <div class="stat-card-footer">
                        <span class="trend-badge  @if ($deliveryRatio <= 50) negative @else positive @endif">
                            {{ getAmount($deliveryRatio) }}%
                        </span>
                        <span>@lang('Delivery Ratio')</span>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-6">
                <div class="stat-card info">
                    <div class="stat-card-header">
                        <div class="stat-card-content">
                            <div class="stat-card-title">@lang('Total Read')</div>
                            <div class="stat-card-value">{{ getAmount($widget['total_read']) }}</div>
                        </div>
                        <div class="stat-card-icon">
                            <i class="fa-regular fa-file-lines"></i>
                        </div>
                    </div>
                    <div class="stat-card-footer">
                        <span class="trend-badge positive">
                            <span class="trend-badge  @if ($readRatio <= 50) negative @else positive @endif">
                                {{ getAmount($readRatio) }}%
                            </span>
                            <span>
                                @lang('Read Ratio')
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xxl-4 col-xl-6">
                <div class="stat-card danger">
                    <div class="stat-card-header">
                        <div class="stat-card-content">
                            <div class="stat-card-title">@lang('Total Failed')</div>
                            <div class="stat-card-value">{{ getAmount($widget['total_failed']) }}</div>
                        </div>
                        <div class="stat-card-icon">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </div>
                    </div>
                    <div class="stat-card-footer">
                        <span class="trend-badge  @if ($failureRatio <= 50) positive  @else negative @endif">
                            {{ getAmount($failureRatio) }}%
                        </span>
                        <span>@lang('Failure Ratio')</span>
                    </div>
                </div>
            </div>
            <div class="col-xxl-8 col-lg-6">
                <div class="card custom--card bg-white h-100">
                    <div class="card-header border-bottom d-flex gap-2 justify-content-between flex-wrap">
                        <h4 class="mb-0"> @lang('Message Log')</h4>
                        <form>
                            <select class="form-control select2 status-filter" name="status">
                                <option value="{{ Status::SENT }}" @selected(request('status') == Status::SENT)>
                                    @lang('Sent')
                                </option>
                                <option value="{{ Status::DELIVERED }} " @selected(request('status') == Status::DELIVERED)>
                                    @lang('Delivered')
                                </option>
                                <option value="{{ Status::READ }}" @selected(request('status') == Status::READ)>
                                    @lang('Read')
                                </option>
                                <option value="{{ Status::FAILED }}" @selected(request('status') == Status::FAILED)>
                                    @lang('Failed')
                                </option>
                            </select>
                        </form>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table--responsive--md">
                            <thead>
                                <tr>
                                    <th>@lang('Contact Number')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Sent At')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($messages as $message)
                                    <tr>
                                        <td>{{ __(@$message->conversation->contact->mobileNumber) }}</td>
                                        <td>
                                            @if ($message->status == Status::SENT)
                                                <span class="custom--badge badge badge--dark">@lang('Sent')</span>
                                            @elseif($message->status == Status::DELIVERED)
                                                <span class="custom--badge badge badge--info">@lang('Delivered')</span>
                                            @elseif($message->status == Status::READ)
                                                <span class="custom--badge badge badge--success">@lang('Read')</span>
                                            @else
                                                <span class="custom--badge badge badge--danger" data-bs-toggle="tooltip"
                                                    data-bs-title="{{ __($message->error_message) }}">
                                                    @lang('Failed')
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ showDateTime($message->created_at) }} <br>
                                            {{ diffForHumans($message->created_at) }}
                                        </td>
                                    </tr>
                                @empty
                                    @include('Template::partials.empty_message')
                                @endforelse
                            </tbody>
                        </table>
                        {{ paginateLinks($messages) }}
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-lg-6">
                <div class="card custom--card bg-white">
                    <div class="card-header border-bottom">
                        <h4 class="mb-0"> @lang('Campaign Overview')</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart" class="chart"></div>
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
    <script src="{{ asset('assets/admin/js/apexcharts.min.js') }}"></script>
@endpush

@php
    unset($widget['total_message']);
@endphp
@push('script')
    <script>
        (function($) {
            "use strict";

            $('.filter-form').find('select').on('change', function() {
                $('.filter-form').submit();
            });
            var options = {

                series: [{{ $sentRatio }}, {{ $deliveryRatio }}, {{ $readRatio }}, {{ $failureRatio }}],
                chart: {
                    height: 350,
                    type: 'pie',
                },
                tooltip: {
                    enabled: true,
                    theme: false,
                    x: {
                        show: true,
                        format: 'dd MMM',
                        formatter: undefined,
                    },
                    y: {
                        title: {
                            formatter: (seriesName) =>
                                `<span class="font-semibold text-white">${snakeCaseToWords(seriesName)}</span>`,
                        },
                        formatter: function(value) {
                            return `<span class="text-white">${parseFloat(value).toFixed(2) + "%"}</span>`;
                        },
                        style: {
                            fontSize: '20px',
                            color: "#ffffff"
                        }
                    }
                },

                legend: {
                    position: 'bottom',
                    markers: {
                        show: false // Hide the default markers
                    },
                    formatter: function(seriesName, opts) {
                        return snakeCaseToWords(seriesName);
                    }
                },
                labels: ['Total Sent', 'Total Delivered', 'Total Read', 'Total Failed'],
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();


            function snakeCaseToWords(snakeCase) {
                return snakeCase
                    .split('_')
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1)
                        .toLowerCase())
                    .join(' ');
            }

            $('.status-filter').on('change', function() {
                $(this).closest('form').submit();
            });


        })(jQuery);
    </script>
@endpush


@push('style')
    <style>
        .stat-card {
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
            border-color: rgba(0, 0, 0, 0.1);
        }

        /* Light Gradient Backgrounds for Each Card */
        .stat-card.primary {
            background: linear-gradient(135deg, #f5f7ff 0%, #faf8ff 100%);
        }

        .stat-card.info {
            background: linear-gradient(135deg, #f0fbff 0%, #f0fffc 100%);
        }

        .stat-card.success {
            background: linear-gradient(135deg, #f0fffc 0%, #f5fff8 100%);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, #fffafc 0%, #fffeef 100%);
        }

        .stat-card.danger {
            background: linear-gradient(135deg, #fff5f9 0%, #fff8f8 100%);
        }

        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .stat-card-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .stat-card-icon i {
            width: 20px;
            height: 20px;
            color: white;
        }

        .stat-card.primary .stat-card-icon {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .stat-card.info .stat-card-icon {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
        }

        .stat-card.success .stat-card-icon {
            background: linear-gradient(135deg, #11998e, #38ef7d);
        }

        .stat-card.warning .stat-card-icon {
            background: linear-gradient(135deg, #fa709a, #fee140);
        }

        .stat-card.danger .stat-card-icon {
            background: linear-gradient(135deg, #f093fb, #f5576c);
        }

        .stat-card:hover .stat-card-icon {
            transform: scale(1.05);
        }

        .stat-card-content {
            flex: 1;
            min-width: 0;
        }

        .stat-card-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.375rem;
        }

        .stat-card-value {
            font-size: 1.875rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1;
            letter-spacing: -0.025em;
        }

        .stat-card-footer {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            font-size: 0.75rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        .trend-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .trend-badge.positive {
            background: rgba(34, 197, 94, 0.15);
            color: #15803d;
        }

        .trend-badge.negative {
            background: rgba(239, 68, 68, 0.15);
            color: #dc2626;
        }

        .pagination .page-item .page-link {
            height: 30px;
            width: 30px;
            font-size: 14px;
        }

        .apexcharts-tooltip {
            background: #f3f3f3;
            color: orange;
        }
    </style>
@endpush
