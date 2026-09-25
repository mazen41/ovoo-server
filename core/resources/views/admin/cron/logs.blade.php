@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">
            <x-admin.ui.card>
                <x-admin.ui.card.body :paddingZero="true">
                    <x-admin.ui.table.layout :renderTableFilter=false>
                        <x-admin.ui.table>
                            <x-admin.ui.table.header>
                                <tr>
                                    <th>@lang('Start At')</th>
                                    <th>@lang('End At')</th>
                                    <th>@lang('Execution Time')</th>
                                    <th>@lang('Error')</th>
                                    <th>@lang('Actions')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse ($logs as $log)
                                    <tr>
                                        <td>{{ showDateTime($log->start_at) }} </td>
                                        <td>{{ showDateTime($log->end_at) }} </td>
                                        <td>{{ $log->duration }} @lang('Seconds')</td>
                                        <td>{{ $log->error }}</td>
                                        <td>
                                            @if ($log->error != null)
                                                <button type="button" class="btn  btn-outline--success confirmationBtn"
                                                    data-action="{{ route('admin.cron.schedule.log.resolved', $log->id) }}"
                                                    data-question="@lang('Are you sure to resolved this log?')">
                                                    <i class="la la-check"></i> @lang('Resolved')
                                                </button>
                                            @else
                                                --
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <x-admin.ui.table.empty_message />
                                @endforelse
                            </x-admin.ui.table.body>
                        </x-admin.ui.table>
                        @if ($logs->hasPages())
                            <x-admin.ui.table.footer>
                                {{ paginateLinks($logs) }}
                            </x-admin.ui.table.footer>
                        @endif
                    </x-admin.ui.table.layout>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap gap-2">
        <button type="button" class="btn  btn-outline--danger confirmationBtn"
            data-action="{{ route('admin.cron.log.flush', $cronJob->id) }}" data-question="@lang('Are you sure to flush all logs?')">
            <i class="la la-history"></i> @lang('Flush Logs')
        </button>
        <x-back_btn route="{{ route('admin.cron.index') }}" />
    </div>
@endpush
