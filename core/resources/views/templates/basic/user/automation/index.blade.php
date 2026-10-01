@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="row gy-4">
            <div class="col-lg-12">
                <div class="chatbot-item">
                    <div class="chatbot-item__top">
                        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                            <div class="left">
                                <h5 class="title">{{ __(@$pageTitle) }}</h5>
                                <p class="text">@lang('Create and manage your chatbots for faster and efficient response.')</p>
                            </div>
                            <x-permission_check permission="add chatbot">
                                <div class="right">
                                    <button type="button" class="btn btn--base addChatBot btn-shadow">
                                        <i class="las la-plus"></i> @lang('Add New Bot')
                                    </button>
                                </div>
                            </x-permission_check>
                        </div>
                    </div>
                    <div class="chatbot-item__body">
                        <div class="tab-content" id="pills-tabContentthre">
                            <div class="tab-pane fade show active" id="pills-auto" role="tabpanel"
                                aria-labelledby="pills-auto-tab" tabindex="0">
                                <table class="table table--responsive--xxl">
                                    <thead>
                                        <tr>
                                            <th>@lang('Title')</th>
                                            <th>@lang('Keyword')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($chatbots as $chatbot)
                                            <tr>
                                                <td>{{ __(@$chatbot->title) }}</td>
                                                <td>{{ __(@$chatbot->keywords) }}</td>
                                                <td>
                                                    <x-permission_check permission="edit chatbot">
                                                        <div class="form--switch two">
                                                            <input class="form-check-input status-switch" type="checkbox"
                                                                role="switch" @checked($chatbot->status)
                                                                data-action="{{ route('user.automation.chatbot.status', $chatbot->id) }}"
                                                                data-message-enable="@lang('Are you sure to enable this chatbot')"
                                                                data-message-disable="@lang('Are you sure to disable this chatbot')" />
                                                        </div>
                                                    </x-permission_check>
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <x-permission_check permission="edit chatbot">
                                                            <button type="button" class="action-btn editChatBot text--base"
                                                                data-bs-toggle="tooltip" data-bs-title="@lang('Edit')"
                                                                data-chatbot="{{ $chatbot }}">
                                                                <i class="fas fa-pen"></i>
                                                            </button>
                                                        </x-permission_check>
                                                        <x-permission_check permission="delete chatbot">
                                                            <button type="button"
                                                                class="action-btn confirmationBtn text--danger"
                                                                data-bs-toggle="tooltip" data-question="@lang('Are you sure to remove this chatbot?')"
                                                                data-action="{{ route('user.automation.chatbot.delete', $chatbot->id) }}"
                                                                data-bs-title="@lang('Delete')"><i
                                                                    class="fas fa-trash"></i>
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
                                {{ paginateLinks($chatbots) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade custom--modal" id="addChatbot">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Chatbot')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="icon">
                            <i class="fas fa-times"></i>
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="label-two">@lang('Whatsapp Account')</label>
                            <x-whatsapp_account />
                        </div>
                        <div class="form-group">
                            <label class="label-two">@lang('Title')</label>
                            <input type="text" class="form--control form-two" name="title"
                                placeholder="@lang('Enter a tile text')" required>
                        </div>
                        <div class="form-group">
                            <label class="label-two">@lang('Keyword')</label>
                            <input type="text" class="form--control form-two" name="keyword"
                                placeholder="@lang('Enter trigger keyword')" required value="{{ old('keyword') }}">
                        </div>
                        <div class="form-group text-response">
                            <label class="label-two">@lang('Response')</label>
                            <textarea class="form--control form-two" name="text" placeholder="@lang('Enter response text')"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn--white " data-bs-dismiss="modal">
                                <i class="la la-times"></i> @lang('Cancel')
                            </button>
                            <button type="submit" class="btn  btn--base">
                                <i class="la la-telegram"></i> @lang('Save Chatbot')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal isFrontend="true" />
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush
@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            const $modal = $('#addChatbot');
            const $confirmationModal = $('#confirmationModal');

            $('.addChatBot').on('click', function() {
                $modal.find('.modal-title').text("@lang('Add New Chatbot')");
                $modal.find('form').trigger('reset');
                $modal.find('form').attr('action', "{{ route('user.automation.chatbot.store') }}");

                $modal.find('input[name=keyword]').attr('readonly', false);
                $modal.modal('show');
            });

            $('.editChatBot').on('click', function() {
                let chatbot = $(this).data('chatbot');
                let route = "{{ route('user.automation.chatbot.update', ':id') }}";

                $modal.find('form').attr('action', route.replace(':id', chatbot.id));
                $modal.find('.modal-title').text('Edit Chatbot');
                $modal.find('input[name=title]').val(chatbot.title);
                $modal.find('input[name=keyword]').val(chatbot.keywords);
                $modal.find('select[name=whatsapp_account_id]').val(chatbot.whatsapp_account_id).trigger(
                    'change');
                $modal.find('textarea[name=text]').val(chatbot.text);

                $modal.modal('show');
            });

            $('.status-switch').on('click', function(e) {
                e.preventDefault();

                const action = $(this).data('action');
                const messageEnable = $(this).data('message-enable');
                const messageDisable = $(this).data('message-disable');

                if (e.target.checked) {
                    $confirmationModal.find(".question").text(messageEnable)
                } else {
                    $confirmationModal.find(".question").text(messageDisable)
                }
                $confirmationModal.find('form').attr('action', action);
                $confirmationModal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
