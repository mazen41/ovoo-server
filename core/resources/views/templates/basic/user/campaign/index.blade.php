@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Manage your campaigns and explore performance stats right here')</p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <a href="{{ route('user.campaign.create') }}" class="btn btn--base btn-shadow">
                        <i class="las la-plus"></i>
                        @lang('Add New')
                    </a>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <div class="body-top">
                <div class="body-top__left">
                    <form class="search-form">
                        <input type="search" class="form--control" placeholder="@lang('Search here')..." name="search"
                            autocomplete="off" value="{{ request()->search }}">
                        <span class="search-form__icon"> <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                    </form>
                </div>
                <div class="body-top__right">
                    <form class="select-group filter-form">
                        <select class="form-select form--control select2" name="status">
                            <option selected value="">@lang('Filter Campaign Status')</option>
                            <option value="{{ Status::CAMPAIGN_RUNNING }}" @selected(request()->status == Status::CAMPAIGN_RUNNING)>
                                @lang('Running')
                            </option>

                            <option value="{{ Status::CAMPAIGN_SCHEDULED }}" @selected(request()->status == Status::CAMPAIGN_SCHEDULED)>
                                @lang('Scheduled')
                            </option>

                            <option value="{{ Status::CAMPAIGN_COMPLETED }}" @selected(request()->status == Status::CAMPAIGN_COMPLETED)>
                                @lang('Completed')
                            </option>

                        </select>

                        <select class="form-select form--control select2" name="export">
                            <option selected value="">@lang('Export')</option>
                            <option value="excel">
                                @lang('Excel')
                            </option>
                            <option value="csv">
                                @lang('CSV')
                            </option>
                            <option value="pdf">
                                @lang('PDF')
                            </option>
                            <option value="print">
                                @lang('Print')
                            </option>
                        </select>

                        <x-whatsapp_account :isHide="true" />
                    </form>
                </div>
            </div>
            <div class="dashboard-table">
                <table class="table table--responsive--lg">
                    <thead>
                        <tr>
                            <th>@lang('Title')</th>
                            <th>@lang('Total Message')</th>
                            <th>@lang('Campaign Date')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($campaigns as $campaign)
                            <tr>
                                <td>{{ __(@$campaign->title) }}</td>
                                <td>{{ @$campaign->total_message }}</td>
                                <td>
                                    {{ showDateTime($campaign->send_at) }}<br>{{ diffForHumans($campaign->send_at) }}
                                </td>
                                <td>@php echo $campaign->statusBadge @endphp </td>
                                <td>
                                    <a href="{{ route('user.campaign.report', $campaign->id) }}"
                                        class="btn btn--base btn-shadow btn--sm">
                                        <i class=" la la-chart-bar"></i> @lang('View Report')
                                    </a>
                                </td>
                            </tr>
                        @empty
                            @include('Template::partials.empty_message')
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ paginateLinks(@$campaigns) }}
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

            $('.filter-form').find('select').on('change', function() {
                $('.filter-form').submit();
            });
        })(jQuery);
    </script>
@endpush
