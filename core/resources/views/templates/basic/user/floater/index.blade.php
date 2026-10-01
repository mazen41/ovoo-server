@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Quickly manage your generated whatsapp floater widgets')</p>
            </div>
            <x-permission_check permission="add floater">
                <div class="container-top__right">
                    <div class="btn--group">
                        <a href="{{ route('user.floater.create') }}" class="btn btn--base btn-shadow">
                            <i class="las la-plus"></i>
                            @lang('Add Floater')
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
                            <th>@lang('Number')</th>
                            <th>@lang('Created At')</th>
                            <th>@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($floaters as $floater)
                            <tr>
                                <td>{{ $floater->dial_code . $floater->mobile }}</td>
                                <td>{{ showDateTime($floater->created_at) }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn  text--base previewBtn" data-bs-toggle="tooltip"
                                            data-bs-title="@lang('Preview')" data-message="{{ $floater->message }}"
                                            data-dial-code="{{ $floater->dial_code }}" data-mobile="{{ $floater->mobile }}"
                                            data-color="{{ $floater->color_code }}">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <button class="action-btn  text--info  scriptBtn " data-bs-toggle="tooltip"
                                            data-id="{{ $floater->id }}" data-bs-title="@lang('Script')"><i
                                                class="fas fa-code text--info"></i>
                                        </button>

                                        <x-permission_check permission="delete floater">
                                            <button type="button" class="action-btn  text--danger confirmationBtn"
                                                data-bs-toggle="tooltip" data-bs-title="@lang('Delete')"
                                                data-question="@lang('Are you sure to delete this floater?')"
                                                data-action="{{ route('user.floater.delete', $floater->id) }}">
                                                <i class="fa-regular fa-trash-can"></i>
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
            {{ paginateLinks($floaters) }}
        </div>
    </div>

    <div class="modal fade custom--modal" id="linkModal" tabindex="-1" role="dialog" aria-labelledby="linkModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title">@lang('Floater Script')</h5>
                        <p class="fs-14">@lang('Add the following script to your website within the head tag for proper functionality.')</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="icon">
                            <i class="fas fa-times"></i>
                        </span>
                    </button>
                </div>
                <div class="modal-body script-area">
                    <textarea class="form--control form-two floaterScript" readonly></textarea>
                    <button class="btn btn--base mt-3 copyScript">
                        <i class="las la-copy"></i> @lang('Copy Script')
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade custom--modal" id="previewModal" tabindex="-1" role="dialog"
        aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('This is how it’ll show up for the users.')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="icon">
                            <i class="fas fa-times"></i>
                        </span>
                    </button>
                </div>
                <div class="modal-body preview-area">
                    <div class="nav-bottom">
                        <div class="floater-nav-bottom">
                            <div class="floater-popup-whatsapp">
                                <div class="floater-content-whatsapp-top">
                                    <div class="floater-header-top-wrapper">
                                        <p>@lang('Welcome')</p>
                                        <button type="button" class="floater-closePopup">
                                            <i class="las la-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="floater-body-wrap">
                                    <p class="floater-p-tag">@lang('Hello! How may we assist you?')</p>
                                    <div class="floater-content-whatsapp-bottom">
                                        <input class="floater-whats-input" id="whats-in" type="text"
                                            Placeholder="@lang('Send message...')" />
                                        <button class="floater-send-msPopup" id="send-btn" type="button">
                                            <i
                                                class="las la-paper-plane floater-icon-font-color--black floater-sentBtn"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="whats-openPopup" class="floater-whatsapp-button">
                                <svg fill="#ffffff" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-2.45 -2.45 35.57 35.57"
                                    xml:space="preserve" stroke="#ffffff" stroke-width="0.122668">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <g>
                                            <path
                                                d="M30.667,14.939c0,8.25-6.74,14.938-15.056,14.938c-2.639,0-5.118-0.675-7.276-1.857L0,30.667l2.717-8.017 c-1.37-2.25-2.159-4.892-2.159-7.712C0.559,6.688,7.297,0,15.613,0C23.928,0.002,30.667,6.689,30.667,14.939z M15.61,2.382 c-6.979,0-12.656,5.634-12.656,12.56c0,2.748,0.896,5.292,2.411,7.362l-1.58,4.663l4.862-1.545c2,1.312,4.393,2.076,6.963,2.076 c6.979,0,12.658-5.633,12.658-12.559C28.27,8.016,22.59,2.382,15.61,2.382z M23.214,18.38c-0.094-0.151-0.34-0.243-0.708-0.427 c-0.367-0.184-2.184-1.069-2.521-1.189c-0.34-0.123-0.586-0.185-0.832,0.182c-0.243,0.367-0.951,1.191-1.168,1.437 c-0.215,0.245-0.43,0.276-0.799,0.095c-0.369-0.186-1.559-0.57-2.969-1.817c-1.097-0.972-1.838-2.169-2.052-2.536 c-0.217-0.366-0.022-0.564,0.161-0.746c0.165-0.165,0.369-0.428,0.554-0.643c0.185-0.213,0.246-0.364,0.369-0.609 c0.121-0.245,0.06-0.458-0.031-0.643c-0.092-0.184-0.829-1.984-1.138-2.717c-0.307-0.732-0.614-0.611-0.83-0.611 c-0.215,0-0.461-0.03-0.707-0.03S9.897,8.215,9.56,8.582s-1.291,1.252-1.291,3.054c0,1.804,1.321,3.543,1.506,3.787 c0.186,0.243,2.554,4.062,6.305,5.528c3.753,1.465,3.753,0.976,4.429,0.914c0.678-0.062,2.184-0.885,2.49-1.739 C23.307,19.268,23.307,18.533,23.214,18.38z">
                                            </path>
                                        </g>
                                    </g>
                                </svg>
                            </button>
                            <div class="floater-circle-anime"></div>
                        </div>
                        <button class="whatsapp-button">
                            <span class="btn-icon">
                                <i class="fa-brands fa-whatsapp"></i>
                            </span>
                        </button>
                        <div class="circle-anime"></div>
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
            $('.scriptBtn').on('click', function() {
                var floaterId = $(this).data('id');
                $('.floaterScript').val();

                $.ajax({
                    type: "GET",
                    url: '{{ route('user.floater.script', ':id') }}'.replace(':id', floaterId),
                    success: function(response) {
                        if (response.status == 'success') {
                            $('.floaterScript').val(response.data.script);
                            $('#linkModal').modal('show');
                        } else {
                            notify('error', response.message || "@lang('Something went wrong')")
                        }
                    }
                });
            });

            $('.copyScript').on('click', function() {
                var script = $('.floaterScript').val();
                try {
                    navigator.clipboard.writeText(script);
                    notify('success', '@lang('Script copied to clipboard')');
                } catch (err) {
                    notify('error', '@lang('Failed to copy script')');
                }
            });


            $('.previewBtn').on('click', function() {
                const message = $(this).data('message');
                const dialCode = $(this).data('dial-code');
                const mobile = $(this).data('mobile');
                const color = $(this).data('color');
                const whatsappLink = `https://wa.me/${dialCode}${mobile}?text=${encodeURIComponent(message)}`;


                $('#send-btn').data('url', whatsappLink);
                $('.whatsapp-button').css('background-color', "#" + color);
                $('.floater-sentBtn').css('color', "#" + color);
                $('.floater-header-top-wrapper').css('background-color', "#" + color);
                $('#previewModal').modal('show');
                $('.floater-popup-whatsapp').addClass('d-none');


            });

            $('body').on('mouseenter', ".whatsapp-button", function() {
                $('.floater-popup-whatsapp').removeClass('d-none').css('display', 'flex');
                $('.floater-whats-input').val(message);
            });

            $('body').on('click', "#send-btn", function() {
                const whatsappUrl = $(this).data('url');
                window.open(whatsappUrl, '_blank');
            });

            $('body').on('click', ".floater-closePopup", function() {
                $('.floater-popup-whatsapp').addClass('d-none');
            });

        })(jQuery);
    </script>
@endpush
