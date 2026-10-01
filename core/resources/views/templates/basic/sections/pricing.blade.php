@php
    @$pricingContent = @getContent('pricing.content', true)->data_values;
    @$user = auth()->user();
@endphp

<section class="pricing-section banner-bg pb-100">
    <div class="container">
        <div class="section-heading">
            <h1 class="section-heading__title wow animationfadeUp" data-wow-delay="0.2s">
                {{ __(@$pricingContent->heading) }}</h1>
            <p class="section-heading__desc wow animationfadeUp" data-wow-delay="0.4s">
                {{ __(@$pricingContent->subheading) }}</p>
        </div>
        <div class="pricing-card-top wow animationfadeUp" data-wow-delay="0.6s">
            <p class="pricing-card-top__text">@lang('Monthly')</p>
            <div class="form--switch">
                <input class="form-check-input" type="checkbox" role="switch" name="recurring_type" />
            </div>
            <p class="pricing-card-top__text">
                @lang('Yearly')
            </p>
        </div>
        <div class="row gy-4 justify-content-center">
            @include('Template::partials.pricing')
        </div>
    </div>
</section>



<x-purchase_modal :is_dark="true" />
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
