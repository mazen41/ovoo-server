@extends('admin.layouts.app')
@section('panel')
    <form action="{{ route('admin.frontend.sections.content', 'seo') }}" method="POST" enctype="multipart/form-data">
        <div class="row justify-content-center">
            @csrf
            <div class="col-lg-3">
                <div class="form-group">
                    <div class="bg--white rounded p-3">
                        <label class="form-label">@lang('SEO Image')</label>
                        <x-image-uploader :imagePath="getImage(getFilePath('seo') . '/' . @$seo->data_values->image, getFileSize('seo'))" :size="getFileSize('seo')" :required="false" name="image_input" />
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <x-admin.ui.card>
                    <x-admin.ui.card.header>
                        <h4 class="card-title">@lang('Update SEO Configuration')</h4>
                        <small>@lang('Ensure all SEO configurations are updated properly for optimal search engine visibility')</small>
                    </x-admin.ui.card.header>
                    <x-admin.ui.card.body>
                        <input type="hidden" name="type" value="data">
                        <input type="hidden" name="seo_image" value="1">
                        <div class="form-group">
                            <label>@lang('Social Title')</label>
                            <input type="text" class="form-control" name="social_title"
                                value="{{ @$seo->data_values->social_title }}" required>
                        </div>
                        <div class="form-group">
                            <labelm class="mb-0">@lang('Meta Keywords')</labelm>
                            <span class="d-block text-muted fs-13 mb-2">@lang('Separate multiple keywords by') <code>,</code>(@lang('comma'))
                                @lang('or') <code>@lang('enter')</code> @lang('key')</span>
                            <select name="keywords[]" class="form-control select2-auto-tokenize select2-js-input"
                                multiple="multiple" required>
                                @if (@$seo->data_values->keywords)
                                    @foreach ($seo->data_values->keywords as $option)
                                        <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Meta Description')</label>
                            <textarea name="description" rows="3" class="form-control" required>{{ @$seo->data_values->description }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>@lang('Social Description')</label>
                            <textarea name="social_description" rows="3" class="form-control" required>{{ @$seo->data_values->social_description }}</textarea>
                        </div>
                        <x-admin.ui.btn.submit />
                    </x-admin.ui.card.body>
                </x-admin.ui.card>
            </div>
        </div>
    </form>
@endsection

@push('style')
    <style>
        .image-upload__placeholder {
            border: unset;
            box-shadow: unset;
        }

        .image-upload__icon {
            bottom: 5px;
        }
    </style>
@endpush
