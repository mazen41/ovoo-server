@extends('admin.layouts.app')
@section('panel')
    @php
        $firebasConfig = gs('firebase_config');
    @endphp
    <div class="row responsive-row">
        <div class="col-12">
            <div class="alert alert--info d-flex" role="alert">
                <div class="alert__icon">
                    <i class="las la-info"></i>
                </div>
                <div class="alert__content">
                    <p>@lang('To send push notifications via Firebase, your system must have an SSL certificate in place for secure communication. Ensure your server is SSL-certified to enable seamless and secure delivery of notifications.')</p>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <x-admin.ui.card>
                <x-admin.ui.card.body>
                    <form method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('API Key') </label>
                                    <input type="text" class="form-control" placeholder="@lang('API Key')"
                                        name="apiKey" value="{{ @$firebasConfig->apiKey }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Auth Domain') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Auth Domain')"
                                        name="authDomain" value="{{ @$firebasConfig->authDomain }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Project Id') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Project Id')"
                                        name="projectId" value="{{ @$firebasConfig->projectId }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Storage Bucket') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Storage Bucket')"
                                        name="storageBucket" value="{{ @$firebasConfig->storageBucket }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Messaging Sender Id') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Messaging Sender Id')"
                                        name="messagingSenderId" value="{{ @$firebasConfig->messagingSenderId }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('App Id') </label>
                                    <input type="text" class="form-control" placeholder="@lang('App Id')"
                                        name="appId" value="{{ @$firebasConfig->appId }}" required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>@lang('Measurement Id') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Measurement Id')"
                                        name="measurementId" value="{{ @$firebasConfig->measurementId }}" required>
                                </div>
                            </div>
                        </div>
                        <x-admin.ui.btn.submit />
                    </form>
                </x-admin.ui.card.body>
            </x-admin.ui.card>

        </div>
    </div>

    <x-admin.ui.modal id="pushNotifyModal" class="modal-xl">
        <x-admin.ui.modal.header>
            <h1 class="modal-title">@lang('Firebase Setup')</h1>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="steps-tab" data-bs-toggle="tab" data-bs-target="#steps"
                        type="button" role="tab" aria-controls="steps" aria-selected="true">@lang('Steps')</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="configs-tab" data-bs-toggle="tab" data-bs-target="#configs" type="button"
                        role="tab" aria-controls="configs" aria-selected="false">@lang('Configs')</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="steps" role="tabpanel" aria-labelledby="steps-tab">
                    <ul class="  list-group list-group-flush py-4 border border--success border-3 rounded">
                        <li class=" list-group-item">
                            <span class="me-2">
                                <i class="fas fa-check-circle text--success"></i>
                            </span>
                            <span class="fs-14">
                                @lang('Go to your Firebase account and select') <span class="text--primary">"@lang('Go to console')</span>"
                                @lang('in the upper-right corner of the page.')
                            </span>
                        </li>
                        <li class=" list-group-item">
                            <span class="me-2">
                                <i class="fas fa-check-circle text--success"></i>
                            </span>
                            <span class="fs-14">
                                @lang('Click on the') <span class="text--primary">"@lang('Add Project')</span>"
                                @lang('button.')
                            </span>
                        </li>
                        <li class=" list-group-item">
                            <span class="me-2">
                                <i class="fas fa-check-circle text--success"></i>
                            </span>
                            <span class="fs-14">
                                @lang('Enter the project name and click on the') <span class="text--primary">"@lang('Continue')</span>"
                                @lang('button.')
                            </span>
                        </li>
                        <li class=" list-group-item">
                            <span class="me-2">
                                <i class="fas fa-check-circle text--success"></i>
                            </span>
                            <span class="fs-14">
                                @lang('Enable Google Analytics and click on the') <span class="text--primary">"@lang('Continue')</span>"
                                @lang('button.')
                            </span>
                        </li>
                        <li class=" list-group-item">
                            <span class="me-2">
                                <i class="fas fa-check-circle text--success"></i>
                            </span>
                            <span class="fs-14">
                                @lang('Choose the default account for the Google Analytics account and click on the') <span class="text--primary">"@lang('Create Project')</span>"
                                @lang('button.')
                            </span>
                        </li>
                        <li class=" list-group-item">
                            <span class="me-2">
                                <i class="fas fa-check-circle text--success"></i>
                            </span>
                            <span class="fs-14">
                                @lang('Within your Firebase project, select the gear next to Project Overview and choose Project settings.')
                            </span>
                        </li>
                        <li class=" list-group-item">
                            <span class="me-2">
                                <i class="fas fa-check-circle text--success"></i>
                            </span>
                            <span class="fs-14">
                                @lang('Next, set up a web app under the General section of your project settings.')
                            </span>
                        </li>
                        <li class=" list-group-item">
                            <span class="me-2">
                                <i class="fas fa-check-circle text--success"></i>
                            </span>
                            <span class="fs-14">
                                @lang('Go to the Service accounts tab and generate a new private key.')
                            </span>
                        </li>
                        <li class=" list-group-item">
                            <span class="me-2">
                                <i class="fas fa-check-circle text--success"></i>
                            </span>
                            <span class="fs-14">
                                @lang('A JSON file will be downloaded. Upload the downloaded file here.')
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="tab-pane fade text-center py-4" id="configs" role="tabpanel"
                    aria-labelledby="configs-tab">
                    <img src="{{ getImage('assets/images/firebase/' . 'configs.png') }}" alt="Firebase Config"
                        class="border rounded border--success border-3 p-3">
                </div>
            </div>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>

    <x-admin.ui.modal id="pushConfigJson">
        <x-admin.ui.modal.header>
            <h1 class="modal-title">@lang('Upload Push Notification Configuration File')</h1>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form method="POST" action="{{ route('admin.setting.notification.push.upload') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="mt-2">@lang('File')</label>
                    <input type="file" class="form-control" name="file" accept=".json" required>
                    <small class="mt-3 text-muted">@lang('Supported Files: .json')</small>
                </div>
                <div class="form-group">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>
@endsection

@push('breadcrumb-plugins')
    <div class=" d-flex gap-2 flex-wrap">
        <button type="button" data-bs-target="#pushNotifyModal" data-bs-toggle="modal" class="btn btn--info flex-fill">
            <i class="las la-question"></i> @lang('Help')
        </button>
        <button class="btn btn--primary updateBtn flex-fill " data-bs-toggle="modal" data-bs-target="#pushConfigJson"
            type="button"><i class="las la-upload"></i> @lang('Upload Config File')
        </button>
        <a href="{{ route('admin.setting.notification.push.download') }}"
            class="btn btn--info updateBtn  flex-fill  @if (!$fileExists) disabled @endif"
            @disabled(!$fileExists)>
            <i class="las la-download"></i> @lang('Download File')
        </a>
    </div>
@endpush
