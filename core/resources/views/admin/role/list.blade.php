@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">
            <x-admin.ui.card>
                <x-admin.ui.card.body class="p-0">
                    <x-admin.ui.table.layout :renderExportButton="false" :hasRecycleBin="false">
                        <x-admin.ui.table>
                            <x-admin.ui.table.header>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse($roles as $role)
                                    <tr>
                                        <td>{{ __($role->name) }}</td>
                                        <td>
                                            <div class="btn--group">
                                                <x-admin.permission_check permission="edit role">
                                                    <x-admin.ui.btn.edit tag="btn" :data-role="$role" />
                                                </x-admin.permission_check>
                                                <x-admin.permission_check permission="assign permissions">
                                                    <a href="{{ route('admin.role.permission', $role->id) }}"
                                                        class="btn btn-outline--success">
                                                        <span class="me-1">
                                                            <i class="las la-check-double"></i>
                                                        </span>
                                                        @lang('Permissions')
                                                    </a>
                                                </x-admin.permission_check>
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

    <x-admin.ui.modal id="modal">
        <x-admin.ui.modal.header>
            <h4 class="modal-title">@lang('Add Role')</h4>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form method="POST">
                @csrf
                <div class="form-group">
                    <label>@lang('Name')</label>
                    <input type="text" class="form-control" name="name" required value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>

@endsection
@push('style')
    <style>
        .btn--group {
            justify-content: end;
        }
    </style>
@endpush
@push('script')
    <script>
        "use strict";
        (function($) {

            const $modal = $('#modal');
            const $form = $modal.find('form');

            $('.add-btn').on('click', function() {
                const action = "{{ route('admin.role.create') }}";

                $modal.find('.modal-title').text("@lang('Add Role')");
                $form.trigger('reset');
                $form.attr('action', action);
                $modal.modal('show');
            });

            $('.edit-btn').on('click', function() {
                const action = "{{ route('admin.role.update', ':id') }}";
                const role = $(this).data('role');

                $modal.find('.modal-title').text("@lang('Edit Role')");
                $modal.find('input[name=name]').val(role.name);
                $form.attr('action', action.replace(':id', role.id));
                $modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
@push('breadcrumb-plugins')
    <x-admin.permission_check permission="add role">
        <x-admin.ui.btn.add tag="btn" />
    </x-admin.permission_check>
@endpush
