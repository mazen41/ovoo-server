@extends('admin.layouts.app')
@section('panel')
    <div class="row responsive-row">
        @foreach ($extensions as $extension)
            <div class="col-xxl-4  col-xl-6 col-md-6">
                <x-admin.ui.card class="h-100">
                    <x-admin.ui.card.body class="position-relative">
                        <div class="extention-status">
                            @php echo $extension->statusBadge; @endphp
                        </div>
                        <div class="flex-thumb-wrapper mb-3  align-items-center">
                            <div class="thumb">
                                <img src="{{ getImage(getFilePath('extensions') . '/' . $extension->image, getFileSize('extensions')) }}"
                                    class="thumb-img">
                            </div>
                            <span class="ms-2">{{ __($extension->name) }}</span>
                        </div>
                        <div class="mb-3">
                            <p>{{ __($extension->info) }}</p>
                        </div>
                        <div class="btn--group">
                            <button type="button" class="flex-sm--fill btn  btn-outline--primary  editBtn"
                                data-name="{{ __($extension->name) }}"
                                data-shortcode="{{ json_encode($extension->shortcode) }}"
                                data-action="{{ route('admin.extensions.update', $extension->id) }}">
                                <span class=" btn--icon"><i class="la la-tools"></i></span>@lang('Configure')
                            </button>
                            <button type="button" class="flex-sm--fill btn  btn-outline--secondary helpBtn"
                                data-description="{{ __($extension->description) }}"
                                data-support="{{ __($extension->support) }}">
                                <span class="btn--icon"><i class="la la-info"></i></span>@lang('Help')
                            </button>
                            @if ($extension->status == Status::DISABLE)
                                <button type="button" class="flex-sm--fill btn  btn-outline--success  confirmationBtn"
                                    data-action="{{ route('admin.extensions.status', $extension->id) }}"
                                    data-question="@lang('Are you sure to enable this extension?')">

                                    <span class="btn--icon"><i class="la la-eye"></i></span>@lang('Enable')
                                </button>
                            @else
                                <button type="button" class="flex-sm--fill btn  btn-outline--danger  confirmationBtn"
                                    data-action="{{ route('admin.extensions.status', $extension->id) }}"
                                    data-question="@lang('Are you sure to disable this extension?')">
                                    <span class="btn--icon"><i class="la la-eye-slash"></i></span>@lang('Disable')
                                </button>
                            @endif
                        </div>
                    </x-admin.ui.card.body>
                </x-admin.ui.card>
            </div>
        @endforeach
    </div>

    <x-admin.ui.modal id="editModal">
        <x-admin.ui.modal.header>
            <h1 class="modal-title">@lang('Update Extension'): <span class="extension-name"></span></h1>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form method="POST">
                @csrf
                <div class="ext-config">
                    <div class="form-group">
                        <label class="form-label">@lang('Script')</label>
                        <textarea name="script" class="form-control" required rows="8" placeholder="@lang('Paste your script with proper key')">{{ old('script') }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>

    <x-admin.ui.modal id="helpModal">
        <x-admin.ui.modal.header>
            <h1 class="modal-title">@lang('Need Help')</h1>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>


    <x-confirmation-modal />
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            $(document).on('click', '.editBtn', function() {
                let modal = $('#editModal');
                let shortcode = $(this).data('shortcode');

                modal.find('.extension-name').text($(this).data('name'));
                modal.find('form').attr('action', $(this).data('action'));

                let html = '';
                $.each(shortcode, function(key, item) {
                    html += `<div class="form-group">
                        <label class="form-label required">${item.title}</label>
                        <input name="${key}" class="form-control" placeholder="--" value="${item.value}" required>
                    </div>`;
                })
                modal.find('.ext-config').html(html);
                modal.modal('show');
            });

            $(document).on('click', '.helpBtn', function() {
                let modal = $('#helpModal');
                let path = "{{ asset(getFilePath('extensions')) }}";
                modal.find('.modal-body').html(`<div class="mb-2">${$(this).data('description')}</div>`);
                if ($(this).data('support') != 'na') {
                    modal.find('.modal-body').append(
                        `<img src="${path}/${$(this).data('support')}">`);
                }
                modal.modal('show');
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

        .extention-status {
            position: absolute;
            right: 16px;
            top: 16px;
        }

        @media screen and (max-width: 375px) {
            .extention-status {
                right: 5px;
                top: 2px;
            }
        }
    </style>
@endpush
