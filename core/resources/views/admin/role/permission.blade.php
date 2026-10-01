@extends('admin.layouts.app')
@section('panel')
    @php
        $existingPermissionIds = (clone $existingPermissions)->pluck('id')->toArray();
    @endphp
    <div class="row">
        <div class="col-12">
            <x-admin.ui.card>
                <x-admin.ui.card.body>
                    <form method="POST" action="{{ route('admin.role.permission.update', $role->id) }}">
                        @csrf
                        <div class="row  gy-4 all-permission-list responsive-row">
                            <div class="col-12">
                                <div class=" d-flex justify-content-between align-items-center">
                                    <p class="mb-0 fs-20">@lang('Permission List')</p>
                                    <div class="form-check form-switch form--switch pl-0 form-switch-success">
                                        <input class="form-check-input check-all-permission" type="checkbox" role="switch"
                                            @checked($permissions->count() == $existingPermissions->count())>
                                    </div>
                                </div>
                            </div>
                            @foreach ($permissions->groupBy('group_name') as $k => $permission)
                                @php
                                    $groupExistingpermissionCount = $existingPermissions
                                        ->where('group_name', $k)
                                        ->count();
                                @endphp
                                <div class="col-xl-4 col-xxl-3 col-md-4 col-sm-6">
                                    <div
                                        class="border border-1 d-flex  p-3 h-100 flex-column rounded group-permission-list">
                                        <div class="py-2">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="fs-18 mb-0">{{ ucwords(__(keyToTitle($k))) }}</p>
                                                <div class="form-check form-switch form--switch pl-0 form-switch-success">
                                                    <input class="form-check-input check-group-permission" type="checkbox"
                                                        role="switch" @checked($groupExistingpermissionCount == $permission->count())>
                                                </div>
                                            </div>
                                            <hr class="mb-0 mt-2">
                                        </div>
                                        <div class="permission-list mt-2">
                                            @foreach ($permission as $singlePermission)
                                                <div class="d-flex gap-2 align-items-center">
                                                    <div
                                                        class="form-check form-switch form--switch pl-0 form-switch-success">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                            id="permission-{{ $singlePermission->id }}"
                                                            value="{{ $singlePermission->id }}" name="permissions[]"
                                                            @checked(in_array($singlePermission->id, $existingPermissionIds))>
                                                    </div>
                                                    <label class="form-check-label"
                                                        for="permission-{{ $singlePermission->id }}">
                                                        {{ ucwords(__($singlePermission->name)) }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-12">
                                <x-admin.ui.btn.submit />
                            </div>
                        </div>
                    </form>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {

            $(".check-all-permission").on('change', function(e) {
                checkUncheckAllPermissions($(this).is(":checked"), $('.all-permission-list'));
            });

            $(".check-group-permission").on('change', function(e) {
                checkUncheckAllPermissions($(this).is(":checked"), $(this).closest(".group-permission-list"));
            });

            function checkUncheckAllPermissions(isChecked, $parent) {
                $parent.find('[type="checkbox"]').attr('checked', isChecked);
            };
        })(jQuery);
    </script>
@endpush

@push('breadcrumb-plugins')
    <x-back_btn route="{{ route('admin.role.list') }}" />
@endpush

@push('style')
    <style>
        .form--switch .form-check-input {
            padding: 9px !important;
            width: 40px;
            height: 15px;
        }
    </style>
@endpush
