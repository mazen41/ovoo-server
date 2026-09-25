@extends('admin.layouts.app')
@section('panel')
    <div class="row responsive-row">
        @forelse($aiAssistants as $k => $aiAssistant)
            <div class="col-xl-4 col-xxl-3 col-sm-6 gateway-col">
                <x-admin.ui.card>
                    <x-admin.ui.card.body class="position-relative">
                        <div class="gateway-status">
                            <div class="form-check form-switch form--switch pl-0 form-switch-success justify-content-end">
                                <input class="form-check-input status-switch" type="checkbox" role="switch"
                                    @checked($aiAssistant->status)
                                    data-action="{{ route('admin.ai-assistant.status', $aiAssistant->id) }}"
                                    data-message-enable="@lang('Are you sure to enable this AI assistant?')" data-message-disable="@lang('Are you sure to disable this AI assistant?')">
                            </div>
                        </div>
                        <div class="flex-thumb-wrapper mb-3  align-items-center">
                            <div class="thumb">
                                <img src="{{ getImage(getFilePath('aiAssistant') . '/' . @$aiAssistant->provider . '.png', getFileSize('aiAssistant')) }}"
                                    class="thumb-img">
                            </div>
                            <span class="ms-2 gateway-name">{{ __($aiAssistant->name) }}</span>
                        </div>
                        <div class="mb-3">
                            <p>
                                {{ __($aiAssistant->info) }}
                            </p>
                        </div>
                        <button type="button" data-assistant="{{ $aiAssistant }}"
                            class="btn btn-outline--primary configureBtn">
                            <span class=" btn--icon"><i class="la la-tools"></i></span>
                            @lang('Configure')
                        </button>
                    </x-admin.ui.card.body>
                </x-admin.ui.card>
            </div>
        @endforeach
    </div>

    <x-admin.ui.modal id="configureModal">
        <x-admin.ui.modal.header>
            <h4 class="modal-title">@lang('Configure AI Assistant')</h4>
            <p class="modal-subtitle text-muted mt-1">@lang('For further information, please refer to the official documentation.')
                <a href="#" target="_blank">
                    <i class="las la-external-link-alt"></i>
                    <span class="text"></span>
                </a>
            </p>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form action="" method="POST">
                @csrf
                <div class="config-data">

                </div>
                <div class="form-group">
                    <x-admin.ui.btn.modal />
                </div>

            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>

    <x-confirmation-modal />
@endsection


@push('script')
    <script>
        "use strict";
        (function($) {

            const $modal = $('#configureModal');

            $('.configureBtn').on('click', function() {

                const provider = $(this).data('assistant');
                const route = "{{ route('admin.ai-assistant.configure', ':id') }}";

                let html = '';
                $.each(provider.config, function(key, item) {
                    html += `<div class="form-group">
                        <label class="form-label required">${toTitleCase(key)}</label>
                        <input name="${key}" class="form-control" placeholder="Enter ${key.replace('_', ' ')}" value="${item}" required>
                    </div>`;
                });

                $modal.find('form').attr('action', route.replace(':id', provider.id));
                $modal.find('.config-data').html(html);
                $modal.find('.modal-title').text("@lang('Configure') " + ' ' + provider.name);
                $modal.find('.modal-subtitle').find('a').attr('href', provider.url);
                $modal.find('.modal-subtitle').find('a').find('.text').text(provider.name);

                $modal.modal('show');
            });

            function toTitleCase(str) {
                return str
                    .toLowerCase() 
                    .split(' ') 
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1)) // capitalize first letter
                    .join(' ')
                    .replace("_"," "); 
            }
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .flex-thumb-wrapper .thumb {
            width: 50px;
            height: 50px;
        }

        .gateway-status {
            position: absolute;
            right: 16px;
            top: 16px;
        }

        .modal-header {
            display: unset !important;
        }
    </style>
@endpush
