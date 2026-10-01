@extends('admin.layouts.app')
@section('panel')
    @if (request()->routeIs('admin.withdraw.data.all') || request()->routeIs('admin.withdraw.method'))
        @include('admin.withdraw.widget')
    @endif
    <div class="row">
        <div class="col-12">
            <x-admin.ui.card class="table-has-filter">
                <x-admin.ui.card.body :paddingZero="true">
                    <x-admin.ui.table.layout searchPlaceholder="Username / TRX" filterBoxLocation="withdraw.filter_form">
                        <x-admin.ui.table>
                            <x-admin.ui.table.header>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Gateway | Transaction')</th>
                                    <th>@lang('Initiated')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Conversion')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse($withdrawals as $withdraw)
                                    <tr>
                                        <td>
                                            <x-admin.other.user_info :user="$withdraw->user" />
                                        </td>
                                        <td>
                                            <div>
                                                <span class="fw-bold">
                                                    <a href="{{ appendQuery('method', @$withdraw->method->id) }}">
                                                        {{ __(@$withdraw->method->name) }}</a></span>
                                                <br>
                                                <small>{{ $withdraw->trx }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                {{ showDateTime($withdraw->created_at) }} <br>
                                                {{ diffForHumans($withdraw->created_at) }}
                                            </div>
                                        </td>

                                        <td>
                                            <div>
                                                {{ showAmount($withdraw->amount) }} - <span class="text--danger"
                                                    title="@lang('charge')">{{ showAmount($withdraw->charge) }} </span>
                                                <br>
                                                <strong title="@lang('Amount after charge')">
                                                    {{ showAmount($withdraw->amount - $withdraw->charge) }}
                                                </strong>
                                            </div>
                                        </td>

                                        <td>
                                            <span>
                                                {{ showAmount(1) }} =
                                                {{ showAmount($withdraw->rate, currencyFormat: false) }}
                                                {{ __($withdraw->currency) }}
                                                <br>
                                                <strong>{{ showAmount($withdraw->final_amount, currencyFormat: false) }}
                                                    {{ __($withdraw->currency) }}</strong>
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                @php echo $withdraw->statusBadge @endphp
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.withdraw.data.details', $withdraw->id) }}"
                                                class="btn  btn-outline--primary table-action-btn">
                                                <i class="las la-info-circle"></i> @lang('Details')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <x-admin.ui.table.empty_message />
                                @endforelse
                            </x-admin.ui.table.body>
                        </x-admin.ui.table>
                        @if ($withdrawals->hasPages())
                            <x-admin.ui.table.footer>
                                {{ paginateLinks($withdrawals) }}
                            </x-admin.ui.table.footer>
                        @endif
                    </x-admin.ui.table.layout>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection


