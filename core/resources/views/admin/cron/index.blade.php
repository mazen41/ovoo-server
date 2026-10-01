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
                                    <th>@lang('Name')</th>
                                    <th>@lang('Schedule')</th>
                                    <th>@lang('Next Run')</th>
                                    <th>@lang('Last Run')</th>
                                    <th>@lang('Is Running')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Actions')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse ($crons as $cron)
                                    @php
                                        $dateTime = now()->parse($cron->next_run);
                                        $formattedDateTime = showDateTime($dateTime, 'Y-m-d\TH:i');
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ __($cron->name) }} @if ($cron->logs->where('error', '!=', null)->count())
                                                <i class="fas fa-exclamation-triangle text--danger"></i>
                                            @endif <br>
                                            <code>{{ __($cron->alias) }}</code>
                                        </td>
                                        <td>{{ __($cron->schedule->name) }}</td>
                                        <td>
                                            @if ($cron->next_run)
                                                {{ __($cron->next_run) }} @if ($cron->next_run > now())
                                                    <br> {{ diffForHumans($cron->next_run) }}
                                                @endif
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td>
                                            @if ($cron->last_run)
                                                {{ __($cron->last_run) }}
                                                <br> {{ diffForHumans($cron->last_run) }}
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td>
                                            @if ($cron->is_running)
                                                <span class=" badge badge--success">@lang('Running')</span>
                                            @else
                                                <span class="badge badge--dark">@lang('Pause')</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($cron->is_default)
                                                <span class=" badge badge--success">@lang('Default')</span>
                                            @else
                                                <span class=" badge badge--primary">@lang('Customizable')</span>
                                            @endif
                                        </td>
                                        <td class="dropdown">
                                            <button class=" btn btn-outline--primary" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                @lang('Action')<i class="las la-ellipsis-v ms-1"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown">
                                                <a class="dropdown-list d-block"
                                                    href="{{ route('cron') }}?alias={{ $cron->alias }}">
                                                    <span class="me-2">
                                                        <i class="fas fa-check-circle text--success"></i>
                                                    </span>
                                                    @lang('Run Now')
                                                </a>
                                                @if ($cron->is_running)
                                                    <a href="{{ route('admin.cron.schedule.pause', $cron->id) }}"
                                                        class="dropdown-list d-block">
                                                        <span class="me-2">
                                                            <i class="fas fa-pause text--info"></i>
                                                        </span>
                                                        @lang('Pause')
                                                    </a>
                                                @else
                                                    <a href="{{ route('admin.cron.schedule.pause', $cron->id) }}"
                                                        class="dropdown-list d-block">
                                                        <span class="me-2">
                                                            <i class="fas fa-play text--info"></i>
                                                        </span>
                                                        @lang('Play')
                                                    </a>
                                                @endif
                                                <a type="button" data-cron='@json($cron)'
                                                    class="dropdown-list d-block editBtn"
                                                    data-next-run="{{ $formattedDateTime }}" class="editBtn">
                                                    <span class="me-2"><i class="fas fa-pen text--primary"></i></span>
                                                    @lang('Edit')
                                                </a>
                                                <a href="{{ route('admin.cron.schedule.logs', $cron->id) }}"
                                                    class="dropdown-list d-block">
                                                    <span class="me-2">
                                                        <i class="fas fa-history text--info"></i>
                                                    </span>
                                                    @lang('Logs')
                                                </a>
                                                @if (!$cron->is_default)
                                                    <a type="button" class="dropdown-list d-block confirmationBtn"
                                                        href="javascript:void(0)"
                                                        data-action="{{ route('admin.cron.delete', $cron->id) }}"
                                                        data-question="@lang('Are you sure to delete this cron?')">
                                                        <span class="me-2">
                                                            <i class="fas fa-trash text--danger"></i>
                                                        </span>
                                                        @lang('Delete')
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <x-admin.ui.table.empty_message />
                                @endforelse
                            </x-admin.ui.table.body>
                        </x-admin.ui.table>
                    </x-admin.ui.table.layout>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>

    <x-confirmation-modal />

    <x-admin.ui.modal id="cronModal">
        <x-admin.ui.modal.header>
            <h1 class="modal-title">@lang('Add Cron Job')</h1>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form method="post" action="{{ route('admin.cron.store') }}">
                @csrf
                <div class="form-group">
                    <label>@lang('Name')</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="form-group">
                    <label>@lang('Next Run')</label>
                    <input type="datetime-local" name="next_run" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>@lang('Schedule')</label>
                    <select name="cron_schedule_id" class="form-control select2" data-minimum-results-for-search="-1"
                        required>
                        @foreach ($schedules as $schedule)
                            <option value="{{ $schedule->id }}">{{ $schedule->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>@lang('Url')</label>
                    <input type="url" name="url" class="form-control" required>
                </div>
                <div class="form-group">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex gap-2 flex-wrap">
        <button type="button" class="btn  btn--primary flex-fill addCron">
            <i class="las la-plus"></i> @lang('Add')
        </button>
        <a href="{{ route('admin.cron.schedule') }}" class="btn btn--info flex-fill">
            <i class="las la-clock"></i>
            @lang('Cron Schedule')
        </a>
    </div>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.addCron').on('click', function() {
                const $modal = $('#cronModal');
                const action = "{{ route('admin.cron.store') }}";
                $modal.find(".modal-title").text("@lang('Add Cron Job')");
                $modal.find("form").attr('action', action).trigger('reset');
                $modal.find('input[name=url]').attr('required', true).parent().show();
                $modal.modal('show');
            });

            $('.editBtn').on('click', function(e) {
                const $modal = $('#cronModal');
                const cron = $(this).data('cron');
                const nextRun = $(this).data('nextRun');
                const action = "{{ route('admin.cron.update', ':id') }}";

                $modal.find(".modal-title").text("@lang('Edit Cron Job')");
                $modal.find('input[name=name]').val(cron.name);
                $modal.find('input[name=next_run]').val(nextRun);
                $modal.find('select[name=cron_schedule_id]').val(cron.cron_schedule_id).change();
                if (cron.is_default) {
                    $modal.find('input[name=url]').attr('required', false).parent().hide();
                } else {
                    $modal.find('input[name=url]').val(cron.url).attr('required', true).parent().show();
                }
                $modal.find("form").attr('action', action.replace(':id', cron.id));
                $modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
