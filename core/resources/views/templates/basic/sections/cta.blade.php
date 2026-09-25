@php
    $ctaContent = @getContent('cta.content', true)->data_values;
@endphp
<div class="cta-section">
    <div class="cta-section__shape">
        <img src="{{ frontendImage('cta', @$ctaContent->background_image) }}" alt="image">
    </div>
    <div class="shape-bg"></div>
    <div class="shape-bg two"></div>
    <div class="shape-bg three"></div>
    <div class="shape-bg four"></div>
    <div class="container">
        <div class="cta-wrapper">
            <div class="cta-section__shape-two">
                <img src="{{ frontendImage('cta', @$ctaContent->shape_image) }}" alt="image">
            </div>
            <div class="cta-wrapper__left">
                <div class="section-heading style-left">
                    <h1 class="section-heading__title wow animationfadeUp" data-wow-delay="0.2s"> {{ __(@$ctaContent->heading) }} </h1>
                    <p class="section-heading__desc wow animationfadeUp" data-wow-delay="0.4s"> {{ __(@$ctaContent->subheading) }} </p>
                </div>
                <div class="cta-wrapper__btn wow animationfadeUp" data-wow-delay="0.6s">
                    <a href="{{ @$ctaContent->button_url }} " class="btn btn--base">
                        {{ __(@$ctaContent->button_text) }}
                    </a>
                </div>
            </div>
            <div class="cta-wrapper__right">
                <div class="cta-wrapper__thumb wow animationfadeRight" data-wow-delay="0.4s">
                    <img src="{{ frontendImage('cta', @$ctaContent->wrapper_image) }}" alt="image">
                </div>
            </div>
        </div>
    </div>
</div>
