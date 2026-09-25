@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Create interactive list with multiple sections for getting your audience attention, making messages more user-friendly and engaging.')</p>
            </div>
            <x-permission_check permission="add interactive list">
                <div class="container-top__right">
                    <div class="btn--group">
                        <a href="{{ route('user.interactive-list.create') }}" class="btn btn--base btn-shadow add-btn">
                            <i class="las la-plus"></i>
                            @lang('Add New')
                        </a>
                    </div>
                </div>
            </x-permission_check>
        </div>
        <div class="dashboard-container__body">
            <div class="body-top">
                <div class="body-top__left">
                    <form class="search-form">
                        <input type="search" class="form--control" placeholder="@lang('Search here')..." name="search"
                            value="{{ request()->search }}">
                        <span class="search-form__icon"> <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                    </form>
                </div>
            </div>
            <div class="dashboard-table">
                <table class="table table--responsive--md">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Button Text')</th>
                            <th>@lang('Created At')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($interactiveLists as $interactiveList)
                            <tr>
                                <td>{{ __(@$interactiveList->name) }}</td>
                                <td>{{ __(@$interactiveList->button_text) }}</td>
                                <td>{{ showDateTime(@$interactiveList->created_at) }} </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" data-interactive-list='@json($interactiveList)'
                                            class="text--info view-btn" data-bs-toggle="tooltip"
                                            data-bs-title="@lang('Preview')">
                                            <i class="fas fa-eye fs-14"></i>
                                        </button>
                                        <x-permission_check permission="delete interactive list">
                                            <button type="button" class="text--danger confirmationBtn"
                                                data-bs-toggle="tooltip" data-bs-title="@lang('Delete')"
                                                data-action="{{ route('user.interactive-list.delete', $interactiveList->id) }}"
                                                data-question="@lang('Are you sure to delete this Interactive List?')">
                                                <i class="las la-trash fs-16"></i>
                                            </button>
                                        </x-permission_check>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @include('Template::partials.empty_message')
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ paginateLinks($interactiveLists) }}
        </div>
    </div>

    <div class="modal fade custom--modal view-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Message Preview')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="template-info-container__right">
                        <div class="preview-item">
                            <div class="preview-item__content">
                                <div class="preview-item__shape">
                                    <img src="{{ getImage($activeTemplateTrue . 'images/preview-1.png') }}" alt="image">
                                </div>
                                <div>
                                    <div class="card-item">
                                        <div class="card-item__content">
                                            <p class="card-item__title header_text fs-14">@lang('Message Header')</p>
                                            <p class="card-item__desc body_text">@lang('Message body')</p>
                                            <p class="text-wrapper">
                                                <span class="text footer_text">@lang('Footer text')</span>
                                                <span class="text time-preview">{{ date('h:i A') }}</span>
                                            </p>
                                        </div>
                                        <div class="button-preview mt-2 border-top text-center p-2">
                                            <button class="button-text-preview text--base w-100">
                                                <i class="las la-list"></i>
                                                <span class="button-text text--base">@lang('Button text')</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="message-preview-list">
                                    <div class="message-preview-list__header">
                                        <span class="close-preview-btn"><i class="las la-times"></i></span>
                                        <h6 class="message-preview-list__header__title button-text">@lang('Button text')</h6>
                                    </div>

                                    <div class="message-preview-list__body">
                                        <div class="message-preview-list__section_wrapper">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal :isFrontend="true" />
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            const $modal = $('.view-modal');

            $('.view-btn').on('click', function() {

                const interactiveList = $(this).data('interactive-list');
                const sections = interactiveList.sections;
                const $sectionWrapper = $('.message-preview-list__section_wrapper');

                $sectionWrapper.html('');
                sections.forEach((section, index) => {
                    const sectionHtml = `
                    <div class="message-preview-list__section" data-section-index="${index}">
                        <p class="message-preview-list__section-title">${section.title}</p>
                        <div class="row_wrapper">
                            ${section.rows.map(row => {
                                return `
                                                                    <div class="message-preview-list__row">
                                                                        <div class="message-preview-list__text">
                                                                            <p class="title">${row.title}</p>
                                                                            <p class="desc">${row.description}</p>
                                                                        </div>
                                                                        <span class="message-preview-list__radio"></span>
                                                                    </div>
                                                                    `;
                            }).join('')}
                        </div>
                    </div>
                    `;
                    $sectionWrapper.append(sectionHtml);
                });

                $modal.find('.header_text').text(interactiveList.header?.text ?? '');
                $modal.find('.body_text').text(interactiveList.body?.text ?? '');
                $modal.find('.footer_text').text(interactiveList.footer?.text ?? '');
                $modal.find('.button-text').text(interactiveList.button_text ?? '');

                $modal.modal('show');
            });

            $(".button-text-preview").on("click", function() {
                $(".message-preview-list").addClass("active");
            });

            $(".close-preview-btn").on("click", function() {
                $(".message-preview-list").removeClass("active");
            });

            $('.preview-item__shape').on('click', function() {
                $(".message-preview-list").removeClass("active");
            });

            $modal.on('hidden.bs.modal', function() {
                $(".message-preview-list").removeClass("active");
            });


        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .template-info-container__right {
            width: 100% !important;
        }

        .template-info-container__right .preview-item__content {
            height: 400px !important;
        }

        .template-info-container__right .preview-item__content .card-item {
            width: 100%;
            border-radius: 10px;
        }

        @media screen and (max-width: 1499px) {
            .template-info-container__right {
                width: 400px;
            }
        }

        @media screen and (max-width: 1499px) {
            .template-info-container__right {
                margin-inline: auto;
            }
        }

        @media screen and (max-width: 575px) {
            .template-info-container__right {
                width: 100%;
            }
        }

        .button-text-preview {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 5px !important;
            cursor: pointer !important;
        }

        .message-preview-list {
            background: hsl(var(--white));
            position: absolute;
            top: 25px;
            left: 0;
            height: 100%;
            width: 100%;
            border-radius: 12px;
            overflow: auto;
            transform: translateY(100%);
            opacity: 0;
            transition: all 0.3s ease;
            pointer-events: none;
            scrollbar-width: thin;
            scrollbar-color: hsl(var(--base) / 0.8) hsl(var(--black) / 0.1);
        }

        .message-preview-list::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .message-preview-list.active {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        .message-preview-list__header {
            position: relative;
            padding: 12px 16px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        }

        .close-preview-btn {
            position: absolute;
            left: 16px;
            top: 12px;
            font-size: 20px;
            cursor: pointer;
        }

        .message-preview-list__header__title {
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }

        .message-preview-list__body {
            padding: 8px 0 16px;
        }

        .message-preview-list__section_wrapper {
            display: flex;
            flex-direction: column;
        }

        .message-preview-list__section-title {
            font-size: 16px;
            font-weight: 500;
            color: #555;
            padding: 12px 16px 0px 10px;
            text-transform: lowercase;
        }

        .message-preview-list__row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 8px;
        }

        .message-preview-list__row:hover {
            background: rgba(0, 0, 0, 0.04);
        }

        .message-preview-list__text .title {
            font-size: 13px;
            font-weight: 500;
            margin: 0;
            color: #111;
        }

        .message-preview-list__text .desc {
            font-size: 13px;
            color: #8696a0;
            margin: 2px 0 0;
        }

        .message-preview-list__radio {
            width: 15px;
            height: 15px;
            border: 2px solid #8696a0;
            border-radius: 50%;
            flex-shrink: 0;
            margin-left: 12px;
        }

        .message-preview-list__row.selected .message-preview-list__radio {
            border: 5px solid #00a884;
        }
    </style>
@endpush
