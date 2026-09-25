@extends('admin.layouts.app')
@section('panel')
    <form action="{{ route('admin.setting.notification.template.update', ['sms', $template->id]) }}" method="post">
        @csrf
        <x-admin.ui.card>
            <x-admin.ui.card.header class="py-3 d-flex justify-content-between">
                <h4 class="card-title">@lang('SMS Template')</h4>
                <div class="form-check form-switch form--switch pl-0 form-switch-success">
                    <input class="form-check-input" name="sms_status" type="checkbox" role="switch"
                        @checked($template->sms_status)>
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
                                    <label>@lang('SMS Sent From')</label>
                                    <input type="text" class="form-control" name="sms_sent_from"
                                        value="{{ $template->sms_sent_from }}">
                                    <small class="text-primary"><i><i class="las la-info-circle"></i>
                                            @lang('Make the field empty if you want to use global template\'s name as sms sent from name.')</i></small>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Message')</label>
                                    <textarea name="sms_body" rows="10" class="form-control" placeholder="@lang('Your message using short-codes')" required>{{ $template->sms_body }}</textarea>
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
