@php
    $featureContent = @getContent('feature.content', true)->data_values;
    $featureElements = @getContent('feature.element', orderById: true);
@endphp
<div class="feature-section pb-100">
    <div class="container">
        <div class="section-heading">
            <h1 class="section-heading__title wow animationfadeUp" data-wow-delay="0.2s"> {{ __(@$featureContent->heading) }} </h1>
            <p class="section-heading__desc wow animationfadeUp" data-wow-delay="0.4s"> {{ __(@$featureContent->subheading) }} </p>
        </div>
        <div class="row gy-4 justify-content-center">
            @foreach ($featureElements as $featureElement)
                <div class="col-lg-4 col-sm-6 wow animationfadeUp" data-wow-delay="0.6s">
                    <div class="feature-item">
                        <div class="feature-item__icon">
                            @php echo @$featureElement->data_values->feature_icon; @endphp
                        </div>
                        <h5 class="feature-item__title"> {{ __(@$featureElement->data_values->title) }} </h5>
                        <p class="feature-item__desc">{{ __(@$featureElement->data_values->description) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
