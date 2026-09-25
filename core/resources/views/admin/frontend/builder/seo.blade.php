@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-12">
            <div class="alert alert--info d-flex" role="alert">
                <div class="alert__icon">
                    <i class="las la-info"></i>
                </div>
                <div class="alert__content">
                    <p>
                        @lang('The SEO settings for this page are optional. If you choose not to configure them, the global SEO settings will apply. You can adjust the global settings in') <a href="{{ route('admin.seo') }}">@lang('System Setting > SEO Configuration').</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <x-admin.ui.card>
                <x-admin.ui.card.body>
                    <form action method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="data">
                        <input type="hidden" name="seo_image" value="1">
                        <div class="row gy-3">
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label>@lang('SEO Image')</label>
                                    <x-image-uploader class="w-100" :imagePath="getImage(
                                        getFilePath('seo') . '/' . @$page->seo_content->image,
                                        getFileSize('seo'),
                                    )" :size="getFileSize('seo')" :required="false"
                                        name="image" />

                                </div>
                            </div>

                            <div class="col-xl-8">
                                <div class="form-group">
                                    <label>@lang('Social Title')</label>
                                    <input type="text" class="form-control" name="social_title"
                                        value="{{ @$page->seo_content->social_title }}" />
                                </div>
                                <div class="form-group">
                                    <label>@lang('Meta Keywords')</label>
                                    <small class="ms-2 mt-2  ">@lang('Separate multiple keywords by') <code>,</code>(@lang('comma'))
                                        @lang('or') <code>@lang('enter')</code> @lang('key')</small>
                                    <select name="keywords[]" class="form-control select2-auto-tokenize select2-js-input"
                                        multiple="multiple">
                                        @if (@$page->seo_content->keywords)
                                            @foreach ($page->seo_content->keywords as $option)
                                                <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Meta Description')</label>
                                    <textarea name="description" rows="3" class="form-control">{{ @$page->seo_content->description }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Social Description')</label>
                                    <textarea name="social_description" rows="3" class="form-control">{{ @$page->seo_content->social_description }}</textarea>
                                </div>
                                <x-admin.ui.btn.submit />
                            </div>
                        </div>
                    </form>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <x-back_btn route="{{ route('admin.frontend.manage.pages') }}" />
@endpush
