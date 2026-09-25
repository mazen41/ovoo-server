@extends('admin.layouts.app')
@section('panel')
    <div class="row  justify-content-center">
        <div class="col-lg-6 col-sm-12">
            <x-admin.ui.card>
                <x-admin.ui.card.header>
                    <h4 class="card-title">@lang('Profile Information')</h4>
                    <small>@lang('View and manage your profile details including name, username, and email.')</small>
                </x-admin.ui.card.header>
                <x-admin.ui.card.body>
                    <div class="text-center m-auto">
                        <div class="admin-profile-image">
                            <img src="{{ $admin->image_src }}">
                        </div>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                            <span class="text-muted"> <i class="las la-user"></i> @lang('Name')</span>
                            <span>{{ __($admin->name) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                            <span class="text-muted"><i class="las la-user"></i> @lang('Username')</span>
                            <span>{{ __($admin->username) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                            <span class="text-muted"> <i class="las la-envelope"></i> @lang('Email')</span>
                            <span>{{ $admin->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                            <span class=" text-muted"><i class="las la-clock"></i> @lang('Joined at')</span>
                            <span class="text-end">
                                <span class="d-block">{{ showDateTime($admin->created_at) }}</span>
                                <span class="text--info">{{ diffForHumans($admin->created_at) }}</span>
                            </span>
                        </li>
                    </ul>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>

    <x-admin.ui.modal id="profileModal">
        <x-admin.ui.modal.header>
            <h1 class="modal-title">@lang('Update Profile')</h1>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group ">
                    <label>@lang('Name')</label>
                    <input class="form-control" type="text" name="name" value="{{ $admin->name }}" required>
                </div>
                <div class="form-group">
                    <label>@lang('Email')</label>
                    <input class="form-control" type="email" name="email" value="{{ $admin->email }}" required>
                </div>
                <div class="form-group">
                    <label>@lang('Image')</label>
                    <x-image-uploader :size="getFileSize('adminProfile')" name="image" :imagePath="$admin->image_src" :required="false" />
                </div>
                <div class="form-group">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap gap-3 flex-fill">
        <button type="button" class="btn btn-outline--primary flex-fill" data-bs-toggle="modal"
            data-bs-target="#profileModal">
            <i class="la la-pencil"></i> @lang('Edit')
        </button>
        <x-admin.permission_check permission="view dashboard">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline--dark flex-fill">
                <i class="las la-tachometer-alt"></i> @lang('Dashboard')
            </a>
        </x-admin.permission_check>
    </div>
@endpush

@push('style')
    <style>
        .admin-profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto;
        }

        .admin-profile-image img {
            margin: 0 auto;
            border-radius: 100%;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
@endpush
