@php
    $bannerContent = @getContent('banner.content',true)->data_values;
@endphp
<section class="banner-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="banner-content">
                    <h1 class="banner-content__title wow animationfadeUp" data-wow-delay="0.2s">{{ __(@$bannerContent->heading) }}</h1>
                    <p class="banner-content__desc wow animationfadeUp" data-wow-delay="0.4s"> {{ __(@$bannerContent->subheading) }} </p>
                    <div class="banner-content__button wow animationfadeUp" data-wow-delay="0.6s">
                        <a href="{{ @$bannerContent->button_url }}" class="btn--base-two btn"> {{ __(@$bannerContent->button_text) }} </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="banner-thumb wow animationfadeUp" data-wow-delay="0.9s">
            <img src="{{ frontendImage('banner',@$bannerContent->image) }}" alt="">
        </div>
    </div>
</section>