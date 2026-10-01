@php
    $breadcrumbContent = @getContent('breadcrumb.content',true)->data_values;
@endphp

<section class="breadcrumb"
    style="background-image: url('{{ frontendImage('breadcrumb',@$breadcrumbContent->background_image) }}')">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="breadcrumb__wrapper">
                    <h2 class="breadcrumb__title mb-0 wow animationfadeUp" data-wow-delay="0.2s">
                        {{  __($pageTitle) }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
</section>
