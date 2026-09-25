@extends('admin.layouts.app')
@section('panel')
    @csrf
    <div class="row responsive-row justify-content-start">
        <div class="col-12">
            <div class="alert alert--info d-flex" role="alert">
                <div class="alert__icon">
                    <i class="las la-info"></i>
                </div>
                <div class="alert__content">
                    <p>
                        @lang('If the logo and favicon do not update after changes are made on this page, please clear your browser cache. Since we retain the same filename after the update, the old image may still appear due to caching. Typically, clearing the browser cache resolves this issue. However, if the old logo or favicon persists, it could be due to server-level or network-level caching, which may also need to be cleared.')
                        <a class="alert__link fw-600" href="{{ route('admin.system.optimize.clear') }}">@lang('Clear cache')</a>
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-12">
            <x-admin.ui.card>
                <x-admin.ui.card.body>
                    <form method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row responsive-row">
                            <div class="col-xl-6  col-xxl-4 col-md-6">
                                <label class="form-label fw-bold">@lang('Logo Light')</label>
                                <x-image-uploader name="logo" :imagePath="siteLogo() . '?' . time()" :size="false" :required="false" theme="light" />
                            </div>
                            <div class="col-xl-6  col-xxl-4 col-md-6">
                                <label class="form-label fw-bold">@lang('Logo Dark')</label>
                                <x-image-uploader name="logo_dark" id="logo_dark" :imagePath="siteLogo('dark') . '?' . time()" :size="false"
                                    :required="false" theme="dark" />
                            </div>
                            <div class="col-xl-6  col-xxl-4 col-md-6">
                                <label class="form-label fw-bold">@lang('Favicon')</label>
                                <x-image-uploader name="favicon" id="favicon" :imagePath="siteFavicon() . '?' . time()" :size="false"
                                    :required="false" />
                            </div>
                        </div>
                        <x-admin.ui.btn.submit />
                    </form>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection
