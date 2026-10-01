@extends('admin.layouts.app')
@section('panel')
    <div class="card">
        <div class="card-body">
            <div class="row responsive-row">
                @include('admin.notification.global_template_nav')
                @include('admin.notification.global_shortcodes')

            </div>
            <form action="{{ route('admin.setting.notification.global.email.update') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Email Sent From - Name') </label>
                            <input type="text" class="form-control " placeholder="@lang('Email address')" name="email_from_name"
                                value="{{ gs('email_from_name') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Email Sent From - Email') </label>
                            <input type="text" class="form-control " placeholder="@lang('Email address')" name="email_from"
                                value="{{ gs('email_from') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Email Body') </label>
                            <textarea name="email_template" rows="19" class="form-control emailTemplateEditor h-100" id="htmlInput"
                                placeholder="@lang('Your email template')">{{ gs('email_template') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="previewContainer">
                            <label>&nbsp;</label>
                            <iframe id="iframePreview"></iframe>
                        </div>
                    </div>
                </div>
                <x-admin.ui.btn.submit />
            </form>
        </div>
    </div>
@endsection
@push('style')
    <style>
        #iframePreview {
            width: 100%;
            height: 480px;
            border: none;
        }
    </style>
@endpush
@push('script')
    <script>
        var iframe = document.getElementById('iframePreview');
        $(".emailTemplateEditor").on('input', function() {
            var htmlContent = $(this).val();
            iframe.src = 'data:text/html;charset=utf-8,' + encodeURIComponent(htmlContent);
        }).trigger('input');
    </script>
@endpush
