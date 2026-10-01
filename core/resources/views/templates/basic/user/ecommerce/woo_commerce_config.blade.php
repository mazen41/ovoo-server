@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="alert alert--info alert-dismissible mb-3 template-requirements" role="alert">
        <div class="alert__content">
            <h4 class="alert__title"><i class="las la-info-circle"></i> @lang('How to Obtain Your WooCommerce API Keys')</h4>
            <ul class="ms-4">
                <li class="mb-0 text-dark">@lang('Log in to your WordPress admin dashboard and navigate to WooCommerce settings.')</li>
                <li class="mb-0 text-dark">@lang('Select the Advanced tab, then REST API, and click on "Add key".')</li>
                <li class="mb-0 text-dark">@lang('Provide a description, assign a user and appropriate permissions, then generate the API key.')</li>
                <li class="mb-0 text-dark">@lang('Copy both the Consumer Key and Consumer Secret for later use.')</li>
                <li class="mb-0 text-dark">@lang('Your store URL typically follows the format: https://yourstore.com.')</li>
            </ul>
        </div>
    </div>
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __($pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Configure your WooCommerce store settings easily using the form below.')
                    <a target="_blank" href="https://woocommerce.com/document/woocommerce-rest-api/"><i
                            class="la la-external-link"></i> @lang('WooCommerce Documentation')</a>
                </p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <button type="submit" form="woo-commerce-config-form" class="btn btn--base btn-shadow">
                        <i class="lab la-telegram"></i>
                        @lang('Submit')
                    </button>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <form id="woo-commerce-config-form" method="POST"
                action="{{ route('user.ecommerce.woocommerce.config.store') }}">
                @csrf
                <div class="row gy-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="label-two">@lang('Domain Name')</label>
                            <input type="text" class="form--control form-two" name="domain_name"
                                placeholder="*********************" value="{{ @$ecommerceConfig?->config?->domain_name }}"
                                required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="label-two">@lang('Consumer Key')</label>
                            <input type="text" class="form--control form-two" name="consumer_key"
                                placeholder="*********************" value="{{ @$ecommerceConfig?->config?->consumer_key }}"
                                required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="label-two">@lang('Consumer Secret')</label>
                            <input type="text" class="form--control form-two" name="consumer_secret"
                                placeholder="*********************"
                                value="{{ @$ecommerceConfig?->config?->consumer_secret }}" required>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
