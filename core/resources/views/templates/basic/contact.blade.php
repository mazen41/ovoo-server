@php
    $contactContent = @getContent('contact.content', true)->data_values;
@endphp
@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="contact-section  py-100">
        <div class="container">
            <div class="section-heading">
                <h1 class="section-heading__title wow animationfadeUp" data-wow-delay="0.2s">
                    {{ __(@$contactContent->heading) }}</h1>
                <p class="section-heading__desc wow animationfadeUp" data-wow-delay="0.4s">
                    {{ __(@$contactContent->subheading) }}</p>
            </div>
            <form class="contact-form verify-gcaptcha wow animationfadeUp" data-wow-delay="0.6s" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>@lang('Your First Name')</label>
                                    <input class="form--control" name="firstname" type="text"
                                        value="{{ old('firstname') }}" placeholder="@lang('Enter your first name')" required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>@lang('Your Last Name')</label>
                                    <input class="form--control" name="lastname" type="text"
                                        value="{{ old('lastname') }}" placeholder="@lang('Enter your last name')" required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>@lang('Your Email')</label>
                                    <input class="form--control" name="email" type="email" value="{{ old('email') }}"
                                        placeholder="@lang('Enter your email')" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 ps-xl-5">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>@lang('Subject')</label>
                                    <input class="form--control" name="subject" type="text" value="{{ old('subject') }}"
                                        placeholder="@lang('Enter your subject')" required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>@lang('Description')</label>
                                    <textarea class="form--control" name="message" placeholder="@lang('Write your message')" required>{{ old('message') }}</textarea>
                                </div>
                            </div>
                            <x-captcha />
                            <div class="col-sm-12">
                                <button class="btn btn--base btn--lg w-100">@lang('Send Message Now')</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-12">
                        <div class="support-wrapper">
                            <div class="support-item">
                                <h3 class="support-item__top">
                                    @lang('24/7')
                                </h3>
                                <h5 class="support-item__title">@lang('Support Center')</h5>
                                <p class="support-item__desc">@lang('Whether you have a question, need support.')</p>
                            </div>
                            <div class="support-item">
                                <h3 class="support-item__top">@lang('FAQ')</h3>
                                <h5 class="support-item__title">@lang('Read all FAQ')</h5>
                                <p class="support-item__desc">@lang('Find answers to common questions in our FAQ below.')
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row gy-4 justify-content-center wow animationfadeUp" data-wow-delay="0.8s">
                <div class="col-lg-4 col-sm-6">
                    <div class="contact-item">
                        <h5 class="contact-item__title">{{ __(@$contactContent->other_contact_title) }}</h5>
                        <p class="contact-item__desc">{{ __(@$contactContent->other_contact_subtitle) }}</p>
                        <div class="contact-item__bottom">
                            <p class="contact-item__text">
                                <a class="contact-item__link" href="mailto:{{ @$contactContent->contact_email }}">
                                    <span class="contact-item__icon"> <i class="fa-regular fa-envelope"></i></span>
                                    {{ @$contactContent->contact_email }}
                                </a>
                            </p>
                            <p class="contact-item__text">
                                <a class="contact-item__link" href="telto:{{ @$contactContent->contact_number }}">
                                    <span class="contact-item__icon"> <i class="fa-solid fa-phone-volume"></i></span>
                                    {{ @$contactContent->contact_number }}
                                </a>
                            </p>
                        </div>
                        <div class="contact-item__shape">
                            <img src="{{ getImage($activeTemplateTrue . 'images/con-1.png') }}" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="contact-item">
                        <h5 class="contact-item__title">{{ __(@$contactContent->business_address_title) }}</h5>
                        <p class="contact-item__desc">{{ __(@$contactContent->business_address_subtitle) }}</p>
                        <div class="contact-item__bottom">
                            <p class="contact-item__text">
                                <span class="contact-item__icon"> <i class="fa-solid fa-location-dot"></i> </span>
                                {{ __(@$contactContent->contact_address) }}
                            </p>
                        </div>
                        <div class="contact-item__shape">
                            <img src="{{ getImage(@$activeTemplateTrue . 'images/con-2.png') }}" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="contact-item">
                        <h5 class="contact-item__title">{{ __(@$contactContent->working_hours_title) }}</h5>
                        <p class="contact-item__desc">{{ __(@$contactContent->working_hours_subtitle) }}</p>
                        <div class="contact-item__bottom">
                            <div class="time-wrapper">
                                <span class="title">@lang('Working Days'): </span>
                                <span class="time"> {{ __(@$contactContent->working_days) }} </span>
                            </div>
                            <div class="time-wrapper">
                                <span class="title">@lang('Working Hours'): </span>
                                <span class="time"> {{ __(@$contactContent->working_hours) }}</span>
                            </div>
                        </div>
                        <div class="contact-item__shape">
                            <img src="{{ getImage(@$activeTemplateTrue . 'images/con-3.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
