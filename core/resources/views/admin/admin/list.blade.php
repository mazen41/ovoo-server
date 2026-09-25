@extends('admin.layouts.app')
@php
    $authAdminId = auth('admin')->id();
@endphp
@section('panel')
    <div class="row">
        <div class="col-12">
            <x-admin.ui.card>
                <x-admin.ui.card.body :paddingZero=true>
                    <x-admin.ui.table.layout :renderTableFilter="false">
                        <x-admin.ui.table>
                            <x-admin.ui.table.header>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Username')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Role')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse($admins as $admin)
                                    <tr>
                                        <td>{{ __($admin->name) }}</td>
                                        <td>{{ __($admin->email) }}</td>
                                        <td>{{ __($admin->username) }}</td>
                                        <td>
                                            @if (auth('admin')->user()->can('edit admin'))
                                                <x-admin.other.status_switch :status="$admin->status" :action="route('admin.status.change', $admin->id)"
                                                    title="admin" />
                                            @else
                                                {!! $admin->statusBadge !!}
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                @forelse ($admin->roles as $role)
                                                    <span class="badge badge--primary">{{ __($role->name) }}</span>
                                                @empty
                                                    <span class="badge badge--dark">@lang('Unassigned')</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 flex-wrap justify-content-end">
                                                @if ($authAdminId == Status::SUPPER_ADMIN_ID)
                                                    <x-admin.permission_check permission="edit admin">
                                                        <x-admin.ui.btn.edit tag="btn" :data-admin="$admin" />
                                                    </x-admin.permission_check>
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

    <x-admin.ui.modal id="modal">
        <x-admin.ui.modal.header>
            <h4 class="modal-title">@lang('Add Admin')</h4>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form method="POST" action="">
                @csrf
                <div class="form-group">
                    <label>@lang('Name')</label>
                    <input type="text" class="form-control" name="name" required value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label>@lang('Username')</label>
                    <input type="text" class="form-control" name="username" required value="{{ old('username') }}">
                </div>
                <div class="form-group">
                    <label>@lang('Email')</label>
                    <input type="email" class="form-control" name="email" required value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label>@lang('Password')</label>
                    <input type="password" class="form-control" name="password" required min="6">
                </div>
                <div class="form-group">
                    <label>@lang('Role')</label>
                    <select name="roles[]" class="form-control select2 admin-role" multiple>
                        <option value="" disabled>@lang('Select One')</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">
                                {{ __($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>


    <x-confirmation-modal />
@endsection


@push('script')
    <script>
        "use strict";
        (function($) {
            const $modal = $('#modal');
            const $form = $modal.find('form');

            $('.add-btn').on('click', function() {
                const action = "{{ route('admin.store') }}"
                $modal.find('.modal-title').text("@lang('Add Admin')");
                $modal.find('input[name=password]').attr('required', true).parent().removeClass('d-none');
                $form.trigger('reset');
                $form.attr('action', action);
                $modal.find('.admin-role').val([]);
                select2Initialize();
                $modal.modal('show');
            });

            $('.edit-btn').on('click', function() {
                const action = "{{ route('admin.update', ':id') }}";
                const admin = $(this).data('admin');
                const roleId = admin.roles.map((role) => role.id);

                $modal.find('.modal-title').text("@lang('Edit Admin')");
                $modal.find('input[name=name]').val(admin.name);
                $modal.find('input[name=username]').val(admin.username);
                $modal.find('input[name=email]').val(admin.email);
                $modal.find('.admin-role').val(roleId);
                $modal.find('input[name=password]').attr('required', false).parent().addClass('d-none');
                $form.attr('action', action.replace(':id', admin.id));
                select2Initialize();
                $modal.modal('show');
            });

            function select2Initialize() {
                $.each($('.select2'), function() {
                    $(this)
                        .wrap(`<div class="position-relative"></div>`)
                        .select2({
                            dropdownParent: $(this).parent(),
                        });
                    multiple: true
                });
            }
        })(jQuery);
    </script>
@endpush
@push('breadcrumb-plugins')
    <x-admin.permission_check permission="add admin">
        <x-admin.ui.btn.add tag="btn" />
    </x-admin.permission_check>
@endpush
