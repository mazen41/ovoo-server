@php
    $footerContent = @getContent('footer.content', true)->data_values;
    $policies = @getContent('policy_pages.element', orderById: true);
    $socialIcons = @getContent('social_icon.element', orderById: true);
    $contactContent = @getContent('contact.content', true)->data_values;
@endphp

<footer class="footer-area">
    <div class="shape-bg two"></div>
    <div class="shape-bg three"></div>
    <div class="shape-bg four"></div>
    <div class="pt-100">
        <div class="container">
            <div class="footer-item__logo wow animationfadeUp" data-wow-delay="0.2s">
                <a href="{{ route('home') }}"> <img src="{{ siteLogo('dark') }}" alt="img"></a>
            </div>
            <div class="row gy-5 justify-content-between">
                <div class="col-xl-4 col-sm-7 wow animationfadeUp" data-wow-delay="0.2s">
                    <div class="footer-item">
                        <p class="footer-item__desc">{{ __(@$footerContent->description) }}</p>
                        <div class="search-wrapper">
                            <p class="search-wrapper__title">{{ __(@$footerContent->subscribe_title) }}</p>
                            <form class="search-form subscribe-form no-submit-loader mt-0">
                                <input type="email" class="form--control" placeholder="@lang('Enter your email')" required>
                                <button class="btn--base btn" type="submit">
                                    @lang('Submit')
                                </button>
                            </form>
                            <p class="search-wrapper__text">{{ __(@$footerContent->subscribe_subtitle) }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-sm-5 col-6 wow animationfadeUp" data-wow-delay="0.4s">
                    <div class="footer-item">
                        <h5 class="footer-item__title">@lang('Quick Links') </h5>
                        <ul class="footer-menu">
                            <li class="footer-menu__item">
                                <a href="{{ route('home') }}" class="footer-menu__link">@lang('Home')</a>
                            </li>
                            <li class="footer-menu__item">
                                <a href="{{ route('blogs') }}" class="footer-menu__link">@lang('Blog')</a>
                            </li>
                            <li class="footer-menu__item">
                                <a href="{{ route('contact') }}" class="footer-menu__link">@lang('Contact')</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-7 col-6 wow animationfadeUp" data-wow-delay="0.6s">
                    <div class="footer-item">
                        <h5 class="footer-item__title">@lang('More Pages')</h5>
                        <ul class="footer-menu">
                            @foreach ($policies as $policy)
                                <li class="footer-menu__item">
                                    <a href="{{ route('policy.pages', [slug($policy->data_values->title)]) }}"
                                        class="footer-menu__link">{{ __(@$policy->data_values->title) }}
                                    </a>
                                </li>
                            @endforeach
                            <li class="footer-menu__item">
                                <a href="{{ route('external.api.documentation') }}" class="footer-menu__link">
                                    @lang('API Documentation')
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-5 wow animationfadeUp" data-wow-delay="0.8s">
                    <div class="footer-item">
                        <h5 class="footer-item__title">@lang('Contact Info')</h5>
                        <ul class="footer-contact-menu">
                            <li class="footer-contact-menu__item">
                                <div class="footer-contact-menu__item-icon">
                                    <i class="las la-envelope"></i>
                                </div>
                                <div class="footer-contact-menu__item-content">
                                    <a
                                        href="mailto:{{ @$contactContent->contact_email }}">{{ @$contactContent->contact_email }}</a>
                                </div>
                            </li>
                            <li class="footer-contact-menu__item">
                                <div class="footer-contact-menu__item-icon">
                                    <i class="las la-phone"></i>
                                </div>
                                <div class="footer-contact-menu__item-content">
                                    <a
                                        href="tel:{{ @$contactContent->contact_number }}">{{ @$contactContent->contact_number }}</a>
                                </div>
                            </li>
                        </ul>
                        <div class="share-btn-wrapper">
                            <h5 class="title">
                                <span class="icon">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16.707 9.80175L7.95698 19.1768C7.86426 19.2757 7.74186 19.3418 7.60827 19.3651C7.47467 19.3884 7.33712 19.3676 7.21638 19.3059C7.09564 19.2441 6.99825 19.1448 6.93892 19.0229C6.87958 18.9009 6.86152 18.763 6.88745 18.6299L8.03276 12.901L3.53042 11.2103C3.43373 11.1742 3.3475 11.1146 3.27944 11.037C3.21138 10.9594 3.16361 10.8661 3.1404 10.7655C3.11718 10.6649 3.11925 10.5601 3.14641 10.4605C3.17357 10.3609 3.22498 10.2696 3.29605 10.1947L12.046 0.819721C12.1388 0.720764 12.2612 0.654652 12.3948 0.631359C12.5284 0.608067 12.6659 0.628858 12.7867 0.690597C12.9074 0.752335 13.0048 0.851671 13.0641 0.973613C13.1234 1.09555 13.1415 1.23349 13.1156 1.3666L11.9671 7.10175L16.4695 8.79003C16.5655 8.82645 16.651 8.88594 16.7185 8.96326C16.7861 9.04058 16.8335 9.13334 16.8567 9.23334C16.8798 9.33335 16.878 9.43753 16.8514 9.53666C16.8247 9.6358 16.7741 9.72684 16.7039 9.80175H16.707Z"
                                            fill="currentColor" />
                                    </svg>
                                </span>
                                @lang('Share and Earn!')
                            </h5>
                            <a href="{{ route('user.referral.index') }}" class="btn--base btn btn--sm w-100">
                                <span class="btn-icon"> <i class="fas fa-share-alt"></i> </span>
                                @lang('Get Referral Link')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bottom-footer wow animationfadeUp" data-wow-delay="0.4s">
                <ul class="social-list">
                    @foreach ($socialIcons as $socialIcon)
                        <li class="social-list__item">
                            <a target="_blank" href="{{ @$socialIcon->data_values->url }}"
                                class="social-list__link flex-center">
                                @php echo $socialIcon->data_values->social_icon @endphp
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="bottom-footer-text wow animationfadeUp" data-wow-delay="0.6s">
                    &copy; @lang('Copyright')
                    {{ date('Y') }} @lang('. All rights reserved.')
                </div>
            </div>
        </div>
    </div>
</footer>

@push('script')
    <script>
        "use strict";

        (function($) {
            let form = $('.subscribe-form');
            let isSubmitting = false;
            form.on('submit', function(e) {
                e.preventDefault();

                if (isSubmitting) return;

                let email = form.find('input').val();
                let token = '{{ csrf_token() }}';
                let url = "{{ route('subscribe') }}"

                let data = {
                    email: email,
                    _token: token
                }

                isSubmitting = true;
                $.post(url, data, function(response) {
                    if (response.success) {
                        notify('success', response.message);
                        $(form).trigger('reset');
                    } else {
                        notify('error', response.message);
                    }
                }).always(function() {
                    isSubmitting = false;
                });
            });
        })(jQuery);
    </script>
@endpush
