@extends('admin.layouts.app')
@section('panel')
    @if (request()->routeIs('admin.deposit.list') || request()->routeIs('admin.deposit.method'))
        @include('admin.deposit.widget')
    @endif
    <div class="row">
        <div class="col-12">
            <x-admin.ui.card class="table-has-filter">
                <x-admin.ui.card.body :paddingZero="true">
                    <x-admin.ui.table.layout searchPlaceholder="Username / TRX" filterBoxLocation="deposit.filter_form">
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
                                @forelse($deposits as $deposit)
                                    <tr>
                                        <td>
                                            <x-admin.other.user_info :user="$deposit->user" />
                                        </td>
                                        <td>
                                            <div>
                                                <span class="fw-bold">
                                                    <a
                                                        href="{{ appendQuery('method', $deposit->method_code < 5000 ? @$deposit->gateway->alias : $deposit->method_code) }}">
                                                        @if ($deposit->method_code < 5000)
                                                            {{ __(@$deposit->gateway->name) }}
                                                        @else
                                                            @lang('Google Pay')
                                                        @endif
                                                    </a>
                                                </span>
                                                <br>
                                                <small> {{ $deposit->trx }} </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                {{ showDateTime($deposit->created_at) }}<br>{{ diffForHumans($deposit->created_at) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                {{ showAmount($deposit->amount) }} + <span class="text--danger"
                                                    title="@lang('charge')">{{ showAmount($deposit->charge) }} </span>
                                                <br>
                                                <strong title="@lang('Amount with charge')">
                                                    {{ showAmount($deposit->amount + $deposit->charge) }}
                                                </strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                {{ showAmount(1) }} =
                                                {{ showAmount($deposit->rate, currencyFormat: false) }}
                                                {{ __($deposit->method_currency) }}
                                                <br>
                                                <strong>{{ showAmount($deposit->final_amount, currencyFormat: false) }}
                                                    {{ __($deposit->method_currency) }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            @php echo $deposit->statusBadge @endphp
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.deposit.details', $deposit->id) }}"
                                                class="btn  btn-outline--primary ms-1 table-action-btn">
                                                <i class="las la-info-circle"></i> @lang('Details')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <x-admin.ui.table.empty_message />
                                @endforelse
                            </x-admin.ui.table.body>
                        </x-admin.ui.table>
                        @if ($deposits->hasPages())
                            <x-admin.ui.table.footer>
                                {{ paginateLinks($deposits) }}
                            </x-admin.ui.table.footer>
                        @endif
                    </x-admin.ui.table.layout>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection

