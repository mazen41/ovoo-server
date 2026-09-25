@php
    $url = urlencode(url()->current());
    $title = urlencode($blog->data_values->title);
@endphp
@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="blog-detials py-100 ">
        <div class="container">
            <div class="row gy-5 justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <div class="blog-details">
                        <div class="blog-details__thumb">
                            <img src="{{ frontendImage('blog', @$blog->data_values->image) }}" class="fit-image"
                                alt="">
                        </div>
                        <div class="blog-details__content">
                            <div class="blog-details__top">
                                <h3 class="blog-details__title"> {{ __(@$blog->data_values->title) }} </h3>
                                <ul class="content-list">
                                    <li class="content-list__item"> {{ showDateTime($blog->created_at, 'd M, Y') }} </li>
                                </ul>
                            </div>
                            <div class="content-item">
                                @php echo @$blog->data_values->description @endphp
                            </div>
                            <div class="fb-comments" data-href="{{ url()->current() }}" data-numposts="5"></div>
                            <div class="blog-details__share ">
                                <h5 class="social-share__title mb-4"> @lang('Share this Blog') </h5>
                                <ul class="social-list">
                                    <li class="social-list__item">
                                        <a target="_blank"
                                            href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}"
                                            class="social-list__link flex-center"><i class="fab fa-facebook-f"></i>
                                        </a>
                                    </li>
                                    <li class="social-list__item">
                                        <a target="_blank"
                                            href="https://twitter.com/intent/tweet?url={{ $url }}&text={{ $title }}"
                                            class="social-list__link flex-center active"> <i class="fab fa-twitter"></i>
                                        </a>
                                    </li>
                                    <li class="social-list__item">
                                        <a target="_blank"
                                            href="https://www.linkedin.com/sharing/share-offsite/?url={{ $url }}"
                                            class="social-list__link flex-center"> <i class="fab fa-linkedin-in"></i>
                                        </a>
                                    </li>
                                    <li class="social-list__item">
                                        <a target="_blank"
                                            href="https://pinterest.com/pin/create/button/?url={{ $url }}&description={{ $title }}"
                                            class="social-list__link flex-center"> <i class="fab fa-pinterest"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="blog-bottom-section pb-100 mb-100">
        <div class="container">
            <h4 class="title"> @lang('More Blogs') </h4>
            <div class="blog-wrapper">
                <div class="blog-slider">
                    @foreach ($allBlogs as $blogElement)
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
            </div>
        </div>
    </section>
@endsection
@push('fbComment')
    @php echo loadExtension('fb-comment') @endphp
@endpush

@push('style')
    <style>
        .content-item p {
            color: hsl(var(--body-color)/0.8);
        }
    </style>
@endpush


@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
@endpush
