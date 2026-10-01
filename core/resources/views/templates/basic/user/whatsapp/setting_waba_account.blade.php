@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('On this page you’ll able to change access token for the WhatsApp Business Account. Make sure you have taken the access token from your')
                    <a target="_blank" href="https://developers.facebook.com/apps/">
                        <i class="la la-external-link"></i> @lang('Meta Dashboard')
                    </a>
                </p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <a href="{{ route('user.whatsapp.account.index') }}" class="btn btn--dark"><i class="las la-undo"></i>
                        @lang('Back')</a>
                    <button type="submit" form="whatsapp-meta-form" class="btn btn--base btn-shadow">
                        <i class="lab la-telegram"></i>
                        @lang('Update Token')
                    </button>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <form id="whatsapp-meta-form" method="POST"
                action="{{ route('user.whatsapp.account.setting.confirm', @$whatsappAccount->id) }}">
                @csrf
                <div class="row gy-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="label-two">@lang('Business Name')</label>
                            <input type="text" class="form--control form-two" name="business_name"
                                placeholder="@lang('Enter your business name')" value="{{ @$whatsappAccount->business_name }}" readonly
                                required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="label-two">@lang('WhatsApp Number')</label>
                            <input type="text" class="form--control form-two" name="whatsapp_number"
                                placeholder="@lang('Enter your WhatsApp number with country code')" value="{{ @$whatsappAccount->phone_number }}" readonly
                                required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="label-two">@lang('WhatsApp Business Account ID')</label>
                            <input type="text" class="form--control form-two" name="whatsapp_business_account_id"
                                placeholder="@lang('Enter business account ID')"
                                value="{{ @$whatsappAccount->whatsapp_business_account_id }}" readonly required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="label-two">@lang('WhatsApp Phone Number ID')</label>
                            <input type="text" class="form--control form-two" name="phone_number_id"
                                placeholder="@lang('Enter phone number ID')" value="{{ @$whatsappAccount->phone_number_id }}" readonly
                                required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="label-two">
                                @lang('Meta Access Token')
                            </label>
                            <i class="fas fa-info-circle text--info ms-1" data-toggle="tooltip" data-placement="top"
                                title="@lang('If you change the access token, the current token will be expired.')">
                            </i>
                            <input type="text" class="form--control form-two" name="meta_access_token"
                                placeholder="@lang('Enter your access token')" value="{{ @$whatsappAccount->access_token }}" required>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
@endsection

@push('topbar_tabs')
    @include('Template::partials.profile_tab')
@endpush



