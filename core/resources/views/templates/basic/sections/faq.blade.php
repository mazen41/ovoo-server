@php
    $faqContent  = @getContent('faq.content', true)->data_values;
    $faqElements = @getContent('faq.element', orderById: true)->groupBy('data_values.category');
@endphp
<div class="faq-section pb-100">
    <div class="container">
        <div class="section-heading">
            <h1 class="section-heading__title wow animationfadeUp" data-wow-delay="0.2s">{{ __(@$faqContent->heading) }}</h1>
            <p class="section-heading__desc wow animationfadeUp" data-wow-delay="0.4s">{{ __(@$faqContent->subheading) }}</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="faq-section__top wow animationfadeUp" data-wow-delay="0.6s">
                    <ul class="nav nav-pills custom--tab" id="pills-tab" role="tablist">
                        @foreach($faqElements as $category => $faqs)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                    id="pills-{{ slug($category) }}-tab" 
                                    data-bs-toggle="pill"
                                    data-bs-target="#pills-{{ slug($category) }}" 
                                    type="button" role="tab"
                                    aria-controls="pills-{{ slug($category) }}"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    {{ ucfirst($category) }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="tab-content wow animationfadeUp" data-wow-delay="0.8s" id="pills-tabContent">
                    @foreach($faqElements as $category => $faqs)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                            id="pills-{{ slug($category) }}" 
                            role="tabpanel"
                            aria-labelledby="pills-{{ slug($category) }}-tab">
                            
                            <div class="accordion custom--accordion" id="accordion-{{ slug($category) }}">
                                @foreach($faqs as $faq)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-{{ $faq->id }}">
                                            <button class="accordion-button {{ $loop->first && $loop->parent->first ? '' : 'collapsed' }}" 
                                                type="button" data-bs-toggle="collapse" 
                                                data-bs-target="#collapse-{{ $faq->id }}" 
                                                aria-expanded="{{ $loop->first && $loop->parent->first ? 'true' : 'false' }}" 
                                                aria-controls="collapse-{{ $faq->id }}">
                                                {{ __($faq->data_values->question) }}
                                            </button>
                                        </h2>
                                        <div id="collapse-{{ $faq->id }}" class="accordion-collapse collapse {{ $loop->first && $loop->parent->first ? 'show' : '' }}" 
                                            aria-labelledby="heading-{{ $faq->id }}" 
                                            data-bs-parent="#accordion-{{ slug($category) }}">
                                            <div class="accordion-body">
                                                <p class="text">
                                                    {{ __($faq->data_values->answer) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
