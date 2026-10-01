@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-xl-12">
            <x-admin.ui.card>
                <x-admin.ui.card.body>
                    <div class="fs-14 bg--info mb-3 p-3 rounded fw-500 text-white">
                        <span>
                            @lang('First of all, you have to create an in-app purchase product non-consumable in the Play Store. we assume that you already created some non-consumable products in the Play Store console now we will show the process of how you can set enough necessary processes to verify in-app purchases')
                        </span>
                    </div>
                    <h6 class="mb-2">
                        @lang('1. Enable APIs in Google Cloud Console')
                    </h6>
                    <div class="ms-3 mb-4">
                        <ul class="list-unstyled">
                            <li class="my-1 fs-14">
                                <i class="fas fa-check-circle text--info me-1"></i>
                                @lang('Go to ')
                                <a href="https://console.cloud.google.com/" target="_blank" class="text--info">
                                    @lang('Google Cloud Console') <i class="fas fa-external-link-alt text--small me-1"></i>
                                </a>
                                @lang('and create a new app, or select one')
                            </li>
                            <li class="my-1 fs-14">
                                <i class="fas fa-check-circle text--info me-1"></i>
                                @lang('Now go to the ')
                                <a href="https://console.cloud.google.com/apis/library/androidpublisher.googleapis.com"
                                    target="_blank" class="text--info">
                                    @lang('Google Play Android Developer API')<i class="fas fa-external-link-alt text--small ms-1"></i>
                                </a>
                                @lang('page and click on the enable button')
                            </li>
                            <li class="my-1 fs-14"><i class="fas fa-check-circle text--info me-1"></i>
                                @lang('Go to the')
                                <a href="https://console.cloud.google.com/apis/library/playdeveloperreporting.googleapis.com"
                                    target="_blank" class="text--info">
                                    @lang(' Google Play Developer Reporting API')<i class="fas fa-external-link-alt text--small ms-1"></i>
                                </a>
                                @lang(' page and click on the enable button')
                            </li>
                        </ul>
                    </div>
                    <h6 class="mb-2">@lang('2. Create a Service Account in the Google Cloud Console')</h6>
                    <div class="ms-3 mb-4">
                        <ul class=" list-unstyled">
                            <li class="my-1 fs-14">
                                <i class="fas fa-check-circle text--info me-1"></i>
                                @lang('Go to the ') <span class="fw-bold">
                                    @lang('Google Cloud console') <i class="fas fa-arrow-right"></i>
                                    @lang('IAM & Admin') <i class="fas fa-arrow-right"></i>
                                    <a href="https://console.cloud.google.com/projectselector2/iam-admin/serviceaccounts?supportedpurview=project"
                                        target="_blank" class="text--info">
                                        @lang('Service Accounts') <i class="fas fa-external-link-alt text--small"></i>
                                    </a>
                                </span>
                                @lang('page. Please use the same Google Cloud Project you used in the previous steps. Click the Create Service Account button')
                            </li>
                            <li class="my-1 fs-14">
                                <i class="fas fa-check-circle text--info me-1"></i>
                                @lang('Then a new popup will appear, just enter your service account name then a service account will auto-generate. Just copy the service id(email id) and click the create and continue button')
                            </li>
                            <li class="my-1 fs-14">
                                <i class="fas fa-check-circle text--info me-1"></i>
                                @lang('Now a new window will be visible, just click on the select a roll drop-down button. Select 2 roles Pub/Sub Admin and Monitoring Viewer. Click on the continue button, and then the done button')
                            </li>
                            <li class="my-1 fs-14">
                                <i class="fas fa-check-circle text--info me-1"></i>
                                @lang('Find the newly created account in the list and the actions click manage keys. Create a new JSON key and save it locally on your computer. And Upload it to the admin panel')
                            </li>
                        </ul>
                    </div>
                    <h6 class="mb-2 ">@lang('3. Grant Permissions in the Google Play Console')</h6>
                    <div class="ms-3">
                        <ul class="list-unstyled">
                            <li class="my-1 fs-14">
                                <i class="fas fa-check-circle text--info me-1"></i> @lang('Go to the')
                                <a href="https://play.google.com/console/u/0/developers/users-and-permissions"
                                    target="_blank" class="text--info">
                                    @lang('Users and Permissions') <i class="fas fa-external-link-alt text--small"></i>
                                </a>
                                @lang('page in the Google Play Console and click Invite new users')
                            </li>
                            <li class="my-1 fs-14">
                                <i class="fas fa-check-circle text--info me-1"></i>
                                @lang("Enter the email of the service account you've created in section 2 of this guide and make sure you select your app properly")
                            </li>
                            <li class="my-1 fs-14">
                                <i class="fas fa-check-circle text--info me-1"></i>
                                @lang('Check on below mentioned permission and click on apply button')
                                <ul class="ms-4 mt-2 list-unstyled">
                                    <li class="my-1 fs-14"> <i class="fas fa-check-circle text--info"></i>
                                        @lang('View app information (read only)')
                                    </li>
                                    <li class="my-1 fs-14"><i class="fas fa-check-circle text--info"></i>
                                        @lang('View financial data')
                                    </li>
                                    <li class="my-1 fs-14"><i class="fas fa-check-circle text--info"></i>
                                        @lang('Manage orders subscriptions')
                                    </li>
                                    <li class="my-1 fs-14"><i class="fas fa-check-circle text--info"></i>
                                        @lang('Manage store presence')
                                    </li>
                                </ul>
                                <p class="fw-bold my-3">
                                    @lang('Note: Changes may take up to 24 hours to take effect. However, there is an alternative method to expedite the process. Visit this ')
                                    <a href="https://stackoverflow.com/questions/43536904/google-play-developer-api-the-current-user-has-insufficient-permissions-to-pe/60691844#60691844"
                                        target="_blank" class="text--info">@lang('link')
                                        <i class="fas fa-external-link-alt text--small"></i>
                                    </a>
                                </p>
                            </li>
                        </ul>
                    </div>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>

    <x-admin.ui.modal id="appPurchaseModal">
        <x-admin.ui.modal.header>
            <h1 class="modal-title">@lang('Update Google Pay Credential')</h1>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="mt-2">@lang('File')</label>
                    <input type="file" class="form-control" name="file" accept=".json" required>
                    <small class="mt-3 text-muted">@lang('Supported Files: .json')</small>
                </div>
                <div class="form-group">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>
@endsection

@push('breadcrumb-plugins')
    <div class=" d-flex gap-2 flex-wrap ">
        <button class="btn btn-outline--primary updateBtn  flex-fill" data-bs-toggle="modal"
            data-bs-target="#appPurchaseModal" type="button">
            <i class="las la-upload"></i> @lang('Update File')
        </button>
        <a href="{{ route('admin.setting.app.purchase.file.download') }}"
            class="btn btn-outline--info updateBtn flex-fill   @if (!$fileExists) disabled @endif"
            @disabled(!$fileExists)>
            <i class="las la-download"></i> @lang('Download File')
        </a>
    </div>
@endpush
