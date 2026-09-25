@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Verify your KYC data by completing the simple form below.')</p>
            </div>
        </div>
        <div class="dashboard-container__body">
            <div class="information-wrapper">
                <div class="row">
                    <div class="col-xxl-8">
                        <form action="{{ route('user.kyc.submit') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <x-ovo-form identifier="act" identifierValue="kyc" />
                            <div class="form-group">
                                <button type="submit" class="btn btn--base w-100 btn-shadow">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        "use strict";
        (function($) {
            $(`input,select`).addClass('form-two');
        })(jQuery);
    </script>
@endpush
