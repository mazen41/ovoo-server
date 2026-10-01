@extends('admin.layouts.app')
@section('panel')
    <x-admin.ui.card>
        <x-admin.ui.card.body>
            <div class="row responsive-row">
                @include('admin.notification.global_template_nav')
                @include('admin.notification.global_shortcodes')
            </div>
            <form action="{{ route('admin.setting.notification.global.sms.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>@lang('SMS Sent From') </label>
                    <input class="form-control" placeholder="@lang('SMS Sent From')" name="sms_from" value="{{ gs('sms_from') }}"
                        required>
                </div>
                <div class="form-group">
                    <label>@lang('SMS Body') </label>
                    <textarea class="form-control" rows="4" placeholder="@lang('SMS Body')" name="sms_template" required>{{ gs('sms_template') }}</textarea>
                </div>
                <x-admin.ui.btn.submit />
            </form>
        </x-admin.ui.card.body>
    </x-admin.ui.card>
@endsection
