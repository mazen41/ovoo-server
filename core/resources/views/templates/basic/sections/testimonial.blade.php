@php
    $testimonialContent = @getContent('testimonial.content', true)->data_values;
    $testimonialElements = @getContent('testimonial.element', orderById: true);
@endphp
<section class="testimonials pb-100">
    <div class="shape-bg"></div>
    <div class="container">
        <div class="section-heading">
            <h1 class="section-heading__title wow animationfadeUp" data-wow-delay="0.2s" data-highlight="[-1]">{{ __(@$testimonialContent->heading) }}
            </h1>
            <p class="section-heading__desc wow animationfadeUp" data-wow-delay="0.4s">
                {{ __(@$testimonialContent->subheading) }}
            </p>
        </div>
        <div class="feedback-slider">
            @foreach ($testimonialElements as $testimonialElement)
                <div class="feedback-item">
                    <div class="feedback-left">
                        <div class="feedback-thumb">
                            <img src="{{ frontendImage('testimonial', @$testimonialElement->data_values->image) }}"
                                alt="image">
                        </div>
                    </div>
                    <div class="feedback-right">
                        <div class="feedback-content">
                            <p class="feedback-content__text">" {{ __(@$testimonialElement->data_values->review) }} "
                            </p>
                            <div class="feedback-content__bottom">
                                <h5 class="name text--base"> {{ __(@$testimonialElement->data_values->author_name) }}
                                </h5>
                                <span class="type"> {{ __(@$testimonialElement->data_values->author_designation) }}
                                </span>
                                <div class="feedback-content__rating">
                                    @for ($i = 1; $i <= $testimonialElement->data_values->ratings; $i++)
                                        <i class="las la-star"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
@endpush
