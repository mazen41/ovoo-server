@extends($activeTemplate.'layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card ">
                <div class="card-header">
                    <h5 class="card-title">@lang('Razorpay')</h5>
                </div>
                <div class="card-body p-5">
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
                     <form action="{{$data->url}}" method="{{$data->method}}" class="submit-form">
                        <input type="hidden" custom="{{$data->custom}}" name="hidden">
                        <script src="{{$data->checkout_js}}"
                                @foreach($data->val as $key=>$value)
                                data-{{$key}}="{{$value}}"
                            @endforeach >
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('script')
    <script>
        (function ($) {
            "use strict";
            $('input[type="submit"]').addClass("mt-4 btn btn--base w-100");
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
                $('body').find('.submit-form').trigger('submit');
            })(jQuery);
        </script>
    @endpush
@endif
