@php
    $howItWorkContent = @getContent('how_it_work.content', true)->data_values;
    $howItWorkElements = @getContent('how_it_work.element', orderById: true);
@endphp
<section class="how-work-section  py-100">
    <div class="shape-bg"></div>
    <div class="shape-bg two"></div>
    <div class="shape-bg three"></div>
    <div class="shape-bg four"></div>
    <div class="container">
        <div class="section-heading">
            <h1 class="section-heading__title wow animationfadeUp" data-wow-delay="0.2s"> {{ __(@$howItWorkContent->heading) }} </h1>
            <p class="section-heading__desc wow animationfadeUp" data-wow-delay="0.4s"> {{ __(@$howItWorkContent->subheading) }} </p>
        </div>
        <div class="row gy-4">
            @foreach ($howItWorkElements as $howItWorkElement)
                <div class="col-lg-3 col-sm-6">
                    <div class="how-work-item wow animationfadeUp" data-wow-delay="0.2s">
                        <span class="how-work-item__icon">
                            @php echo @$howItWorkElement->data_values->step_icon @endphp
                        </span>
                        <div class="how-work-item__content">
                            <h5 class="how-work-item__title">
                                {{ __(@$howItWorkElement->data_values->title) }}
                            </h5>
                            <p class="how-work-item__desc"> {{ __(@$howItWorkElement->data_values->subtitle) }} </p>
                        </div>
                        @unless ($loop->last)
                            <div class="how-work-item__shape">
                                <img src="{{ getImage(@$activeTemplateTrue . 'images/arrow-shape.png') }}" alt="">
                            </div>
                        @endunless
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
