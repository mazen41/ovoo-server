@extends('admin.layouts.app')
@section('panel')
    <x-admin.ui.card>
        <x-admin.ui.card.body>
            <div class="row responsive-row">
                @include('admin.notification.global_template_nav')
                @include('admin.notification.global_shortcodes')
            </div>
            <form action="{{ route('admin.setting.notification.global.push.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>@lang('Notification Title') </label>
                    <input class="form-control" placeholder="@lang('Notification Title')" name="push_title"
                        value="{{ gs('push_title') }}" required>
                </div>
                <div class="form-group">
                    <label>@lang('Push Notification Body') </label>
                    <textarea class="form-control" rows="4" placeholder="@lang('Push Notification Body')" name="push_template" required>{{ gs('push_template') }}</textarea>
                </div>
                <x-admin.ui.btn.submit />
            </form>
        </x-admin.ui.card.body>
    </x-admin.ui.card>
@endsection
