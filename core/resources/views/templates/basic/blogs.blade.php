@php
    $blogContent = @getContent('blog.content', true)->data_values;
@endphp

@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="blog-section  py-100">
        <div class="container">
            <div class="section-heading">
                <h1 class="section-heading__title wow animationfadeUp" data-wow-delay="0.2s">{{ __(@$blogContent->heading) }}
                </h1>
                <p class="section-heading__desc wow animationfadeUp" data-wow-delay="0.4s">{{ __(@$blogContent->subheading) }}
                </p>
            </div>
            <div class="blog-bottom-section">
                <div class="row gy-4 justify-content-center">
                    @foreach ($blogs as $blog)
                        <div class="col-xl-4 col-sm-6 wow animationfadeUp" data-wow-delay="0.6s">
                            <div class="blog-item">
                                <div class="blog-item__thumb">
                                    <a href="{{ route('blog.details', @$blog->slug) }}" class="blog-item__thumb-link">
                                        <img src="{{ frontendImage('blog', 'thumb_' . @$blog->data_values->image) }}"
                                            class="fit-image" alt="image">
                                    </a>
                                </div>
                                <div class="blog-item__content">
                                    <h5 class="blog-item__title">
                                        <a href="{{ route('blog.details', $blog->slug) }}"
                                            class="blog-item__title-link border-effect">
                                            {{ __(@$blog->data_values->title) }} </a>
                                    </h5>
                                    <p class="blog-item__desc">@php echo strLimit(strip_tags(__(@$blog->data_values->description)), 100) @endphp</p>
                                    <div class="blog-item__bottom">
                                        <ul class="content-list">
                                            <li class="content-list__item">
                                                {{ showDateTime(@$blog->created_at, 'd M, Y') }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if ($blogs->hasPages())
                    <div class="dark-pagination">
                        {{ $blogs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
