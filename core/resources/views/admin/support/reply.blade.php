@extends('admin.layouts.app')

@section('panel')
    <div class="row responsive-row">
        <x-admin.permission_check permission="answer tickets">
            <div class="col-12">
                <x-admin.ui.card>
                    <x-admin.ui.card.header class="d-flex justify-content-between">
                        <div>
                            @php echo $ticket->statusBadge; @endphp
                            [@lang('Ticket#'){{ $ticket->ticket }}] {{ $ticket->subject }}
                        </div>
                    </x-admin.ui.card.header>
                    <x-admin.ui.card.body>
                        <form action="{{ route('admin.ticket.reply', $ticket->id) }}" enctype="multipart/form-data"
                            method="post">
                            @csrf
                            <div class="form-group">
                                <textarea class="form-control" name="message" rows="5" required id="inputMessage" placeholder="@lang('Enter reply here')"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="d-flex gap-2 flex-wrap mb-2 flex-between align-items-start">
                                    <span class="text--info fs-14">
                                        @lang('You can upload up to 5 files with a maximum size of ') {{ convertToReadableSize(ini_get('upload_max_filesize')) }}.
                                        @lang('Supported file formats include .jpg, .jpeg, .png, .pdf, .doc, and .docx.')
                                    </span>
                                    <div class="d-flex gap-2  flex-wrap">
                                        <button type="button"
                                            class="btn  btn--secondary btn-large addAttachment flex-fill">
                                            <i class="fas fa-plus"></i>
                                            @lang('Add Attachment')
                                        </button>
                                        <button class="btn btn--primary btn-large flex-fill" type="submit"
                                            name="replayTicket" value="1"><i class="la la-fw la-lg la-reply"></i>
                                            @lang('Reply')
                                        </button>
                                    </div>
                                </div>

                                <div class="row fileUploadsContainer">
                                </div>
                            </div>
                        </form>
                    </x-admin.ui.card.body>
                </x-admin.ui.card>
            </div>
        </x-admin.permission_check>
        @foreach ($messages as $message)
            <div class="col-12">
                @if ($message->admin_id == 0)
                    <x-admin.ui.card class="border--warning">
                        <x-admin.ui.card.header class="d-flex justify-content-between gap-2 flex-wrap align-items-center">
                            <div>
                                @if ($ticket->user_id != null)
                                    <h4 class="card-title">
                                        <a
                                            href="{{ route('admin.users.detail', $ticket->user_id) }}">{{ $ticket->name }}</a>
                                        <i class="fa-solid fa-arrow-down"></i>
                                    </h4>
                                @else
                                    <h4 class="card-title">{{ $ticket->name }}</h4>
                                @endif
                                <small class="text--info">
                                    @lang('Posted on') {{ showDateTime($message->created_at, 'l, dS F Y @ h:i a') }}
                                </small>
                            </div>
                            <x-admin.permission_check permission="close tickets">
                                <button class="btn btn--danger  confirmationBtn" data-question="@lang('Are you sure to delete this message?')"
                                    data-action="{{ route('admin.ticket.delete', $message->id) }}">
                                    <i class="la la-trash"></i>
                                    @lang('Delete')
                                </button>
                            </x-admin.permission_check>
                        </x-admin.ui.card.header>
                        <x-admin.ui.card.body>
                            <p>{{ $message->message }}</p>
                            @if ($message->attachments->count() > 0)
                                <div class="my-3">
                                    @foreach ($message->attachments as $k => $image)
                                        <a href="{{ route('admin.ticket.download', encrypt($image->id)) }}"
                                            class="fw-semibold">
                                            <i class="las la-file"></i> @lang('Attachment')
                                            {{ ++$k }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </x-admin.ui.card.body>
                    </x-admin.ui.card>
                @else
                    <x-admin.ui.card>
                        <x-admin.ui.card.header class="d-flex justify-content-between gap-2 flex-wrap align-items-center">
                            <div>
                                <h4 class="card-title">
                                    {{ @$message->admin->name }} -
                                    <span class="text-muted">@lang('Staff')</span>
                                </h4>
                                <small class="text--info">
                                    @lang('Posted on') {{ showDateTime($message->created_at, 'l, dS F Y @ h:i a') }}
                                </small>
                            </div>
                            <button class="btn btn--danger  confirmationBtn" data-question="@lang('Are you sure to delete this message?')"
                                data-action="{{ route('admin.ticket.delete', $message->id) }}">
                                <i class="la la-trash"></i>
                                @lang('Delete')
                            </button>
                        </x-admin.ui.card.header>
                        <x-admin.ui.card.body>
                            <p>{{ $message->message }}</p>
                            @if ($message->attachments->count() > 0)
                                <div class="my-3">
                                    @foreach ($message->attachments as $k => $image)
                                        <a href="{{ route('admin.ticket.download', encrypt($image->id)) }}"
                                            class="fw-semibold">
                                            <i class="las la-file"></i> @lang('Attachment')
                                            {{ ++$k }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </x-admin.ui.card.body>
                    </x-admin.ui.card>
                @endif
            </div>
        @endforeach
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex gap-2 flex-wrap">
        <x-admin.permission_check permission="close tickets">
            @if ($ticket->status != Status::TICKET_CLOSE)
                <button class="btn btn--danger confirmationBtn " type="button" data-question="@lang('Are you want to close this support ticket?')"
                    data-action="{{ route('admin.ticket.close', $ticket->id) }}">
                    <i class="la la-times"></i> @lang('Close Ticket')
                </button>
            @endif
        </x-admin.permission_check>
        <x-back_btn route="{{ route('admin.ticket.index') }}" />
    </div>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.delete-message').on('click', function(e) {
                $('.message_id').val($(this).data('id'));
            })
            var fileAdded = 0;
            $('.addAttachment').on('click', function() {
                fileAdded++;
                if (fileAdded == 5) {
                    $(this).attr('disabled', true)
                }
                $(".fileUploadsContainer").append(`
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 removeFileInput">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="file" name="attachments[]" class="form-control" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                            <button type="button" class="input-group-text removeFile bg--danger border--danger text-white"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                </div>
                `)
            });
            $(document).on('click', '.removeFile', function() {
                $('.addAttachment').removeAttr('disabled', true)
                fileAdded--;
                $(this).closest('.removeFileInput').remove();
            });
        })(jQuery);
    </script>
@endpush
