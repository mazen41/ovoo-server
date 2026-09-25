@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-md-12">
            <x-admin.ui.card>
                <x-admin.ui.card.body class="py-3">
                    <h4 class="text--info fs-18 mb-1">@lang('Important Info')</h4>
                    <p>
                        @lang('The SEO settings for this page are optional. If you choose not to configure them, the global SEO settings will apply. You can adjust the global settings in') <a href="{{ route('admin.seo') }}">@lang('System Setting > SEO Configuration').</a>
                    </p>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
        <div class="col-lg-12">
            <x-admin.ui.card>
                <x-admin.ui.card.body>
                    <form method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label>@lang('SEO Image')</label>
                                    <x-image-uploader class="w-100" :imagePath="frontendImage(
                                        $key,
                                        @$data->seo_content->image,
                                        getFileSize('seo'),
                                        true,
                                    )" :size="getFileSize('seo')"
                                        :required="false" />
                                </div>
                            </div>

                            <div class="col-xl-8 mt-xl-0 mt-4">
                                <div class="form-group select2-parent position-relative">
                                    <label>@lang('Meta Keywords')</label>
                                    <small class="ms-2 mt-2  ">@lang('Separate multiple keywords by') <code>,</code>(@lang('comma'))
                                        @lang('or') <code>@lang('enter')</code> @lang('key')</small>
                                    <select name="keywords[]" class="form-control select2-auto-tokenize"
                                        multiple="multiple">
                                        @if (@$data->seo_content->keywords)
                                            @foreach (@$data->seo_content->keywords as $option)
                                                <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Meta Description')</label>
                                    <textarea name="description" rows="3" class="form-control">{{ @$data->seo_content->description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Social Title')</label>
                                    <input type="text" class="form-control" name="social_title"
                                        value="{{ @$data->seo_content->social_title }}" />
                                </div>
                                <div class="form-group">
                                    <label>@lang('Social Description')</label>
                                    <textarea name="social_description" rows="3" class="form-control">{{ @$data->seo_content->social_description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <x-admin.ui.btn.submit />
                                </div>
                            </div>
                        </div>
                    </form>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back_btn route="{{ route('admin.frontend.sections', $key) }}" />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.select2-auto-tokenize').select2({
                dropdownParent: $('.select2-parent'),
                tags: true,
                tokenSeparators: [',']
            });
        })(jQuery);
    </script>
@endpush
