@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-xl-12">
            <x-admin.ui.card>
                <x-admin.ui.card.body>
                    <form action="{{ route('admin.users.notification.single', $user->id) }}" class="notificationForm"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class=" form-group d-flex gap-5 flex-wrap">
                                    @gs('en')
                                        <div>
                                            <label>@lang('Email')</label>
                                            <div class="form-check form-switch form--switch pl-0 form-switch-success">
                                                <input class="form-check-input" type="radio" role="switch" name="via"
                                                    value="email">
                                            </div>
                                        </div>
                                    @endgs
                                    @gs('sn')
                                        <div>
                                            <label>@lang('Sms')</label>
                                            <div class="form-check form-switch form--switch pl-0 form-switch-success">
                                                <input class="form-check-input" type="radio" role="switch" name="via"
                                                    value="sms">
                                            </div>
                                        </div>
                                    @endgs
                                    @gs('pn')
                                        <div>
                                            <label>@lang('Firebase')</label>
                                            <div class="form-check form-switch form--switch pl-0 form-switch-success">
                                                <input class="form-check-input" type="radio" role="switch" name="via"
                                                    value="push">
                                            </div>
                                        </div>
                                    @endgs
                                </div>
                            </div>
                            <div class="form-group col-md-12 subject-wrapper">
                                <label>@lang('Subject') </label>
                                <input type="text" class="form-control" placeholder="@lang('Subject / Title')" name="subject">
                            </div>
                            <div class="form-group col-md-12 push-notification-file d-none">
                                <label>@lang('Image (optional)') </label>
                                <input type="file" class="form-control" accept=".png,.jpg,.jpeg" name="image">
                                <small class="mt-3 text-muted"> @lang('Supported Files'):<b>@lang('.png, .jpg, .jpeg')</b> </small>
                            </div>
                            <div class="form-group col-md-12">
                                <label>@lang('Message') </label>
                                <textarea name="message" rows="10" class="form-control editor"></textarea>
                            </div>
                            <div class="col-12">
                                <x-admin.ui.btn.submit />
                            </div>
                        </div>
                    </form>
                </x-admin.ui.card.body>
            </x-admin.ui.card>

        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/summernote-lite.min.js') }}"></script>
@endpush
@push('style-lib')
    <link href="{{ asset('assets/global/css/summernote-lite.min.css') }}" rel="stylesheet">
@endpush

@push('script')
    <script>
        (function($) {
            "use strict"

            $('input[name=via]').on('change', function() {

                $(this).addClass('active');
                const via = $(this).val();

                if (via == 'email') {
                    $('.editor').summernote({
                        height: 200
                    });
                } else {
                    $('.editor').summernote('destroy');
                    $('.editor').val("");
                }

                if (via == 'push') {
                    $('.push-notification-file').removeClass('d-none');
                } else {
                    $('.push-notification-file').addClass('d-none');
                    $('.push-notification-file [type=file]').val('');
                }

                if (via == 'push' || via == 'email') {
                    $('.subject-wrapper').removeClass('d-none');
                } else {
                    $('.subject-wrapper').addClass('d-none')
                }
                $('.subject-wrapper').find('input').val('');
            });
        })(jQuery);
    </script>
@endpush
