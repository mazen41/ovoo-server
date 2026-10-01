@extends('admin.layouts.app')
@section('panel')
    @include('admin.plans.widget')
    <div class="row">
        <div class="col-12">
            <x-admin.ui.card class="table-has-filter">
                <x-admin.ui.card.body :paddingZero="true">
                    <x-admin.ui.table.layout :renderExportButton="false" >
                        <x-admin.ui.table>
                            <x-admin.ui.table.header>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Plan Name')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Recurring Type')</th>
                                    <th>@lang('Purchase Date')</th>
                                    <th>@lang('Expire Date')</th>
                                    <th>@lang('Payment Method')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse($subscriptions as $subscription)
                                    <tr>
                                        <td>
                                            <x-admin.other.user_info :user="$subscription->user" />
                                        </td>
                                        <td>{{ __(@$subscription->plan->name) }}</td>
                                        <td>{{ showAmount(@$subscription->amount) }}</td>
                                        <td>{{ @$subscription->billing_cycle }}</td>
                                        <td>{{ showDateTime(@$subscription->created_at, 'd M Y') }}</td>
                                        <td>{{ showDateTime(@$subscription->expired_at, 'd M Y') }}</td>
                                        <td>{{ $subscription->get_payment_method }}</td>
                                    </tr>
                                @empty
                                    <x-admin.ui.table.empty_message />
                                @endforelse
                            </x-admin.ui.table.body>
                        </x-admin.ui.table>
                        @if ($subscriptions->hasPages())
                            <x-admin.ui.table.footer>
                                {{ paginateLinks($subscriptions) }}
                            </x-admin.ui.table.footer>
                        @endif
                    </x-admin.ui.table.layout>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection
