@extends('admin.layouts.app')
@section('panel')
    <div class="row responsive-row">
        @foreach ($languages as $language)
            <div class="col-xxl-4 col-sm-12 col-md-6">
                <x-admin.ui.card class="h-100">
                    <x-admin.ui.card.body>
                        <div class="flex-thumb-wrapper mb-3">
                            <div class="thumb">
                                <img src="{{ $language->image_src }}" class="thumb-img">
                            </div>
                            @if ($language->is_default == Status::YES)
                                <span class="ms-2 fw-500">
                                    {{ ucfirst($language->name) }} - {{ strtolower($language->code) }}
                                    <span class="ms-2">
                                        <i class=" fas fa-check-double text--success"></i>
                                    </span>
                                </span>
                            @else
                                <span class="ms-2 ">
                                    {{ ucfirst($language->name) }} - {{ strtolower($language->code) }}
                                </span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <p>{{ __($language->info) }}</p>
                        </div>
                        <div class="btn--group">
                            <a href="{{ route('admin.language.key', $language->id) }}" class="btn  btn-outline--success">
                                <i class="la la-language"></i> @lang('Translate')
                            </a>
                            <button type="button" class="btn  btn-outline--primary editBtn"
                                data-lang='@json($language)' data-image="{{ $language->image_src }}">
                                <i class="la la-pencil"></i> @lang('Edit')
                            </button>
                            <button class="btn  btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to remove this language from this system?')"
                                data-action="{{ route('admin.language.manage.delete', $language->id) }}"
                                @disabled($language->id == 1 || $language->is_default == Status::YES)>
                                <i class="las la-trash"></i> @lang('Remove')
                            </button>
                        </div>
                    </x-admin.ui.card.body>
                </x-admin.ui.card>
            </div>
        @endforeach
    </div>

    <x-admin.ui.modal id="langModal">
        <x-admin.ui.modal.header>
            <h1 class="modal-title">@lang('Add New Language')</h1>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>@lang('Language Flag')</label>
                    <x-image-uploader :size="getFileSize('language')" class="w-100" id="imageCreate" :required="true" />
                </div>
                <div class="form-group">
                    <label>@lang('Language Name')</label>
                    <input type="text" class="form-control" value="{{ old('name') }}" name="name" required>
                </div>
                <div class="form-group">
                    <label>@lang('Language Code')</label>
                    <input type="text" class="form-control" value="{{ old('code') }}" name="code" required>
                </div>
                <div class="form-group">
                    <label>@lang('Language Info')</label>
                    <textarea name="info" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="inputName">@lang('Default Language')</label>
                    <div class="form-check form-switch form--switch pl-0  form-switch-success">
                        <input class="form-check-input" name="is_default" type="checkbox" role="switch">
                    </div>
                </div>
                <div class="form-group">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>

    <x-admin.ui.modal id="getLangModal" class="py-4">
        <x-admin.ui.modal.header>
            <h1 class="modal-title">@lang('Language Keywords')</h1>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <div class="form-group">
                <span class="bg--info p-2 rounded text-white  fs-14">
                    @lang("Here, you'll find a comprehensive list of all available language keywords. While we've made every effort to include them all, some may be missing due to variations or updates in the database. If you notice any keywords that are not listed, you can easily add them manually to ensure complete coverage.")
                </span>
            </div>
            <div class="form-group">
                <textarea class="form-control langKeys fs-14" readonly></textarea>
            </div>
            <button type="button" class="btn btn--primary w-100  copyBtn btn-large my-2">
                <i class="las la-copy"></i> <span class="copy-text">@lang('Copy')</span>
            </button>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <div class=" d-flex gap-2 flex-wrap">
        <button type="button" class="btn  btn--primary addBtn flex-fill">
            <i class="las la-plus me-1"></i>@lang('Add New Language')
        </button>
        <button type="button" class="btn  btn--info keyBtn flex-fill" data-bs-toggle="modal"
            data-bs-target="#getLangModal">
            <i class="las la-code me-1"></i>@lang('Language Keywords')
        </button>
    </div>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $(".addBtn").on('click', function(e) {
                const $modal = $("#langModal");
                const action = "{{ route('admin.language.manage.store') }}";
                $modal.find('form').attr('action', action).trigger('reset');
                $modal.find('.modal-title').text("@lang('Add New Language')");
                $modal.find('input[name=code]').attr('required', true).parent().removeClass('d-none');
                $modal.find('[name=is_default]').attr("checked", false);
                $modal.find('input[name=image]').attr('required', true).closest(".form-group").find('label')
                    .addClass('required');
                $modal.find('.image-upload__placeholder .image-upload__thumb img').attr('src',
                    `{{ asset('assets/images/drag-and-drop.png') }}`);
                $modal.modal('show');
            });

            $(".editBtn").on('click', function(e) {
                const $modal = $("#langModal");
                const {
                    lang,
                    image
                } = $(this).data();


                const action = "{{ route('admin.language.manage.update', ':id') }}";
                $modal.find('form').attr('action', action.replace(':id', lang.id));
                $modal.find('.modal-title').text("@lang('Edit Language')");
                $modal.find('input[name=name]').val(lang.name);
                $modal.find('[name=info]').val(lang.info);

                if (lang.is_default) {
                    $modal.find('[name=is_default]').attr("checked", true);
                } else {
                    $modal.find('[name=is_default]').attr("checked", false);
                }

                $modal.find('input[name=code]').attr('required', false).parent().addClass('d-none');
                $modal.find('input[name=image]').attr('required', false).closest(".form-group").find('label')
                    .removeClass('required');
                $modal.find('.image-upload__thumb img').attr('src', image);
                $modal.modal('show');
            });

            $('.keyBtn').on('click', function(e) {
                e.preventDefault();
                $.get("{{ route('admin.language.get.key') }}", {}, function(data) {
                    $('.langKeys').text(data);
                });
            });

            $('.copyBtn').on('click', function() {
                const $this     = $(this);
                const $textArea = $(".langKeys");
                const textArea  = $textArea[0];
                const copyText  = $textArea.text();
                const oldHtml   = $this.html();

                textArea.select();
                textArea.setSelectionRange(0, 99999);

                navigator.clipboard.writeText(copyText).then(function() {
                    $this.html(`<i class="las la-check-double fw-bold me-2"></i> Copied`);
                    setTimeout(function() {
                        $this.html(oldHtml);
                    }, 1500);
                }).catch(function(error) {
                    console.error('Copy failed!', error);
                });
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .flex-thumb-wrapper .thumb {
            width: 50px;
            height: 50px;
        }

        .langKeys {
            min-height: 450px !important;
            line-height: 1.5;
            cursor: no-drop;
        }
    </style>
@endpush
