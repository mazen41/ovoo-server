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
                                    <th>@lang('Interval')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Actions')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse ($schedules as $schedule)
                                    <tr>
                                        <td>{{ __($schedule->name) }}</td>
                                        <td>{{ __($schedule->interval) }} @lang('Seconds')</td>
                                        <td> @php echo $schedule->statusBadge; @endphp </td>
                                        <td>
                                            <div class="d-flex gap-2 flex-wrap justify-content-end">
                                                <button type="button" class="btn  btn-outline--primary editBtn"
                                                    data-schedule='@json($schedule)'>
                                                    <i class="las la-edit"></i>
                                                    @lang('Edit')
                                                </button>

                                                @if (!$schedule->status)
                                                    <button type="button"
                                                        class="btn  btn-outline--success confirmationBtn"
                                                        data-action="{{ route('admin.cron.schedule.status', $schedule->id) }}"
                                                        data-question="@lang('Are you sure to enable this schedule?')">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button type="button"
                                                        class="btn  btn-outline--danger confirmationBtn"
                                                        data-action="{{ route('admin.cron.schedule.status', $schedule->id) }}"
                                                        data-question="@lang('Are you sure to disable this schedule?')">
                                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                                    </button>
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

    <x-admin.ui.modal id="scheduleModal">
        <x-admin.ui.modal.header>
            <h1 class="modal-title">@lang('Add Cron Schedule')</h1>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form method="post" action="{{ route('admin.cron.schedule.store') }}">
                @csrf
                <div class="form-group">
                    <label>@lang('Name')</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="form-group">
                    <label>@lang('Interval')</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="interval" required>
                        <span class="input-group-text">@lang('Seconds')</span>
                    </div>
                </div>
                <div class="form-group">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap gap-2">
        <button class="btn  btn-outline--primary addBtn"><i class="las la-plus"></i> @lang('Add New')</button>
        <x-back_btn route="{{ route('admin.cron.index') }}" />
    </div>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.addBtn').on('click', function() {
                const $modal = $('#scheduleModal');
                const action = "{{ route('admin.cron.schedule.store') }}";

                $modal.find(".modal-title").text("@lang('Add Cron Schedule')");
                $modal.find("form").attr('action', action).trigger('reset');
                $modal.modal('show');
            });

            $('.editBtn').on('click', function(e) {
                const $modal = $('#scheduleModal');
                const schedule = $(this).data('schedule');
                const action = "{{ route('admin.cron.schedule.store', ':id') }}";

                $modal.find(".modal-title").text("@lang('Edit Cron Schedule')");
                $modal.find('input[name=name]').val(schedule.name);
                $modal.find('input[name=interval]').val(schedule.interval);
                $modal.find("form").attr('action', action.replace(':id', schedule.id));
                $modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
