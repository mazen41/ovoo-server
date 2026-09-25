@php
    $blogContent = @getContent('blog.content', true)->data_values;
    $blogElements = @getContent('blog.element', orderById: true);
@endphp
<section class="blog py-100 banner-bg">
    <div class="container">
        <div class="section-heading">
            <h2 class="section-heading__title wow animationfadeUp" data-wow-delay="0.2s"> {{ __(@$blogContent->heading) }}
            </h2>
            <p class="section-heading__desc wow animationfadeUp" data-wow-delay="0.4s">
                {{ __(@$blogContent->subheading) }}t </p>
        </div>
        <div class="blog-wrapper wow animationfadeUp" data-wow-delay="0.6s">
            <div class="blog-slider">
                @foreach ($blogElements as $blogElement)
                    <div class="blog-item">
                        <div class="blog-item__thumb">
                            <a href="{{ route('blog.details', $blogElement->slug) }}" class="blog-item__thumb-link">
                                <img src="{{ frontendImage('blog', 'thumb_' . $blogElement->data_values->image) }}"
                                    class="fit-image" alt="">
                            </a>
                        </div>
                        <div class="blog-item__content">
                            <h5 class="blog-item__title">
                                <a href="{{ route('blog.details', $blogElement->slug) }}"
                                    class="blog-item__title-link border-effect">{{ __(@$blogElement->data_values->title) }}</a>
                            </h5>
                            <p class="blog-item__desc"> @php echo strLimit(strip_tags(__(@$blogElement->data_values->description)), 100) @endphp </p>
                            <div class="blog-item__bottom">
                                <ul class="content-list">
                                    <li class="content-list__item">
                                        {{ showDateTime($blogElement->created_at, 'd M, Y') }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="blog-wrapper__bottom">
                <a href="{{ route('blogs') }}" class="btn btn--base btn--lg"> @lang('Visit More Blog') </a>
            </div>
        </div>
    </div>
</section>

@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
@endpush
