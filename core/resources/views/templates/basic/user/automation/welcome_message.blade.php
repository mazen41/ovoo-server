@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4">
        @forelse ($welcomeMessages as $welcomeMessage)
            <div class="col-xl-6 col-lg-12 col-md-6">
                <div class="chatbot-top-item">
                    <div class="chatbot-top-item__header">
                        <div>
                            <h4 class="title"> @lang('Welcome Message')👋 -
                                {{ __($welcomeMessage->whatsappAccount->phone_number) }}
                            </h4>
                            <span class="d-block fs-14">
                                {{ __($welcomeMessage->whatsappAccount->business_name) }}
                            </span>
                        </div>
                        <x-permission_check permission="edit welcome message">
                            <div class="form--switch two">
                                <input class="form-check-input status-switch" type="checkbox" role="switch"
                                    @checked($welcomeMessage->status)
                                    data-action="{{ route('user.automation.welcome.message.status', $welcomeMessage->id) }}"
                                    data-message-enable="@lang('Are you sure to enable this welcome message')" data-message-disable="@lang('Are you sure to disable this welcome message')" />
                            </div>
                        </x-permission_check>
                    </div>
                    <p class="chatbot-top-item__desc">
                        @php echo $welcomeMessage->message @endphp
                    </p>
                    <x-permission_check permission="edit welcome message">
                        <div class="chatbot-top-item__btn">
                            <button class="btn btn--white btn--sm edit-btn" data-message='@json($welcomeMessage)'>
                                <i class="la la-pen"></i> @lang('Edit Message')
                            </button>
                        </div>
                    </x-permission_check>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="chatbot-top-item text-center py-5">
                    <div class="py-5">
                        <button class="btn btn--base btn-shadow add-btn mb-2" type="button">
                            <i class="las la-plus"></i>
                            @lang('Create First Welcome Message')
                        </button>
                        <p>@lang('Add a engaging welcome message to your WhatsApp Business account to create a great first impression')</p>
                    </div>
                </div>
            </div>
        @endforelse

        <x-permission_check permission="add welcome message">
            @if ($accounts->count() && $welcomeMessages->count())
                <div class="col-xl-6 col-lg-12 col-md-6">
                    <div class="chatbot-top-item text-center py-5">
                        <button class="btn btn--base btn-shadow add-btn mb-2" type="button">
                            <i class="las la-plus"></i> @lang('New Welcome Message')
                        </button>
                        <p>@lang('Add a more engaging welcome message to your WhatsApp Business account to create a great first impression')</p>
                    </div>
                </div>
            @endif
        </x-permission_check>
    </div>

    <div class="modal fade custom--modal" id="modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Welcome Message')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="icon">
                            <i class="fas fa-times"></i>
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.automation.welcome.message.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="label-two">@lang('Whatsapp Account')</label>
                            <select name="whatsapp_account_id" class="form-control form-tow select2"
                                data-minimum-results-for-search="-1">
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ __($account->business_name) }}({{ $account->phone_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="label-two">@lang('Response')</label>
                            <textarea class="form--control form-two" name="message" placeholder="@lang('Enter Response Text')"></textarea>
                        </div>
                        <div class="form-group d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn--white " data-bs-dismiss="modal">
                                <i class="la la-times"></i> @lang('Cancel')
                            </button>
                            <button type="submit" class="btn  btn--base">
                                <i class="la la-telegram"></i> @lang('Save Message')
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

            const $modal = $('#modal');
            const $confirmationModal = $('#confirmationModal');

            $('.add-btn').on('click', function() {
                const action = "{{ route('user.automation.welcome.message.store') }}";
                $modal.find('.modal-title').text('Add Your Welcome Message');
                $modal.find('form').trigger('reset');
                $modal.find(`form`).attr('action', action);
                $modal.find('select[name=whatsapp_account_id]').closest('.form-group').removeClass('d-none');
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


            $('.edit-btn').on('click', function() {
                const message = $(this).data('message');
                const action = "{{ route('user.automation.welcome.message.store', ':id') }}";
                $modal.find('.modal-title').text('Edit Welcome Message');
                $modal.find('textarea[name=message]').val(message.message);
                $modal.find('select[name=whatsapp_account_id]').closest('.form-group').addClass('d-none');
                $modal.find(`form`).attr('action', action.replace(":id", message.id));
                $modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
