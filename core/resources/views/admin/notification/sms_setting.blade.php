@extends('admin.layouts.app')
@section('panel')
@php
$smsConfig = gs('sms_config');
@endphp
<div class="row">
    <div class="col-md-12">
        <x-admin.ui.card>
            <x-admin.ui.card.body>
                <form method="POST">
                    @csrf
                    <div class="form-group">
                        <label>@lang('Sms Send Method')</label>
                        <select name="sms_method" class="select2 form-control select2-100"
                            data-minimum-results-for-search="-1">
                            <option value="clickatell" @if (@$smsConfig->name == 'clickatell') selected @endif>
                                @lang('Clickatell')</option>
                            <option value="infobip" @if (@$smsConfig->name == 'infobip') selected @endif>
                                @lang('Infobip')
                            </option>
                            <option value="messageBird" @if (@$smsConfig->name == 'messageBird') selected @endif>
                                @lang('Message Bird')</option>
                            <option value="nexmo" @if (@$smsConfig->name == 'nexmo') selected @endif>
                                @lang('Nexmo')
                            </option>
                            <option value="smsBroadcast" @if (@$smsConfig->name == 'smsBroadcast') selected @endif>
                                @lang('Sms Broadcast')</option>
                            <option value="twilio" @if (@$smsConfig->name == 'twilio') selected @endif>
                                @lang('Twilio')
                            </option>
                            <option value="textMagic" @if (@$smsConfig->name == 'textMagic') selected @endif>
                                @lang('Text Magic')</option>
                            <option value="custom" @if (@$smsConfig->name == 'custom') selected @endif>
                                @lang('Custom API')
                            </option>
                        </select>
                    </div>
                    <div class="row mt-4 d-none configForm" id="clickatell">
                        <div class="col-md-12">
                            <h6 class="mb-2">@lang('Clickatell Configuration')</h6>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('API Key') </label>
                                <input type="text" class="form-control" placeholder="@lang('API Key')"
                                    name="clickatell_api_key" value="{{ @$smsConfig->clickatell->api_key }}">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 d-none configForm" id="infobip">
                        <div class="col-md-12">
                            <h6 class="mb-2">@lang('Infobip Configuration')</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Base Url') </label>
                                <input type="text" class="form-control" placeholder="@lang('Base Url')"
                                    name="infobip_baseurl" value="{{ @$smsConfig->infobip->baseurl }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Api Key') </label>
                                <input type="text" class="form-control" placeholder="@lang('Api Key')"
                                    name="infobip_apikey" value="{{ @$smsConfig->infobip->apikey }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('From Number') </label>
                                <input type="text" class="form-control" placeholder="@lang('From Number')"
                                    name="infobip_from" value="{{ @$smsConfig->infobip->from }}" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 d-none configForm" id="messageBird">
                        <div class="col-md-12">
                            <h6 class="mb-2">@lang('Message Bird Configuration')</h6>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('API Key') </label>
                                <input type="text" class="form-control" placeholder="@lang('API Key')"
                                    name="message_bird_api_key" value="{{ @$smsConfig->message_bird->api_key }}">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 d-none configForm" id="nexmo">
                        <div class="col-md-12">
                            <h6 class="mb-2">@lang('Nexmo Configuration')</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('API Key') </label>
                                <input type="text" class="form-control" placeholder="@lang('API Key')"
                                    name="nexmo_api_key" value="{{ @$smsConfig->nexmo->api_key }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('API Secret') </label>
                                <input type="text" class="form-control" placeholder="@lang('API Secret')"
                                    name="nexmo_api_secret" value="{{ @$smsConfig->nexmo->api_secret }}">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 d-none configForm" id="smsBroadcast">
                        <div class="col-md-12">
                            <h6 class="mb-2">@lang('Sms Broadcast Configuration')</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Username') </label>
                                <input type="text" class="form-control" placeholder="@lang('Username')"
                                    name="sms_broadcast_username" value="{{ @$smsConfig->sms_broadcast->username }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Password') </label>
                                <input type="text" class="form-control" placeholder="@lang('Password')"
                                    name="sms_broadcast_password" value="{{ @$smsConfig->sms_broadcast->password }}">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 d-none configForm" id="twilio">
                        <div class="col-md-12">
                            <h6 class="mb-2">@lang('Twilio Configuration')</h6>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Account SID') </label>
                                <input type="text" class="form-control" placeholder="@lang('Account SID')"
                                    name="account_sid" value="{{ @$smsConfig->twilio->account_sid }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Auth Token') </label>
                                <input type="text" class="form-control" placeholder="@lang('Auth Token')"
                                    name="auth_token" value="{{ @$smsConfig->twilio->auth_token }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('From Number') </label>
                                <input type="text" class="form-control" placeholder="@lang('From Number')" name="from"
                                    value="{{ @$smsConfig->twilio->from }}">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 d-none configForm" id="textMagic">
                        <div class="col-md-12">
                            <h6 class="mb-2">@lang('Text Magic Configuration')</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Username') </label>
                                <input type="text" class="form-control" placeholder="@lang('Username')"
                                    name="text_magic_username" value="{{ @$smsConfig->text_magic->username }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Apiv2 Key') </label>
                                <input type="text" class="form-control" placeholder="@lang('Apiv2 Key')"
                                    name="apiv2_key" value="{{ @$smsConfig->text_magic->apiv2_key }}">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 d-none configForm" id="custom">
                        <div class="col-md-12">
                            <h6 class="mb-2">@lang('Custom API')</h6>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('API URL') </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <select name="custom_api_method" class="method-select">
                                            <option value="get">@lang('GET')</option>
                                            <option value="post">@lang('POST')</option>
                                        </select>
                                    </span>
                                    <input type="text" class="form-control" name="custom_api_url"
                                        value="{{ @$smsConfig->custom->url }}" placeholder="@lang('API URL')">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class=" table-responsive table-responsive--sm mb-3">
                                    <table class="  table align-items-center table--light">
                                        <thead>
                                            <tr>
                                                <th>@lang('Short Code') </th>
                                                <th>@lang('Description')</th>
                                            </tr>
                                        </thead>
                                        {{-- blade-formatter-disable --}}
                                        <tbody class="list">
                                            <tr>
                                                <td><span class="copyBtn cursor-pointer"
                                                        data-copy="@{{message}}">@{{message}}</span></td>
                                                <td>@lang('Message')</td>
                                            </tr>
                                            <tr>
                                                <td><span class="copyBtn cursor-pointer"
                                                        data-copy="@{{number}}">@{{number}}</span></td>
                                                <td>@lang('Number')</td>
                                            </tr>
                                        </tbody>
                                        {{-- blade-formatter-enable --}}
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border--gray mb-3 ">
                                    <div class="card-header bg--gray d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">@lang('Headers')</h5>
                                        <button type="button" class="btn   addHeader btn--primary">
                                            <i class="la la-fw la-plus"></i>
                                            @lang('Add')
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="headerFields">
                                            @for ($i = 0; $i < count(gs('sms_config')->custom->headers->name); $i++)
                                                <div class="row mt-3">
                                                    <div class="col-md-5">
                                                        <input type="text" name="custom_header_name[]"
                                                            class="form-control"
                                                            value="{{ @$smsConfig->custom->headers->name[$i] }}"
                                                            placeholder="@lang('Headers Name')">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" name="custom_header_value[]"
                                                            class="form-control"
                                                            value="{{ @$smsConfig->custom->headers->value[$i] }}"
                                                            placeholder="@lang('Headers Value')">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button"
                                                            class="w-100 btn btn--danger btn-block removeHeader h-100"><i
                                                                class="las la-times"></i></button>
                                                    </div>
                                                </div>
                                                @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border--gray mb-3 ">
                                    <div class="card-header bg--gray d-flex justify-content-between align-items-center">
                                        <h5 class="card-title">@lang('Body')</h5>
                                        <button type="button" class="btn  border-white  addHeader border-white addBody">
                                            <i class="la la-fw la-plus"></i>@lang('Add')
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="bodyFields">
                                            @for ($i = 0; $i < count(gs('sms_config')->custom->body->name); $i++)
                                                <div class="row mt-3">
                                                    <div class="col-md-5">
                                                        <input type="text" name="custom_body_name[]"
                                                            class="form-control"
                                                            value="{{ @$smsConfig->custom->body->name[$i] }}"
                                                            placeholder="@lang('Body Name')">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" name="custom_body_value[]"
                                                            value="{{ @$smsConfig->custom->body->value[$i] }}"
                                                            class="form-control" placeholder="@lang('Body Value')">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button"
                                                            class="w-100 btn btn--danger btn-block removeBody h-100"><i
                                                                class="las la-times"></i></button>
                                                    </div>
                                                </div>
                                                @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <x-admin.ui.btn.submit />
                </form>
            </x-admin.ui.card.body>
        </x-admin.ui.card>
    </div>
</div>

<x-admin.ui.modal id="testSMSModal">
    <x-admin.ui.modal.header>
        <h1 class="modal-title">@lang('Test SMS Setup')</h1>
        <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
            <i class="las la-times"></i>
        </button>
    </x-admin.ui.modal.header>
    <x-admin.ui.modal.body>
        <form action="{{ route('admin.setting.notification.sms.test') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>@lang('Sent to') </label>
                <input type="text" name="mobile" class="form-control" placeholder="@lang('Mobile')">
            </div>
            <input type="hidden" name="id">
            <div class="form-group">
                <x-admin.ui.btn.modal />
            </div>
        </form>
    </x-admin.ui.modal.body>
</x-admin.ui.modal>
@endsection
@push('breadcrumb-plugins')
<button type="button" data-bs-target="#testSMSModal" data-bs-toggle="modal" class="btn btn--primary "> <i
        class="fa-regular fa-paper-plane"></i> @lang('Send Test SMS')</button>
@endpush


@push('style')
<style>
    .method-select {
        padding: 2px 7px;
    }
</style>
@endpush


@push('script')
<script>
    (function($) {
            "use strict";



            var method = '{{ @$smsConfig->name }}';

            if (!method) {
                method = 'clickatell';
            }

            smsMethod(method);
            $('select[name=sms_method]').on('change', function() {
                var method = $(this).val();
                smsMethod(method);
            });

            function smsMethod(method) {
                $('.configForm').addClass('d-none');
                if (method != 'php') {
                    $(`#${method}`).removeClass('d-none');
                }
            }

            $('.addHeader').on('click', function() {
                var html = `
                    <div class="row mt-3">
                        <div class="col-md-5">
                            <input type="text" name="custom_header_name[]" class="form-control" placeholder="@lang('Headers Name')">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="custom_header_value[]" class="form-control" placeholder="@lang('Headers Value')">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="w-100 btn btn--danger btn-block removeHeader h-100"><i class="las la-times"></i></button>
                        </div>
                    </div>
                `;
                $('.headerFields').append(html);

            })
            $(document).on('click', '.removeHeader', function() {
                $(this).closest('.row').remove();
            })

            $('.addBody').on('click', function() {
                var html = `
                    <div class="row mt-3">
                        <div class="col-md-5">
                            <input type="text" name="custom_body_name[]" class="form-control" placeholder="@lang('Body Name')">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="custom_body_value[]" class="form-control" placeholder="@lang('Body Value')">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="w-100 btn btn--danger btn-block removeBody h-100"><i class="las la-times"></i></button>
                        </div>
                    </div>
                `;
                $('.bodyFields').append(html);

            })
            $(document).on('click', '.removeBody', function() {
                $(this).closest('.row').remove();
            })

            $('select[name=custom_api_method]').val('{{ @$smsConfig->custom->method }}');

        })(jQuery);
</script>
@endpush