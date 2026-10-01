@extends('admin.layouts.app')
@section('panel')
    <form method="POST">
        @csrf
        <x-admin.ui.card>
            <x-admin.ui.card.body>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="mb-1">@lang('S3 Cloud Storage')</h5>
                        <p class="mb-0 text-muted">@lang('Configure S3-compatible cloud storage for media files.')</p>
                    </div>
                    <div class="form-check form-switch form--switch pl-0 form-switch-success">
                        <input class="form-check-input" type="checkbox" role="switch" id="s3_status"
                            name="status" value="1" @checked(gs('s3_config')->status ?? false)>
                        <label class="form-check-label" for="s3_status">@lang('Enable S3 Storage')</label>
                    </div>
                </div>
            </x-admin.ui.card.body>
        </x-admin.ui.card>

        <div class="alert alert-info my-4">
            <h6 class="mb-2">@lang('When enabled, these features will use S3 cloud storage:')</h6>
            <ul class="mb-0 ps-3">
                <li>@lang('WhatsApp conversation media (images, videos, documents)')</li>
                <li>@lang('Support ticket attachments')</li>
                <li>@lang('Flow builder media')</li>
                <li>@lang('CTA URL header images')</li>
            </ul>
        </div>

        <x-admin.ui.card>
            <x-admin.ui.card.body>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label>@lang('Access Key ID')</label>
                        <input type="text" class="form-control" placeholder="@lang('Access Key ID')" name="key"
                            value="{{ gs('s3_config')->key ?? '' }}" required>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>@lang('Secret Access Key')</label>
                        <input type="text" class="form-control" placeholder="@lang('Secret Access Key')" name="secret"
                            value="{{ gs('s3_config')->secret ?? '' }}" required>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>@lang('Default Region')</label>
                        <input type="text" class="form-control" placeholder="@lang('e.g. us-east-1')" name="region"
                            value="{{ gs('s3_config')->region ?? '' }}" required>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>@lang('Bucket Name')</label>
                        <input type="text" class="form-control" placeholder="@lang('Bucket name')" name="bucket"
                            value="{{ gs('s3_config')->bucket ?? '' }}" required>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>@lang('Bucket URL')</label>
                        <input type="text" class="form-control" placeholder="@lang('https://your-bucket.s3.region.amazonaws.com')" name="url"
                            value="{{ gs('s3_config')->url ?? '' }}">
                        <small class="text-muted">@lang('Optional. The base URL for publicly accessible files.')</small>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>@lang('Endpoint')</label>
                        <input type="text" class="form-control" placeholder="@lang('https://s3.custom-endpoint.com')" name="endpoint"
                            value="{{ gs('s3_config')->endpoint ?? '' }}">
                        <small class="text-muted">@lang('Optional. For DigitalOcean Spaces, MinIO, etc.')</small>
                    </div>
                    <div class="form-group col-sm-6">
                        <div class="form-check form-switch form--switch pl-0 form-switch-success">
                            <input class="form-check-input" type="checkbox" role="switch" id="use_path_style_endpoint"
                                name="use_path_style_endpoint" value="1"
                                @checked(gs('s3_config')->use_path_style_endpoint ?? false)>
                            <label class="form-check-label" for="use_path_style_endpoint">@lang('Use Path Style Endpoint')</label>
                        </div>
                        <small class="text-muted">@lang('Enable for MinIO, DigitalOcean Spaces, or any S3-compatible service.')</small>
                    </div>
                    <div class="col-12 mt-3">
                        <x-admin.ui.btn.submit />
                    </div>
                </div>
            </x-admin.ui.card.body>
        </x-admin.ui.card>
    </form>

    <div class="row mt-4">
        <div class="col-12">
            <x-admin.ui.card>
                <x-admin.ui.card.body>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">@lang('Test S3 Connection')</h5>
                            <p class="mb-0 text-muted">@lang('Verify that the credentials are correct and the bucket is accessible.')</p>
                        </div>
                        <button type="button" class="btn btn-outline--success" id="testS3Connection">
                            <i class="las la-cloud-upload-alt"></i> @lang('Test Connection')
                        </button>
                    </div>
                    <div id="s3TestResult" class="mt-3 d-none"></div>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="https://docs.ovosolution.com/ovowpp/" target="_blank" class="btn btn-outline--success">
        <i class="las la-info"></i> @lang('Documentations')
    </a>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('#testS3Connection').on('click', function() {
                const $btn = $(this);
                const $result = $('#s3TestResult');
                const originalText = $btn.html();

                $btn.prop('disabled', true).html(
                    '<i class="las la-spinner la-spin"></i> @lang("Testing...")'
                );
                $result.addClass('d-none');

                $.ajax({
                    url: "{{ route('admin.setting.s3.test') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: $('#s3_status').is(':checked') ? 1 : 0,
                        key: $('input[name="key"]').val(),
                        secret: $('input[name="secret"]').val(),
                        region: $('input[name="region"]').val(),
                        bucket: $('input[name="bucket"]').val(),
                        url: $('input[name="url"]').val(),
                        endpoint: $('input[name="endpoint"]').val(),
                        use_path_style_endpoint: $('input[name="use_path_style_endpoint"]').is(':checked') ? 1 : 0,
                    },
                    success: function(resp) {
                        const cls = resp.success ? 'success' : 'error';
                        $result.removeClass('d-none alert alert-success alert-danger')
                            .addClass('alert alert-' + cls)
                            .html(resp.message);
                    },
                    error: function() {
                        $result.removeClass('d-none alert alert-success alert-danger')
                            .addClass('alert alert-danger')
                            .html('@lang("Failed to test connection. Please try again.")');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html(originalText);
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
