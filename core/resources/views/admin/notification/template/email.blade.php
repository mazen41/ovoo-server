@extends('admin.layouts.app')
@section('panel')
    <form action="{{ route('admin.setting.notification.template.update', ['email', $template->id]) }}" method="post">
        @csrf
        <x-admin.ui.card>
            <x-admin.ui.card.header class="py-3 d-flex justify-content-between">
                <h4 class="card-title">@lang('Email Template')</h4>
                <div class="form-check form-switch form--switch pl-0 form-switch-success">
                    <input class="form-check-input" name="email_status" type="checkbox" role="switch"
                        @checked($template->email_status)>
                </div>
            </x-admin.ui.card.header>
            <x-admin.ui.card.body>
                <div class="row gy-4">
                    @include('admin.notification.template.nav')
                    @include('admin.notification.template.shortcodes')
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Subject')</label>
                                    <input type="text" class="form-control" placeholder="@lang('Email subject')"
                                        name="subject" value="{{ $template->subject }}" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Email Sent From - Name')</label>
                                    <input type="text" class="form-control" name="email_sent_from_name"
                                        value="{{ $template->email_sent_from_name }}">
                                    <small class="text-primary"><i><i class="las la-info-circle"></i>
                                            @lang('Make the field empty if you want to use global template\'s name as email sent from name.')</i></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Email Sent From - Email')</label>
                                    <input type="text" class="form-control" name="email_sent_from_address"
                                        value="{{ $template->email_sent_from_address }}">
                                    <small class="text-primary"><i><i class="las la-info-circle"></i>
                                            @lang('Make the field empty if you want to use global template\'s email as email sent from.')</i></small>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Message') <span class="text--danger">*</span></label>
                                    <textarea name="email_body" rows="10" class="form-control editor" placeholder="@lang('Your message using short-codes')">{{ $template->email_body }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <x-admin.ui.btn.submit />
            </x-admin.ui.card.body>
        </x-admin.ui.card>
    </form>
@endsection

@push('breadcrumb-plugins')
    <x-back_btn route="{{ route('admin.setting.notification.templates') }}" />
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/summernote-lite.min.js') }}"></script>
@endpush
@push('style-lib')
    <link href="{{ asset('assets/global/css/summernote-lite.min.css') }}" rel="stylesheet">
@endpush
