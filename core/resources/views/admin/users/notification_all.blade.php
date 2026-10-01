@extends('admin.layouts.app')
@section('panel')
    @php
        $sessionData = session('SEND_NOTIFICATION') ?? [];
        $viaName = $sessionData['via'] ?? 'email';
        $viaText = @$sessionData['via'] == 'push' ? 'Push notification ' : ucfirst($viaName);
    @endphp

    @empty(!$sessionData)
        <div class="notification-data-and-loader">
            <div class="row  mb-4 justify-content-center">
                <div class="col-sm-7">
                    <div class="row gy-4 justify-content-center">
                        @include('admin.users.notification_widget')
                    </div>
                </div>
            </div>
        </div>
    @endempty

    <div class="row @empty(!$sessionData) d-none @endempty">
        <div class="col-xl-12">
            <x-admin.ui.card>
                <x-admin.ui.card.body>
                    <form class="notify-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="via" value="{{ $viaName }}">
                        <div class="row">
                            <div class="col-12">
                                <div class=" form-group d-flex gap-3 gap-md-5 flex-wrap">
                                    @gs('en')
                                        <div>
                                            <label class="form-label">@lang('Email')</label>
                                            <div class="form-check form-switch form--switch pl-0 form-switch-success">
                                                <input class="form-check-input" type="radio" role="switch" name="via"
                                                    value="email" @checked($viaName == 'email')>
                                            </div>
                                        </div>
                                    @endgs
                                    @gs('sn')
                                        <div>
                                            <label class="form-label">@lang('Sms')</label>
                                            <div class="form-check form-switch form--switch pl-0 form-switch-success">
                                                <input class="form-check-input" type="radio" role="switch" name="via"
                                                    value="sms" @checked($viaName == 'sms')>
                                            </div>
                                        </div>
                                    @endgs
                                    @gs('pn')
                                        <div>
                                            <label class="form-label">@lang('Firebase')</label>
                                            <div class="form-check form-switch form--switch pl-0 form-switch-success">
                                                <input class="form-check-input" type="radio" role="switch" name="via"
                                                    value="push" @checked($viaName == 'push')>
                                            </div>
                                        </div>
                                    @endgs
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Being Sent To') </label>
                                    <select class="form-control select2 select2-100" name="being_sent_to" required
                                        data-minimum-results-for-search="1">
                                        @foreach ($notifyToUser as $key => $toUser)
                                            <option value="{{ $key }}" @selected(old('being_sent_to', @$sessionData['being_sent_to']) == $key)>
                                                {{ __($toUser) }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text--info d-none userCountText mt-2 d-block"> <i class="las la-info-circle"></i>
                                        <strong class="userCount">0</strong> @lang('active users found to send the notification')</small>
                                </div>
                                <div class="input-append">
                                </div>
                            </div>
                            <div class="form-group col-md-12 subject-wrapper">
                                <label>@lang('Subject') <span class="text--danger">*</span> </label>
                                <input type="text" class="form-control" placeholder="@lang('Subject / Title')" name="subject"
                                    value="{{ old('subject', @$sessionData['subject']) }}">
                            </div>
                            <div class="form-group col-md-12 push-notification-file d-none">
                                <label>@lang('Image (optional)') </label>
                                <input type="file" class="form-control" name="image" accept=".png,.jpg,.jpeg">
                                <small class="mt-3 text-muted"> @lang('Supported Files'):<b>@lang('.png, .jpg, .jpeg')</b> </small>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Message') <span class="text--danger">*</span> </label>
                                    <textarea class="form-control editor" id="editor" name="message" rows="10">{{ old('message', @$sessionData['message']) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Start Form') </label>
                                            <input class="form-control" name="start"
                                                value="{{ old('start', @$sessionData['start']) }}" type="number"
                                                placeholder="@lang('Start form user id. e.g. 1')" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Per Batch') </label>
                                            <div class="input-group input--group">
                                                <input class="form-control" name="batch"
                                                    value="{{ old('batch', @$sessionData['batch']) }}" type="number"
                                                    placeholder="@lang('How many user')" required>
                                                <span class="input-group-text">
                                                    @lang('USER')
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Cooling Period') </label>
                                            <div class="input-group input--group">
                                                <input class="form-control" name="cooling_time"
                                                    value="{{ old('cooling_time', @$sessionData['batch']) }}" type="number"
                                                    placeholder="@lang('Waiting time')" required>
                                                <span class="input-group-text">
                                                    @lang('SECONDS')
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
        let formSubmit = false;

        (function($) {
            "use strict"

            $('select[name=being_sent_to]').on('change', function(e) {
                let methodName = $(this).val();
                if (!methodName) return;
                getUserCount(methodName);
                methodName = methodName.toUpperCase();

                if (methodName == 'SELECTEDUSERS') {
                    $('.input-append').html(`
                    <div class="form-group position-relative" id="user_list_wrapper">
                        <label class="required form-label">@lang('Select User')</label>
                        <select name="user[]"  class="form-control select2-100" id="user_list" required multiple >
                            <option disabled>@lang('Select One')</option>
                        </select>
                    </div>
                    `);
                    fetchUserList();
                    return;
                }
                if (methodName == 'TOPDEPOSITEDUSERS') {
                    $('.input-append').html(`
                    <div class="form-group">
                        <label class="required form-label">@lang('Number Of Top Deposited User')</label>
                        <input class="form-control" type="number" name="number_of_top_deposited_user" >
                    </div>
                    `);
                    return;
                }

                if (methodName == 'NOTLOGINUSERS') {
                    $('.input-append').html(`
                    <div class="form-group">
                        <label class="required form-label">@lang('Number Of Days')</label>
                        <div class="input-group input--group">
                            <input class="form-control" value="{{ old('number_of_days', @$sessionData['number_of_days']) }}" type="number" name="number_of_days" >
                            <span class="input-group-text">@lang('Days')</span>
                        </div>
                    </div>
                    `);
                    return;
                }

                $('.input-append').empty();

            }).change();

            function fetchUserList() {

                $('.row #user_list').select2({
                    ajax: {
                        url: "{{ route('admin.users.list') }}",
                        type: "get",
                        dataType: 'json',
                        delay: 1000,
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page,
                            };
                        },
                        processResults: function(response, params) {
                            params.page = params.page || 1;
                            let data = response.users.data;
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.email,
                                        id: item.id
                                    }
                                }),
                                pagination: {
                                    more: response.more
                                }
                            };
                        },
                        cache: false,
                    },
                    dropdownParent: $('#user_list_wrapper')
                });

            }


            function getUserCount(methodName) {
                var methodNameUpper = methodName.toUpperCase();
                if (methodNameUpper == 'SELECTEDUSERS' || methodNameUpper == 'ALLUSERS' || methodNameUpper ==
                    'TOPDEPOSITEDUSERS' ||
                    methodNameUpper == 'NOTLOGINUSERS') {
                    $('.userCount').text(0);
                    $('.userCountText').addClass('d-none');
                    return;
                }
                var route = "{{ route('admin.users.segment.count', ':methodName') }}"
                route = route.replace(':methodName', methodName)
                $.get(route, function(response) {
                    $('.userCount').text(response);
                    $('.userCountText').removeClass('d-none');
                });
            }

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

            $(".notify-form").on("submit", function(e) {
                formSubmit = true;
            });

            @empty(!$sessionData)
                $(document).ready(function() {
                    const coalingTimeOut = setTimeout(() => {
                        let coalingTime = Number("{{ $sessionData['cooling_time'] }}");

                        $("#animate-circle").css({
                            "animation": `countdown ${coalingTime}s linear infinite forwards`
                        });

                        let $coalingCountElement = $('.coaling-time-count');
                        let $coalingLoaderElement = $(".coaling-loader");

                        $coalingCountElement.text(coalingTime);

                        const coalingIntVal = setInterval(function() {
                            coalingTime--;
                            $coalingCountElement.text(coalingTime);
                            if (coalingTime <= 0) {
                                formSubmit = true;
                                $("#animate-circle").css({
                                    "animation": `unset`
                                });
                                clearInterval(coalingIntVal);
                                clearTimeout(coalingTimeOut);
                                $(".notify-form").submit();
                            }
                        }, 1000);

                    }, 1000);
                });
            @endif

        })(jQuery);

        @if (!empty(@$sessionData) && @request()->email_sent && @request()->email_sent = 'yes')
            window.addEventListener('beforeunload', function(event) {
                if (!formSubmit) {
                    event.preventDefault();
                    event.returnValue = '';
                    var confirmationMessage = 'Are you sure you want to leave this page?';
                    (event || window.event).returnValue = confirmationMessage;
                    return confirmationMessage;
                }
            });
        @endif
    </script>
@endpush


@push('style')
    <style>
        .countdown {
            position: relative;
            height: 100px;
            width: 100px;
            text-align: center;
            margin: 0 auto;
        }

        .coaling-time {
            color: yellow;
            position: absolute;
            z-index: 999999;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 30px;
        }

        .coaling-loader svg {
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            transform: rotateY(-180deg) rotateZ(-90deg);
            position: relative;
            z-index: 1;
        }

        .coaling-loader svg circle {
            stroke-dasharray: 314px;
            stroke-dashoffset: 0px;
            stroke-linecap: round;
            stroke-width: 6px;
            stroke: #4634ff;
            fill: transparent;

        }

        .coaling-loader .svg-count {
            width: 100px;
            height: 100px;
            position: relative;
            z-index: 1;
        }

        .coaling-loader .svg-count::before {
            content: '';
            position: absolute;
            outline: 5px solid #f3f3f9;
            z-index: -1;
            width: calc(100% - 16px);
            height: calc(100% - 16px);
            left: 8px;
            top: 8px;
            z-index: -1;
            border-radius: 100%
        }

        .coaling-time-count {
            color: #4634ff;
        }

        @keyframes countdown {
            from {
                stroke-dashoffset: 0px;
            }

            to {
                stroke-dashoffset: 314px;
            }
        }
    </style>
@endpush
