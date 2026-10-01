@extends($activeTemplate.'layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card ">
                <div class="card-header">
                    <h5 class="card-title">@lang('Stripe Storefront')</h5>
                </div>
                <div class="card-body p-5">
                    <form action="{{$data->url}}" method="{{$data->method}}"  class="submit-form">
                        <ul class="list-group text-center list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                @lang('You have to pay '):
                                <strong>{{showAmount($deposit->final_amount,currencyFormat:false)}} {{__($deposit->method_currency)}}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                @lang('You will get '):
                                <strong>{{showAmount($deposit->amount)}}</strong>
                            </li>
                        </ul>
                         <script src="{{$data->src}}"
                            class="stripe-button"
                            @foreach($data->val as $key=> $value)
                            data-{{$key}}="{{$value}}"
                            @endforeach
                        >
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script-lib')
    <script src="https://js.stripe.com/v3/"></script>
@endpush
@push('script')
    <script>
        (function ($) {
            "use strict";
            $('button[type="submit"]').removeClass().addClass("btn btn--base w-100 mt-3").text("Pay Now");
        })(jQuery);
    </script>
@endpush

@if ($deposit->from_api)
    @push('script')
        <script>
            "use strict";
            (function($) {
                $(".sidebar-menu").remove();
                $(".dashboard-header").remove();
                $(".custom--card").addClass('d-none');
                $("body").attr("style", "background-color: #f0f0f4 !important;");
                $('body').find('#btn-confirm').trigger('click');
            })(jQuery);
        </script>
    @endpush
@endif

