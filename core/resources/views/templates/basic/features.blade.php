@php
    $featurePageContent = @getContent('feature_page.content', true)->data_values;
    $featurePageElements = @getContent('feature_page.element', orderById: true);
@endphp
@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="key-feature-section banner-bg py-100">
        <div class="container">
            <div class="section-heading">
                <h1 class="section-heading__title wow animationfadeUp" data-wow-delay="0.2s">{{ __(@$featurePageContent->heading) }}</h1>
                <p class="section-heading__desc wow animationfadeUp" data-wow-delay="0.4s">{{ __(@$featurePageContent->subheading) }}</p>
            </div>
            <div class="key-feature-container">
                @foreach ($featurePageElements as $featurePageElement)
                    <div class="key-feature-wrapper wow animationfadeUp" data-wow-delay="0.6s">
                        <div class="key-feature-wrapper__content">
                            <p class="key-feature-wrapper__top">
                                <span class="icon"> <i class="fa-solid fa-inbox"></i> </span>
                                {{ __(@$featurePageElement->data_values->title) }}
                            </p>
                            <h3 class="key-feature-wrapper__title">{{ __(@$featurePageElement->data_values->heading) }}</h3>
                            <p class="key-feature-wrapper__desc">{{ __(@$featurePageElement->data_values->description) }}</p>
                            <div class="why-choose-us">
                                @php
                                    echo @$featurePageElement->data_values->benefits;
                                @endphp
                            </div>
                        </div>
                        <div class="key-feature-wrapper__thumb">
                            <img src="{{ frontendImage('feature_page', @$featurePageElement->data_values->image, '635x340') }}"
                                alt="img">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
