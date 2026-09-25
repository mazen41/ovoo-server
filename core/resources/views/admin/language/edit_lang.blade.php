@extends('admin.layouts.app')
@section('panel')
    <form action="{{ route('admin.language.update.key', $lang->id) }}" method="post" class="scale-1">
        @csrf
        <div class="mb-3 pb-4">
            <div class=" update-lang__wrapper">
                @forelse($keywords as $k => $keyword)
                    <x-admin.ui.card class="update-lang">
                        <x-admin.ui.card.body class="py-3">
                            <span role="button" class="keyword-remove confirmationBtn" data-question="@lang('Are you sure to remove this language keyword?')"
                                data-action="{{ route('admin.language.delete.key', [$lang->id, $k]) }}">
                                <i class="las la-times"></i>
                            </span>
                            <p class="mb-2 fs-14">
                                <span class="keyword">{{ strLimit($k, 32) }}</span>
                                @if (strlen($k) > 32)
                                    <span class="fs-13 cursor-pointer text--primary see-more"
                                        data-keyword-full="{{ $k }}" data-keyword-short="{{ strLimit($k, 32) }}"
                                        mode="1">
                                        @lang('See More')
                                    </span>
                                @endif
                            </p>
                            <div class="input-group">
                                <input type="text" value="{{ $keyword }}" class="form-control"
                                    name="keyword[{{ $k }}]">
                            </div>
                        </x-admin.ui.card.body>
                    </x-admin.ui.card>
                @empty
                    <x-admin.other.card_empty_message />
                @endforelse
            </div>
        </div>
        @if (count($keywords))
            <div class=" d-flex flex-wrap gap-3 fixed-footer">
                <a href="{{ route('admin.language.manage') }}" class="btn btn--secondary btn-large">
                    <i class="la la-undo"></i> @lang('Back to Language')
                </a>
                <x-admin.ui.btn.submit />
            </div>
        @endif
    </form>
    <x-admin.ui.modal id="langModal">
        <x-admin.ui.modal.header>
            <h1 class="modal-title">@lang('Add New Keyword')</h1>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form action="{{ route('admin.language.store.key', $lang->id) }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="key">@lang('Key')</label>
                    <input type="text" class="form-control" name="key" value="{{ old('key') }}" required>
                </div>
                <div class="form-group">
                    <label for="value">@lang('Value')</label>
                    <input type="text" class="form-control" name="value" value="{{ old('value') }}" required>
                </div>
                <div class="form-group">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>

    <x-admin.ui.modal id="importModal">
        <x-admin.ui.modal.header>
            <h1 class="modal-title">@lang('Import Keywords')</h1>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form action="{{ route('admin.language.import.lang') }}" method="POST">
                @csrf
                <input type="hidden" name="to_lang_id" value="{{ $lang->id }}">
                <div class="form-group">
                    <label>@lang('Import From')</label>
                    <select class="form-control select_lang select2" data-minimum-results-for-search="-1" required
                        name="id">
                        <option value="">@lang('Select One')</option>
                        <option value="999">@lang('System')</option>
                        @foreach ($languages as $language)
                            <option value="{{ $language->id }}">{{ __($language->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>
    <x-confirmation-modal />
@endsection


@push('breadcrumb-plugins')
    <div class="d-flex gap-2 flex-wrap">
        <div class="flex-fill">
            <x-admin.other.search-form placeholder="Search keywords" />
        </div>
        <button type="button" class="btn flex-fill btn--primary addBtn">
            <i class="fas fa-plus"></i>
            @lang('Add Keyword')
        </button>
        <button type="button" class="btn flex-fill btn--dark importBtn">
            <i class="la la-download"></i>
            @lang('Import Keywords')
        </button>
    </div>
@endpush


@push('script')
    <script>
        "use strict";
        (function($) {

            $(".addBtn").on('click', function(e) {
                const $modal = $("#langModal");
                $modal.modal('show');
            });

            $(".importBtn").on('click', function(e) {
                const $modal = $("#importModal");
                $modal.modal('show');
            });

            $(".editBtn").on('click', function(e) {
                const $modal = $("#langModal");
                const {
                    key,
                    value
                } = $(this).data();

                $modal.find('.modal-title').text("@lang('Edit Keyword')");
                $modal.find('form').attr('action', "{{ route('admin.language.update.key', $lang->id) }}");
                $modal.find('input[name=key]').val(key);
                $modal.find('input[name=value]').val(value);
                $modal.find('input[name=key]').attr('required', false).attr('readonly', true);
                $modal.modal('show');
            });


            $(".see-more").on('click', function(e) {
                let kewyordFull = $(this).data('keyword-full');
                let kewyordShort = $(this).data('keyword-short');
                let mode = $(this).attr('mode');
                if (mode == 1) {
                    $(this).attr('mode', 2)
                    $(this).parent().find('.keyword').text(kewyordFull);
                    $(this).text("@lang('See Less')");
                } else {
                    $(this).attr('mode', 1)
                    $(this).parent().find('.keyword').text(kewyordShort);
                    $(this).text("@lang('See More')");
                }
            });
            $('.dashboard__area-header').addClass('fixed-header');
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .lang-keywords {
            max-height: 635px;
            min-height: 635px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .keyword-remove {
            position: absolute;
            right: 5px;
            top: 5px;
            border: 1px solid transparent;
            width: 22px;
            height: 22px;
            border-radius: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            background: hsl(var(--black)/0.02);
            transition: all 0.2s;
        }

        .keyword-remove:hover {
            border-color: hsl(var(--border-color));
            transform: scale(1.05)
        }

        .update-lang {
            position: relative;
        }

        .update-lang__wrapper:has(.update-lang) {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(375px, 1fr));
            gap: var(--inner-gap);
            margin-bottom: 30px;
        }

        @media screen and (max-width:442px) {
            .update-lang__wrapper:has(.update-lang) {
                grid-template-columns: 1fr;
            }

        }



        .fixed-header {
            position: sticky;
            top: 0;
            left: 0;
            z-index: 99;
            margin: 0;
            padding-block: var(--inner-gap);
            background: hsl(var(--bg-color));
            box-shadow: -15px 0 0 0 hsl(var(--bg-color)), 15px 0 0 0 hsl(var(--bg-color));
        }


        .fixed-header+.dashboard__area-inner {
            margin-top: 0;
        }

        .fixed-footer {
            background: hsl(var(--bg-color));
            position: fixed;
            padding: 20px 31px;
            width: calc(100% - var(--sidebar));
            top: calc(100vh - 80px);
            justify-content: flex-end;
            right: 0;
        }

        @media (max-width:1200px) {
            .fixed-footer {
                width: 100%;
            }
        }

        @media (max-width:575px) {
            .fixed-footer {
                padding: 12px 31px;
                justify-content: center;
                top: calc(100vh - 58px);
            }
        }
        @media (max-width:800px) {
           .update-lang__wrapper:has(.update-lang){
                margin-bottom: 40px;
            }   
        }
        @media (max-width:425px) {
            .fixed-footer {
                padding: 12px 31px;
                top: calc(100vh - 110px);
            }

            .fixed-footer .btn {
                width: 100%;
            }
            .fixed-footer > div{
                width: 100%;
            }
            .update-lang__wrapper:has(.update-lang){
                margin-bottom: 70px;
            }
        }
    </style>
@endpush