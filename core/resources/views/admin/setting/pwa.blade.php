@extends('admin.layouts.app')
@section('panel')
    <div class="row responsive-row">
        <div class="col-12">
            <div class="alert alert--info d-flex" role="alert">
                <div class="alert__icon">
                    <i class="las la-info"></i>
                </div>
                <div class="alert__content">
                    <p class="d-block mb-3">
                        @lang('Please upload your PWA icons below. The 192x192 image will be used as the smaller icon, and the 512x512 image will serve as the larger icon for app installation prompts and home screen displays.')
                    </p>
                    <p>
                        @lang('If the image do not update after changes are made on this page, please clear your browser cache. Since we retain the same filename after the update, the old image may still appear due to caching. Typically, clearing the browser cache resolves this issue. However, if the old logo or favicon persists, it could be due to server-level or network-level caching, which may also need to be cleared.')
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
                        <div class="row responsive-row justify-content-center">
                            <div class="col-xl-6  col-xxl-4 col-md-6">
                                <label class="form-label fw-bold">@lang('PWA Small Icon')</label>
                                <x-image-uploader size="192x192" accept=".png" name="pwa_small_icon"
                                    imagePath="{{ getImage('assets/images/logo_icon/pwa_small_icon.png', '192x192') }}"
                                    :required="false" />
                            </div>
                            <div class="col-xl-6  col-xxl-4 col-md-6">
                                <label class="form-label fw-bold">@lang('PWA Large Icon')</label>
                                <x-image-uploader accept=".png" name="pwa_large_icon" size="512x512" id="logo_dark"
                                    imagePath="{{ getImage('assets/images/logo_icon/pwa_large_icon.png', '512x512') }}"
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
