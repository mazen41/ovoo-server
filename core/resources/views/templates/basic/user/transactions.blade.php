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
                        @lang('New Deposit')
                    </a>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <div class="body-top">
                <div class="body-top__left">
                    <form class="search-form">
                        <input type="search" class="form--control" name="search" value="{{ request()->search }}"
                            placeholder="Search transactions..." autocomplete="off">
                        <span class="search-form__icon"> <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                    </form>
                </div>
                <div class="body-top__right">
                    <span class="text"> @lang('Filter by') : </span>
                    <form class="select-group filter-form">
                        <select class="form-select form--control select2" name="remark">
                            <option value="">@lang('Status')</option>
                            @foreach ($remarks as $remark)
                                <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>
                                    {{ __(keyToTitle($remark->remark)) }}</option>
                            @endforeach

                        </select>
                        <select class="form-select form--control select2" name="date">
                            <option value="">@lang('Date')</option>
                            <option value="{{ now()->subDays(7)->format('Y-m-d') }} to {{ now()->format('Y-m-d') }}"
                                @selected(request()->date == now()->subDays(7)->format('Y-m-d') . ' to ' . now()->format('Y-m-d'))>
                                @lang('Last 7days')
                            </option>
                            <option value="{{ now()->subMonth()->format('Y-m-d') }} to {{ now()->format('Y-m-d') }}"
                                @selected(request()->date == now()->subMonth()->format('Y-m-d') . ' to ' . now()->format('Y-m-d'))>
                                @lang('Last Month')
                            </option>
                            <option value="{{ now()->subYear()->format('Y-m-d') }} to {{ now()->format('Y-m-d') }}"
                                @selected(request()->date == now()->subYear()->format('Y-m-d') . ' to ' . now()->format('Y-m-d'))>
                                @lang('Last Year')
                            </option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="dashboard-table">
                <table class="table table--responsive--xxl">
                    <thead>
                        <tr>
                            <th>@lang('Transaction ID')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Post Balance')</th>
                            <th>@lang('Details')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td> {{ $transaction->trx }} </td>
                                <td class="text-lg-center">
                                    {{ showDateTime($transaction->created_at) }}<br>{{ diffForHumans($transaction->created_at) }}
                                </td>
                                <td class="text-lg-center">
                                    <div>
                                        {{ showAmount($transaction->amount) }} + <span class="text--danger"
                                            data-bs-toggle="tooltip"
                                            title="@lang('Processing Charge')">{{ showAmount($transaction->charge) }}
                                        </span>
                                        <br>
                                        <strong data-bs-toggle="tooltip" title="@lang('Amount with charge')">
                                            {{ showAmount($transaction->amount + $transaction->charge) }}
                                        </strong>
                                    </div>
                                </td>
                                <td>{{ showAmount($transaction->post_balance) }}</td>
                                <td> {{ __(@$transaction->details) }} </td>
                            </tr>
                        @empty
                            @include('Template::partials.empty_message')
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ paginateLinks($transactions) }}
            
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
