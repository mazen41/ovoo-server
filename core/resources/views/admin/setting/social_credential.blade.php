@extends('admin.layouts.app')
@section('panel')
    <div class="row responsive-row">
        @foreach (gs('socialite_credentials') as $key => $credential)
            <div class="col-lg-4 col-sm-6">
                <x-admin.ui.card class="h-100">
                    <x-admin.ui.card.body class="position-relative">
                        <div class="extention-status">
                            @if (@$credential->status == Status::ENABLE)
                                <span class="badge badge--success">@lang('Enabled')</span>
                            @else
                                <span class="badge badge--warning">@lang('Disabled')</span>
                            @endif
                        </div>
                        <div class="flex-thumb-wrapper mb-3  align-items-center">
                            <div class="thumb">
                                <img src="{{ asset('assets/images/social/' . strtolower($key) . '.png') }}"
                                    class="thumb-img">
                            </div>
                            <span class="ms-2">{{ __(ucfirst($key)) }}</span>
                        </div>
                        <div class="mb-3">
                            <p>{{ __(@$credential->info) }}</p>
                        </div>
                        <div class="btn--group">
                            <button class="btn btn-outline--primary  editBtn"
                                data-client_id="{{ $credential->client_id }}"
                                data-client_secret="{{ $credential->client_secret }}" data-key="{{ $key }}"><i
                                    class="la la-tools"></i>
                                @lang('Configure')
                            </button>
                            <button type="button" class="btn  btn-outline--secondary helpBtn"
                                data-target-key="{{ $key }}">
                                <i class="la la-info"></i>@lang('Help')
                            </button>
                            @if (@$credential->status == Status::ENABLE)
                                <button class="btn btn-outline--danger  confirmationBtn"
                                    data-question="@lang('Are you sure that you want to disable this social login provider?')"
                                    data-action="{{ route('admin.setting.socialite.credentials.status.update', $key) }}">
                                    <i class="las la-eye-slash"></i> @lang('Disable')
                                </button>
                            @else
                                <button class="btn btn-outline--success  confirmationBtn"
                                    data-question="@lang('Are you sure that you want to enable this social login provider?')"
                                    data-action="{{ route('admin.setting.socialite.credentials.status.update', $key) }}">
                                    <i class="las la-eye"></i> @lang('Enable')
                                </button>
                            @endif
                        </div>
                    </x-admin.ui.card.body>
                </x-admin.ui.card>
            </div>
        @endforeach
    </div>

    <x-admin.ui.modal id="editModal">
        <x-admin.ui.modal.header>
            <h1 class="modal-title">@lang('Update Credential'): <span class="credential-name"></span></h1>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form method="POST">
                @csrf
                <div class="form-group">
                    <label>@lang('Client ID')</label>
                    <input type="text" class="form-control" name="client_id">
                </div>
                <div class="form-group">
                    <label>@lang('Client Secret')</label>
                    <input type="text" class="form-control" name="client_secret">
                </div>
                <div class="form-group">
                    <label>@lang('Callback URL')</label>
                    <div class="input-group ">
                        <input type="text" class="form-control callback" readonly>
                        <button type="button" class="input-group-text copyBtn" data-copy=""
                            title="@lang('Copy')">
                            <i class="las la-clipboard"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>

    <!-- Help -->
    <x-admin.ui.modal id="helpModal">
        <x-admin.ui.modal.header>
            <h1 class="modal-title">@lang('How to get') <span class="title-key"></span> @lang('credentials')?</h1>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>

        </x-admin.ui.modal.body>
    </x-admin.ui.modal>

    <x-confirmation-modal />
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).on('click', '.editBtn', function() {
                let modal = $('#editModal');
                let data = $(this).data();
                let route = "{{ route('admin.setting.socialite.credentials.update', '') }}";
                let callbackRoute = "{{ route('user.social.login.callback', '') }}";
                let callbackUrl=`${callbackRoute}/${data.key}`;

                modal.find('form').attr('action', `${route}/${data.key}`);
                modal.find('.credential-name').text(data.key);
                modal.find('[name=client_id]').val(data.client_id);
                modal.find('[name=client_secret]').val(data.client_secret);
                modal.find('.callback').val(callbackUrl);
                modal.find('.copyBtn').attr("data-copy",callbackUrl);
                modal.modal('show');
            });

            $(document).on('click', '.helpBtn', function() {
                var modal = $('#helpModal');
                let rules = '';
                let key = $(this).data('target-key');
                modal.find('.title-key').text(key);

                if (key == 'google') {

                    rules = `<ul class="list-group list-group-flush">
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Go to') <a href="https://console.developers.google.com" target="_blank">@lang('google developer console').</a></li>
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Click on Select a project than click on') <a href="https://console.cloud.google.com/projectcreate" target="_blank">@lang('New Project')</a>  @lang('and create a project providing the project name').</li>
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Click on') <a href="https://console.cloud.google.com/apis/credentials" target="_blank">@lang('credentials').</a></li>
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Click on create credentials and select') <a href="https://console.cloud.google.com/apis/credentials/oauthclient" target="_blank">@lang('OAuth client ID').</a></li>
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Click on') <a href="https://console.cloud.google.com/apis/credentials/consent" target="_blank">@lang('Configure Consent Screen').</a></li>
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Choose External option and press the create button'). </li>
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Please fill up the required informations for app configuration'). </li>
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Again click on') <a href="https://console.cloud.google.com/apis/credentials" target="_blank">@lang('credentials')</a> @lang('and select type as web application and fill up the required informations. Also don\'t forget to add redirect url and press create button'). </li>
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Finally you\'ve got the credentials. Please copy the Client ID and Client Secret and paste it in admin panel google configuration'). </li>
                    </ul>`;
                } else if (key == 'facebook') {
                    rules = ` <ul class="list-group list-group-flush">
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Go to') <a href="https://developers.facebook.com/" target="_blank">@lang('facebook developer')</a></li>
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Click on Get Started and create Meta Developer account').</li>
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Create an app by selecting Consumer option').</li>
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Click on Setup Facebook Login and select Web option').</li>
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Add site url').</li>
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Go to Facebook Login > Settings and add callback URL here').</li>
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Go to Setting > Basic and copy the credentials and paste to admin panel').</li>

                    </ul>`;
                } else if (key == 'linkedin') {
                    rules = `<ul class="list-group list-group-flush">
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Go to') <a href="https://developer.linkedin.com/" target="_blank">@lang('linkedin developer')</a>.</li>
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Click on create app and provide required information').</li>
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Click on Sign In with Linkedin > Request access').</li>
                        <li class="list-group-item ps-0 fs-14"><i class="las la-check-circle text--primary pe-1"></i> @lang('Click Auth option and copy the credentials and paste it to admin panel and don\'t forget to add redirect url here').</li>
                    </ul>`;
                }

                modal.find('.modal-body').html(rules);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .flex-thumb-wrapper .thumb {
            width: 50px;
            height: 50px;
        }

        .extention-status {
            position: absolute;
            right: 16px;
            top: 16px;
        }
    </style>
@endpush
