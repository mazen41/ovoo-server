@extends('admin.layouts.app')
@section('panel')
    <form method="POST">
        <x-admin.ui.card>
            <x-admin.ui.card.body>
                @csrf
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label> @lang('Pusher App ID')</label>
                        <input type="text" class="form-control" placeholder="@lang('Pusher App ID')" name="pusher_app_id"
                            value="{{ config('app.PUSHER_APP_ID') }}" required>
                    </div>
                    <div class="form-group col-sm-6">
                        <label> @lang('Pusher App Key')</label>
                        <input type="text" class="form-control" placeholder="@lang('Pusher App Key')" name="pusher_app_key"
                            value="{{ config('app.PUSHER_APP_KEY') }}" required>
                    </div>
                    <div class="form-group col-sm-6">
                        <label> @lang('Pusher App Secret')</label>
                        <input type="text" class="form-control" placeholder="@lang('Pusher App Secret')" name="pusher_app_secret"
                            value="{{ config('app.PUSHER_APP_SECRET') }}" required>
                    </div>
                    <div class="form-group col-sm-6">
                        <label> @lang('Pusher App Cluster')</label>
                        <input type="text" class="form-control" placeholder="@lang('Pusher App Cluster')" name="pusher_app_cluster"
                            value="{{ config('app.PUSHER_APP_CLUSTER') }}" required>
                    </div>
                    <div class="col-12">
                        <x-admin.ui.btn.submit />
                    </div>
                </div>

            </x-admin.ui.card.body>
        </x-admin.ui.card>
    </form>
@endsection

@push('breadcrumb-plugins')
    <a href="https://preview.ovosolution.com/ovowpp/documentation/#pusher-setting" target="_blank" class="btn btn-outline--success">
        <i class="las la-info"></i> @lang('Documentations')
    </a>
@endpush
