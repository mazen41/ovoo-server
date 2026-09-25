@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Create interactive CTA URLs with buttons instead of raw links, making messages more user-friendly and engaging.')</p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <a href="{{ route('user.cta-url.create') }}" class="btn btn--base btn-shadow add-btn">
                        <i class="las la-plus"></i>
                        @lang('Add New')
                    </a>
                </div>
            </div>
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
                            <th>@lang('URL')</th>
                            <th>@lang('Created At')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ctaUrls as $url)
                            <tr>
                                <td>{{ __(@$url->name) }}</td>
                                <td><a href="{{ @$url->cta_url ?? '#' }}" target="_blank">{{ @$url->cta_url }}</a></td>
                                <td>{{ showDateTime(@$url->created_at) }} </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" data-cta-url='@json($url)'
                                            class="text--info view-btn" data-bs-toggle="tooltip"
                                            data-bs-title="@lang('Preview')">
                                            <i class="fas fa-eye fs-14"></i>
                                        </button>
                                        <button type="button" class="text--danger confirmationBtn" data-bs-toggle="tooltip"
                                            data-bs-title="@lang('Delete')" data-action="{{ route('user.cta-url.delete', $url->id) }}"
                                            data-question="@lang('Are you sure to remove this item?')">
                                            <i class="las la-trash fs-16"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @include('Template::partials.empty_message')
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ paginateLinks($ctaUrls) }}
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
                                        <div class="card-item__thumb header_media">
                                            <img src="{{ getImage($activeTemplateTrue . 'images/preview-1.png') }}"
                                                alt="image">
                                        </div>
                                        <div class="card-item__content">
                                            <p class="card-item__title header_text">@lang('Message Header')</p>
                                            <p class="card-item__desc body_text">@lang('Message body')</p>
                                            <p class="text-wrapper">
                                                <span class="text footer_text">@lang('Footer text')</span>
                                                <span class="text time-preview">{{ date('h:i A') }}</span>
                                            </p>
                                        </div>
                                        <div class="button-preview mt-2 border-top text-center p-2">
                                            <a href="" class="button-text-preview" target="_blank">
                                                <svg viewBox="0 0 19 18" height="18" width="19"
                                                    preserveAspectRatio="xMidYMid meet" version="1.1">
                                                    <path
                                                        d="M14,5.41421356 L9.70710678,9.70710678 C9.31658249,10.0976311 8.68341751,10.0976311 8.29289322,9.70710678 C7.90236893,9.31658249 7.90236893,8.68341751 8.29289322,8.29289322 L12.5857864,4 L10,4 C9.44771525,4 9,3.55228475 9,3 C9,2.44771525 9.44771525,2 10,2 L14,2 C15.1045695,2 16,2.8954305 16,4 L16,8 C16,8.55228475 15.5522847,9 15,9 C14.4477153,9 14,8.55228475 14,8 L14,5.41421356 Z M14,12 C14,11.4477153 14.4477153,11 15,11 C15.5522847,11 16,11.4477153 16,12 L16,13 C16,14.6568542 14.6568542,16 13,16 L5,16 C3.34314575,16 2,14.6568542 2,13 L2,5 C2,3.34314575 3.34314575,2 5,2 L6,2 C6.55228475,2 7,2.44771525 7,3 C7,3.55228475 6.55228475,4 6,4 L5,4 C4.44771525,4 4,4.44771525 4,5 L4,13 C4,13.5522847 4.44771525,14 5,14 L13,14 C13.5522847,14 14,13.5522847 14,13 L14,12 Z"
                                                        fill="currentColor" fill-rule="nonzero"></path>
                                                </svg>
                                                <span class="button-text text--base">@lang('Button text')</span>
                                            </a>
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
                const ctaUrl = $(this).data('cta-url');
                if (ctaUrl.header_format == 'IMAGE') {
                    $modal.find('.header_text').addClass('d-none');
                    $modal.find('.header_media').removeClass('d-none');
                    $modal.find('.header_media').find('img').attr('src', ctaUrl.header.image.link ?? '');
                } else {
                    $modal.find('.header_media').addClass('d-none');
                    $modal.find('.header_text').removeClass('d-none');
                    $modal.find('.header_text').text(ctaUrl.header.text ?? '');
                }

                $modal.find('.body_text').text(ctaUrl.body.text ?? '');
                $modal.find('.footer_text').text(ctaUrl.footer.text ?? '');
                $modal.find('.button-text-preview').find('.button-text').text(ctaUrl.action.parameters
                    .display_text ?? '');
                $modal.find('.button-text-preview').attr('href', ctaUrl.cta_url ?? '#');

                $modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .template-info-container__right {
            width: 100% !important;
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

        .template-info-container__right .preview-item {
            border: 1px solid #c1c9d066;
            border-radius: 8px;
            overflow: hidden;
            max-width: 500px;
        }
    </style>
@endpush
