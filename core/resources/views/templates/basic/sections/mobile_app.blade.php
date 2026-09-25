@php
    $mobileAppContent         = @getContent('mobile_app.content', true)->data_values;
    $mobileAppSectionElements = @getContent('mobile_app.element', orderById: true);
@endphp

<section class="customer-management-section pb-100">
    <div class="shape-bg"></div>
    <div class="container">
        <div class="section-heading">
            <h1 class="section-heading__title wow animationfadeUp" data-wow-delay="0.2s"> {{ __(@$mobileAppContent->heading) }} </h1>
            <p class="section-heading__desc wow animationfadeUp" data-wow-delay="0.4s"> {{ __(@$mobileAppContent->subheading) }} </p>
        </div>
        <div class="management-wrapper">
            <div class="shape-bg"></div>
            <div class="shape-bg two"></div>
            <div class="shape-bg three"></div>
            <div class="shape-bg four"></div>
            <div class="management-wrapper__left">
                <h4 class="title text--base wow animationfadeUp" data-wow-delay="0.6s"> {{ __(@$mobileAppContent->benefit_title) }} </h4>
                <ul class="text-list wow animationfadeUp" data-wow-delay="0.8s">
                    @foreach ($mobileAppSectionElements as $customerManagementElement)
                        <li class="text-list__item">
                            <span class="text-list__icon"> <i class="las la-check"></i> </span>
                            {{ __(@$customerManagementElement->data_values->benefits) }}
                        </li>
                    @endforeach
                </ul>
                <div class="management-wrapper__bottom wow animationfadeUp" data-wow-delay="0.8s">
                    <h4 class="title"> {{ __(@$mobileAppContent->bottom_text) }} </h4>
                    <div class="download-wrapper">
                        <p class="download-wrapper__title"> {{ __(@$mobileAppContent->download_title) }} </p>
                        <ul class="download-list">
                            <li>
                                <a href="{{ @$mobileAppContent->google_store_link }}" target="_blank"
                                    class="download-list__link"> <img
                                        src="{{ $activeTemplateTrue . 'images/google.png' }}" alt=""></a>
                            </li>
                            <li>
                                <a href="{{ @$mobileAppContent->apple_store_link }}" target="_blank"
                                    class="download-list__link"> <img
                                        src="{{ $activeTemplateTrue . 'images/apple.png' }}" alt=""></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="management-wrapper__right">
                <div class="management-wrapper__thumb wow animationfadeRight" data-wow-delay="0.4s">
                    <img src="{{ frontendImage('mobile_app', @$mobileAppContent->image) }}"
                        alt="image">
                </div>
            </div>
        </div>
    </div>
</section>
