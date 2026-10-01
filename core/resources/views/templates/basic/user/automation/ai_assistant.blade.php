@extends($activeTemplate . 'layouts.master')
@section('content')
    @if ($activeAiAssistant)
        <div class="alert alert--info alert-dismissible mb-3 template-requirements" role="alert">
            <div class="alert__content">
                <h4 class="alert__title"><i class="las la-info-circle"></i> @lang('Important Note')</h4>
                <ul class="ms-4">
                    <li class="mb-0 text-dark">@lang('The system prompt acts as the data source for the AI about your business information. Make it brief and clear.')</li>
                    <li class="mb-0 text-dark">@lang('The clearer and more specific your system prompt is, the more accurate the AI responses will be.')</li>
                    <li class="mb-0 text-dark">@lang('The fallback response will be sent to the customer when the AI cannot find a related response from the business information (system prompt).')</li>
                    <li class="mb-0 text-dark">@lang('The AI will respond to the customer until a fallback response is triggered within 12 hours.')</li>
                    <li class="mb-0 text-dark">@lang('Avoid including sensitive or confidential information except business contact information in the system prompt, as it will be visible to the AI for generating replies.')</li>
                    <li class="mb-0 text-dark">@lang('For an e-commerce business, include relevant product information in the system prompt,so that AI can generate relevant responses.')</li>
                    <li class="mb-0 text-dark">@lang('If there is any automation flow or the welcome message triggered, the AI will not respond to the customer.')</li>
                    <li class="mb-0 text-dark">@lang('For an example of a system prompt, you can use the following') <span
                            class="text--primary fs-14 cursor-pointer system-prompt-btn">@lang('See Example')</span></li>
                </ul>
            </div>
        </div>
    @else
        <div class="alert alert--danger alert-dismissible mb-3" role="alert">
            <div class="alert__content">
                <h4 class="alert__title"><i class="las la-info-circle"></i> @lang('Notice')</h4>
                <p class="fs-16 text-secondary">
                    @lang('No AI Assistant has been configured for this system by the platform administrator. Please')
                    <a href="{{ route('contact') }}" class="text-primary font-weight-bold">@lang('contact administrator')</a>
                    @lang('to get it set up.')
                </p>
            </div>

        </div>
    @endif
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Setup your AI Assistant and get personalized responses.')</p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <button type="submit" form="ai-assistant-form" class="btn btn--base btn-shadow">
                        <i class="lab la-telegram"></i> @lang('Save Settings')
                    </button>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <div class="information-wrapper">
                <div class="row">
                    <div class="col-xxl-8">
                        <form action="{{ route('user.automation.ai.assistant.store') }}" method="POST"
                            id="ai-assistant-form">
                            @csrf

                            <div class="form-group">
                                <label>
                                    @lang('System Prompt')
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('Please provide very clear details about your business. If possible, also include some initial product details so the AI can better reply on your behalf.')">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                </label>
                                <textarea name="system_prompt" cols="30" rows="10" class="form--control form-two" required>{{ old('system_prompt', @$aiSetting->system_prompt) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>
                                    @lang('Fallback Response')
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('If the customer query does not match the system prompt, this response will be sent instead.')">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                </label>
                                <textarea name="fallback_response" cols="30" rows="10" class="form--control form-two" required>{{ old('fallback_response', @$aiSetting->fallback_response) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>@lang('Max Length')</label>
                                <input type="number" name="max_length" class="form--control form-two"
                                    value="{{ old('max_length', @$aiSetting->max_length) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1">@lang('Status')</label>
                                <div class="form--switch">
                                    <input class="form-check-input" type="checkbox" role="switch" name="status"
                                        id="ai-status-switch" @checked(@$aiSetting->status) />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade custom--modal modal-xl" id="systemPromptModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('System Prompt')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="icon">
                            <i class="fas fa-times"></i>
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-pills mb-3" id="systemPromptTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="ecommerce-tab" data-bs-toggle="pill"
                                data-bs-target="#ecommerce" type="button" role="tab" aria-controls="ecommerce"
                                aria-selected="true">
                                @lang('E-commerce')
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="service-tab" data-bs-toggle="pill" data-bs-target="#service"
                                type="button" role="tab" aria-controls="service" aria-selected="false">
                                @lang('Service Business')
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="restaurant-tab" data-bs-toggle="pill"
                                data-bs-target="#restaurant" type="button" role="tab" aria-controls="restaurant"
                                aria-selected="false">
                                @lang('Real Estate')
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="systemPromptTabsContent">
                        <div class="tab-pane fade show active" id="ecommerce" role="tabpanel"
                            aria-labelledby="ecommerce-tab">
                            <p class="mb-2">@lang('Modify the info below with your business details:')</p>
                            <div class="prompt-content">
                                <textarea class="p-2 bg-light border rounded pre-like">
We are an e-commerce company. Please introduce yourself to our company and reply with the following after the introduction and welcome every customer:
Business Name: TechNova Gadgets
Address: 123 Tech Street, New York, USA
Contact: (123) 456-7890

Products: Apparel & Accessories

T-Shirts:
- Full-sleeve for men
  Price Range: $100 - $500
- Half-sleeve for men
  Price Range: $50 - $150
- Sleeveless for women
  Price Range: $70 - $200
- Half-sleeve for women
  Price Range: $50 - $150

Pants:
- Formal pants for men
  Price Range: $80 - $300
- Casual pants for men
  Price Range: $50 - $150
- Jeans for women
  Price Range: $60 - $250
- Leggings for women
  Price Range: $40 - $120

Shoes:
- Sneakers for men
  Price Range: $100 - $400
- Formal shoes for men
  Price Range: $120 - $500
- Flats for women
  Price Range: $50 - $200
- Heels for women
  Price Range: $80 - $350

Watches:
- Analog watches for men
  Price Range: $150 - $600
- Digital watches for men
  Price Range: $100 - $500
- Analog watches for women
  Price Range: $120 - $400
- Smart watches for women
  Price Range: $200 - $700

Note: if the question/query is out of the box e-commerce, then please respond empty(do not reply to anything, not even a string)
                            </textarea>
                                <span><i class="las la-copy copy-button"></i></span>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="service" role="tabpanel" aria-labelledby="service-tab">
                            <p class="mb-2">@lang('Modify the info below with your business details:')</p>
                            <div class="prompt-content">
                                <textarea class="p-2 bg-light border rounded pre-like">
We are a cleaning company. Please introduce yourself to our company and reply with the following after the introduction and welcome every customer:
Business Name: CleanCraft Services
Address: 78 Green Valley Road, Chicago, USA
Contact: (312) 987-6543

Services: Professional Home & Office Maintenance

Home Cleaning:
- Full home deep cleaning
  Price Range: $120 - $350
- Kitchen and bathroom cleaning
  Price Range: $80 - $200
- Carpet and sofa shampooing
  Price Range: $100 - $250
- Move-in / move-out cleaning
  Price Range: $150 - $400

Office Cleaning:
- Daily maintenance packages
  Price Range: $500 - $1,200 per month
- One-time deep cleaning
  Price Range: $200 - $600
- Window and glass cleaning
  Price Range: $80 - $250
- Floor polishing and disinfection
  Price Range: $150 - $400

Additional Services:
- AC and appliance servicing
  Price Range: $70 - $200
- Pest control and sanitization
  Price Range: $100 - $300
- Plumbing and electrical repair
  Price Range: $50 - $250
- Upholstery and mattress cleaning
  Price Range: $80 - $220

Beauty & Personal Care:
- Home salon for women
  Price Range: $50 - $300
- Haircut and styling
  Price Range: $40 - $150
- Massage and spa at home
  Price Range: $100 - $400
- Bridal and makeup packages
  Price Range: $150 - $600

Note: if the question/query is out of the box e-commerce, then please respond empty(do not reply to anything, not even a string)
                            </textarea>
                                <span><i class="las la-copy copy-button"></i></span>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="restaurant" role="tabpanel" aria-labelledby="restaurant-tab">
                            <p class="mb-2">@lang('Modify the info below with your business details:')</p>
                            <div class="prompt-content">
                                <textarea class="p-2 bg-light border rounded pre-like">
We are a real estate company. Please introduce yourself to our company and reply with the following after the introduction and welcome every customer:
Business Name: Skyline Properties
Address: 45 Empire Avenue, Los Angeles, USA
Contact: (987) 654-3210

Services: Real Estate Sales & Rentals

Residential Properties:
- Luxury apartments
  Price Range: $250,000 - $1,200,000
- Family houses
  Price Range: $180,000 - $850,000
- Studio apartments
  Price Range: $90,000 - $250,000
- Villas and penthouses
  Price Range: $500,000 - $2,000,000

Commercial Properties:
- Office spaces (downtown)
  Price Range: $300,000 - $1,500,000
- Retail shops
  Price Range: $200,000 - $900,000
- Warehouses
  Price Range: $150,000 - $750,000
- Co-working spaces
  Price Range: $100,000 - $500,000

Rental Options:
- Apartments for rent
  Price Range: $800 - $3,000 per month
- Houses for rent
  Price Range: $1,000 - $4,500 per month
- Office spaces for rent
  Price Range: $1,200 - $5,000 per month
- Retail shops for rent
  Price Range: $900 - $3,800 per month

Land & Plots:
- Residential plots
  Price Range: $50,000 - $500,000
- Commercial plots
  Price Range: $100,000 - $800,000
- Agricultural land
  Price Range: $30,000 - $300,000
- Industrial land
  Price Range: $120,000 - $900,000

Note: if the question/query is out of the box e-commerce, then please respond empty(do not reply to anything, not even a string)
                            </textarea>
                                <span><i class="las la-copy copy-button"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.system-prompt-btn').on('click', function() {
                $('#systemPromptModal').modal('show');
            });


            $('.copy-button').on('click', function() {
                var textarea = $(this).closest('.prompt-content').find('textarea')[0];
                textarea.select();
                textarea.setSelectionRange(0, 99999);
                document.execCommand("copy");
                textarea.blur();

                $(this).removeClass('las la-copy').addClass('las la-check-double');
                notify('success', "@lang('Copied to clipboard')");
                setTimeout(() => $(this).removeClass('las la-check-double').addClass('las la-copy'), 1500);
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .pre-like {
            color: hsl(var(--black)) !important;
            width: 100%;
            height: 500px !important;
            border: 1px solid #ccc;
            background: #f8f9fa;
            padding: 10px;
            font-family: monospace;
            white-space: pre;
            overflow: auto;
            resize: none;
            scrollbar-width: thin;
            scrollbar-color: hsl(var(--base) / 0.8) hsl(var(--black) / 0.1);
        }

        .pre-like:focus-visible {
            outline: none !important;
        }

        .pre-like::-webkit-scrollbar {
            width: 5px;
        }

        .pre-like::-webkit-scrollbar {
            width: 5px;

        }

        .pre-like::-webkit-scrollbar-thumb {
            background-color: rgb(var(--main));
            border-radius: 10px;
        }

        .prompt-content {
            position: relative;
        }

        .copy-button {
            position: absolute;
            top: 15px;
            right: 22px;
            cursor: pointer;
            color: hsl(var(--base) / 0.8);
            font-size: 20px;
        }

        .nav-pills .nav-link {
            color: hsl(var(--black) / 0.8);
        }

        .nav-pills .nav-link.active {
            color: hsl(var(--base));
            background-color: unset ! important;
        }
    </style>
@endpush
