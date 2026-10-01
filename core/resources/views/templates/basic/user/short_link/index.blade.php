@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Manage your whatsApp short links with ease.')</p>
            </div>
            <x-permission_check permission="add shortlink">
                <div class="container-top__right">
                    <div class="btn--group">
                        <a href="{{ route('user.shortlink.create') }}" class="btn btn--base btn-shadow"> <i
                                class="las la-plus"></i>
                            @lang('Add Short Link')
                        </a>
                    </div>
                </div>
            </x-permission_check>
        </div>
        <div class="dashboard-container__body">
            <div class="dashboard-table">
                <table class="table table--responsive--xxl">
                    <thead>
                        <tr>
                            <th>@lang('ShortLink')</th>
                            <th>@lang('Mobile')</th>
                            <th>@lang('Total Click')</th>
                            <th>@lang('action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shortLinks as $shortLink)
                            <tr>
                                <td>
                                    <a href="{{ route('short.link.redirect', $shortLink->code) }}" target="_blank"
                                        class="text--base">{{ route('short.link.redirect', $shortLink->code) }}
                                    </a>
                                </td>
                                <td> +{{ $shortLink->dial_code . '' . $shortLink->mobile }} </td>
                                <td> {{ $shortLink->click }} </td>
                                <td>
                                    <div class="action-buttons">
                                        <x-permission_check permission="edit shortlink">
                                            <a href="{{ route('user.shortlink.edit', $shortLink->id) }}"
                                                class="action-btn  text--base" data-bs-toggle="tooltip"
                                                title="@lang('Edit')">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                        </x-permission_check>
                                        <button type="button" class="action-btn text--info qrCodeBtn"
                                            data-bs-toggle="tooltip" title="@lang('Get Link')"
                                            data-id="{{ $shortLink->id }}"
                                            data-image="{{ cryptoQR( route('short.link.redirect', $shortLink->code)) }}"
                                            data-url="{{  route('short.link.redirect', $shortLink->code) }}">
                                            <i class="fa fa-link"></i>
                                        </button>
                                        <x-permission_check permission="delete shortlink">
                                            <button type="button" class="action-btn text--danger confirmationBtn"
                                                data-bs-toggle="tooltip" data-question="@lang('Are you sure to remove this short link?')"
                                                data-action="{{ route('user.shortlink.delete', @$shortLink->id) }}"
                                                title="@lang('Delete')"><i class="fa-regular fa-trash-can"></i>
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
            {{ paginateLinks($shortLinks) }}
        </div>
    </div>

    <div class="modal fade custom--modal" id="linkModal" tabindex="-1" aria-labelledby="linkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Get Short Link')</h5>
                    
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="icon">
                            <i class="fas fa-times"></i>
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-5">
                        <div class="text-center qrCode mb-4">
                            <div class="qrcode_image" id="qrcode-canvas">
                                <img src="" alt="">
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="input-group">
                                <input type="text" class="form-control form--control form-two qrcode-url-input" readonly>
                                <span class="input-group-text cursor-pointer copy-url"><i class="la la-copy"></i></span>
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
        "use strict";
        (function($) {

            $('.qrCodeBtn').on('click', function() {
               var qrCodeUrl = $(this).data('url');
               var qrCodeImage = $(this).data('image');
                $(".qrcode-url-input").val(qrCodeUrl);
                $(".qrcode_image img").attr('src', qrCodeImage);
                $("#linkModal").modal('show');
            });

            $('.generatedLink').on('click', function() {
                var link = $(this).text();
                window.open(link, '_blank');
            })

            $('.copy-url').on('click', function() {
                try {
                    navigator.clipboard.writeText(qrCodeUrl).then(() => {
                        notify('success', 'Link copied to clipboard!');
                    });
                } catch (e) {
                    notify('error', 'Unable to copy link!');
                }

            });
            
        })(jQuery);
    </script>
@endpush




@push('style')
    <style>
        .qrCode {
            border: 5px solid hsl(var(--base));
            padding: 40px;
            border-radius: 5px;
        }
    </style>
@endpush
