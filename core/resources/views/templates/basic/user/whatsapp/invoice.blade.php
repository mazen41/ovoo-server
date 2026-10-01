@php
    $contactContent = @getContent('contact.content', true)->data_values;
@endphp
@extends($activeTemplate . 'layouts.master')
@section('content')
    @include('Template::user.whatsapp.invoice_details')
    <div class="invoice-footer">
        <div class="flex-end gap-2">
            <a href="{{ route('user.subscription.invoice.download', $subscription->id) }}" class="btn btn--base"><i
                    class="las la-file-pdf"></i> @lang('PDF')</a>
            <button class="btn btn--dark printBtn"
                data-action="{{ route('user.subscription.invoice.print', $subscription->id) }}"><i class="las la-print"></i>
                @lang('Print Invoice')
            </button>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";

        (function($) {

            $(".printBtn").on('click', function() {
                const action = $(this).data('action');
                $.ajax({
                    type: "GET",
                    url: action,
                    success: function(response) {
                        if (response.success) {
                            if ($('body').find('.print-content').length) {
                                $('body').find('.print-content').html(response.html);
                            } else {
                                $('body').append(
                                    `<div class="print-content">${response.html}</div>`);
                            }
                            window.print();
                        } else {
                            notify('error', response.message);
                        }
                    }
                });
            });

            $(window).on('afterprint', function() {
                $('body').find('.print-content').remove();
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .invoice-wrapper {
            font-size: 14px;
            border-radius: 12px;
            background-color: hsl(var(--white));
            max-width: 1024px;
            width: 100%;
            border: 1px solid hsl(var(--black)/0.1);
            max-width: 794px;
            margin: 0 auto;
        }

        .invoice-footer {
            max-width: 794px;
            margin: 0 auto;
        }

        .invoice-header,
        .invoice-body {
            padding: 16px 24px;
        }

        @media (max-width: 424px) {

            .invoice-header,
            .invoice-body {
                padding: 12px 10px;
            }
        }

        .invoice-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .invoice-logo {
            max-width: 200px;
            display: block;
            object-fit: cover;
        }

        .invoice-company-info {
            list-style-type: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .invoice-company-info__item {
            font-weight: 500;
            line-height: 180%;
            max-width: 240px;
            color: rgba(0, 0, 0, 0.8);
            margin-bottom: 6px;
            text-align: right;
        }

        .invoice-company-info__item .label {
            color: rgba(0, 0, 0, 0.5);
        }

        .invoice-customer__title {
            font-size: 16px;
            font-weight: 600;
            color: rgba(0, 0, 0, 0.8);
            margin-bottom: 12px;
        }

        .invoice-customer-info {
            list-style-type: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .invoice-customer-info__item {
            font-weight: 500;
            margin-bottom: 6px;
        }

        .invoice-customer-info__item .label {
            color: rgba(0, 0, 0, 0.5);
        }

        .invoice-customer-info__item .value {
            color: rgba(0, 0, 0, 0.8);
        }

        .invoice-info {
            list-style-type: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .invoice-info__item {
            font-weight: 500;
        }

        .invoice-info__item .label {
            color: rgba(0, 0, 0, 0.5);
            font-weight: 500;
        }

        .invoice-info__item .value {
            font-size: inherit;
            font-weight: 500;
            color: rgba(0, 0, 0, 0.8)
        }

        .invoice-pdt {
            margin-top: 24px;
        }

        .invoice-pdt__footer {
            margin-top: 24px;
        }

        .invoice-pdt-table-wrapper {
            overflow: hidden;
            border-radius: 6px;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .invoice-pdt-table {
            border-collapse: collapse;
            border-spacing: 0px;
            font-weight: 500;
        }

        .invoice-pdt-table thead>tr>th,
        .invoice-pdt-table tbody>tr>td {
            padding: 8px;
            font-weight: 500;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        @media print {
            .invoice-footer {
                display: none !important;
            }

            .invoice-pdt-table thead>tr>th,
            .invoice-pdt-table tbody>tr>td {
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            }
        }

        .invoice-pdt-table thead>tr>th {
            color: rgba(0, 0, 0, 0.8);
            font-weight: 600;
            /* white-space: nowrap; */
        }

        .invoice-pdt-table tbody>tr>td {
            color: rgba(0, 0, 0, 0.5);
            font-weight: 400;
        }

        .invoice-pdt-table tbody>tr.tr-last>td {
            border: none;
        }

        .invoice-payment {
            margin-left: auto;
        }

        .invoice-payment__title {
            font-size: 16px;
            font-weight: 600;
            color: rgba(0, 0, 0, 0.8);
            margin-bottom: 4px;
        }

        .invoice-payment-info {
            list-style-type: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .invoice-payment-info__item {
            font-size: 16px;
        }

        .invoice-payment-info__item .label {
            color: rgba(0, 0, 0, 0.5);
        }


        .invoice-pricing__title {
            color: rgba(0, 0, 0, 0.8);
            font-size: 16px;
        }

        .invoice-pricing-info {
            max-width: 240px;
            list-style-type: none;
            padding-left: 0;
            margin-bottom: 0;
            text-align: right;
            margin-left: auto;
        }

        .invoice-footer {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: 16px;
        }

        .invoice-pricing-info__item {
            font-size: 16px;
            white-space: nowrap;
        }

        .invoice-pricing-info__item .label {
            color: rgba(0, 0, 0, 0.5);
            font-weight: 500;
        }

        .invoice-pricing-info__item.total {
            font-size: 24px;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            margin-top: 6px;
            padding-top: 6px;
        }


        .clearfix::after {
            display: block;
            clear: both;
            content: ""
        }

        .float-start {
            float: left !important;
        }

        .float-end {
            float: right !important;
        }

        .float-none {
            float: none !important;
        }

        .align-baseline {
            vertical-align: baseline !important;
        }

        .align-top {
            vertical-align: top !important;
        }

        .align-middle {
            vertical-align: middle !important;
        }

        .align-bottom {
            vertical-align: bottom !important;
        }

        .align-text-bottom {
            vertical-align: text-bottom !important;
        }

        .align-text-top {
            vertical-align: text-top !important;
        }

        .border-0 {
            border: 0 !important;
        }

        .overflow-auto {
            overflow: auto !important;
        }

        .overflow-hidden {
            overflow: hidden !important;
        }

        .overflow-visible {
            overflow: visible !important;
        }

        .overflow-scroll {
            overflow: scroll !important;
        }

        .d-inline {
            display: inline !important;
        }

        .d-inline-block {
            display: inline-block !important;
        }

        .w-25 {
            width: 25% !important;
        }

        .w-50 {
            width: 50% !important;
        }

        .w-75 {
            width: 75% !important;
        }

        .w-100 {
            width: 100% !important;
        }

        .w-auto {
            width: auto !important;
        }

        .mw-100 {
            max-width: 100% !important;
        }

        .vw-100 {
            width: 100vw !important;
        }

        .min-vw-100 {
            min-width: 100vw !important;
        }

        .h-25 {
            height: 25% !important;
        }

        .h-50 {
            height: 50% !important;
        }

        .h-75 {
            height: 75% !important;
        }

        .h-100 {
            height: 100% !important;
        }

        .h-auto {
            height: auto !important;
        }

        .mh-100 {
            max-height: 100% !important;
        }

        .vh-100 {
            height: 100vh !important;
        }

        .min-vh-100 {
            min-height: 100vh !important;
        }

        .m-0 {
            margin: 0 !important;
        }

        .m-1 {
            margin: 0.25rem !important;
        }

        .m-2 {
            margin: 0.5rem !important;
        }

        .m-3 {
            margin: 1rem !important;
        }

        .m-4 {
            margin: 1.5rem !important;
        }

        .m-5 {
            margin: 3rem !important;
        }

        .m-auto {
            margin: auto !important;
        }

        .mx-0 {
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        .mx-1 {
            margin-right: 0.25rem !important;
            margin-left: 0.25rem !important;
        }

        .mx-2 {
            margin-right: 0.5rem !important;
            margin-left: 0.5rem !important;
        }

        .mx-3 {
            margin-right: 1rem !important;
            margin-left: 1rem !important;
        }

        .mx-4 {
            margin-right: 1.5rem !important;
            margin-left: 1.5rem !important;
        }

        .mx-5 {
            margin-right: 3rem !important;
            margin-left: 3rem !important;
        }

        .mx-auto {
            margin-right: auto !important;
            margin-left: auto !important;
        }

        .my-0 {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        .my-1 {
            margin-top: 0.25rem !important;
            margin-bottom: 0.25rem !important;
        }

        .my-2 {
            margin-top: 0.5rem !important;
            margin-bottom: 0.5rem !important;
        }

        .my-3 {
            margin-top: 1rem !important;
            margin-bottom: 1rem !important;
        }

        .my-4 {
            margin-top: 1.5rem !important;
            margin-bottom: 1.5rem !important;
        }

        .my-5 {
            margin-top: 3rem !important;
            margin-bottom: 3rem !important;
        }

        .my-auto {
            margin-top: auto !important;
            margin-bottom: auto !important;
        }

        .mt-0 {
            margin-top: 0 !important;
        }

        .mt-1 {
            margin-top: 0.25rem !important;
        }

        .mt-2 {
            margin-top: 0.5rem !important;
        }

        .mt-3 {
            margin-top: 1rem !important;
        }

        .mt-4 {
            margin-top: 1.5rem !important;
        }

        .mt-5 {
            margin-top: 3rem !important;
        }

        .mt-auto {
            margin-top: auto !important;
        }

        .me-0 {
            margin-right: 0 !important;
        }

        .me-1 {
            margin-right: 0.25rem !important;
        }

        .me-2 {
            margin-right: 0.5rem !important;
        }

        .me-3 {
            margin-right: 1rem !important;
        }

        .me-4 {
            margin-right: 1.5rem !important;
        }

        .me-5 {
            margin-right: 3rem !important;
        }

        .me-auto {
            margin-right: auto !important;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .mb-1 {
            margin-bottom: 0.25rem !important;
        }

        .mb-2 {
            margin-bottom: 0.5rem !important;
        }

        .mb-3 {
            margin-bottom: 1rem !important;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        .mb-5 {
            margin-bottom: 3rem !important;
        }

        .mb-auto {
            margin-bottom: auto !important;
        }

        .ms-0 {
            margin-left: 0 !important;
        }

        .ms-1 {
            margin-left: 0.25rem !important;
        }

        .ms-2 {
            margin-left: 0.5rem !important;
        }

        .ms-3 {
            margin-left: 1rem !important;
        }

        .ms-4 {
            margin-left: 1.5rem !important;
        }

        .ms-5 {
            margin-left: 3rem !important;
        }

        .ms-auto {
            margin-left: auto !important;
        }

        .p-0 {
            padding: 0 !important;
        }

        .p-1 {
            padding: 0.25rem !important;
        }

        .p-2 {
            padding: 0.5rem !important;
        }

        .p-3 {
            padding: 1rem !important;
        }

        .p-4 {
            padding: 1.5rem !important;
        }

        .p-5 {
            padding: 3rem !important;
        }

        .px-0 {
            padding-right: 0 !important;
            padding-left: 0 !important;
        }

        .px-1 {
            padding-right: 0.25rem !important;
            padding-left: 0.25rem !important;
        }

        .px-2 {
            padding-right: 0.5rem !important;
            padding-left: 0.5rem !important;
        }

        .px-3 {
            padding-right: 1rem !important;
            padding-left: 1rem !important;
        }

        .px-4 {
            padding-right: 1.5rem !important;
            padding-left: 1.5rem !important;
        }

        .px-5 {
            padding-right: 3rem !important;
            padding-left: 3rem !important;
        }

        .py-0 {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .py-1 {
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
        }

        .py-2 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        .py-3 {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }

        .py-4 {
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
        }

        .py-5 {
            padding-top: 3rem !important;
            padding-bottom: 3rem !important;
        }

        .pt-0 {
            padding-top: 0 !important;
        }

        .pt-1 {
            padding-top: 0.25rem !important;
        }

        .pt-2 {
            padding-top: 0.5rem !important;
        }

        .pt-3 {
            padding-top: 1rem !important;
        }

        .pt-4 {
            padding-top: 1.5rem !important;
        }

        .pt-5 {
            padding-top: 3rem !important;
        }

        .pe-0 {
            padding-right: 0 !important;
        }

        .pe-1 {
            padding-right: 0.25rem !important;
        }

        .pe-2 {
            padding-right: 0.5rem !important;
        }

        .pe-3 {
            padding-right: 1rem !important;
        }

        .pe-4 {
            padding-right: 1.5rem !important;
        }

        .pe-5 {
            padding-right: 3rem !important;
        }

        .pb-0 {
            padding-bottom: 0 !important;
        }

        .pb-1 {
            padding-bottom: 0.25rem !important;
        }

        .pb-2 {
            padding-bottom: 0.5rem !important;
        }

        .pb-3 {
            padding-bottom: 1rem !important;
        }

        .pb-4 {
            padding-bottom: 1.5rem !important;
        }

        .pb-5 {
            padding-bottom: 3rem !important;
        }

        .ps-0 {
            padding-left: 0 !important;
        }

        .ps-1 {
            padding-left: 0.25rem !important;
        }

        .ps-2 {
            padding-left: 0.5rem !important;
        }

        .ps-3 {
            padding-left: 1rem !important;
        }

        .ps-4 {
            padding-left: 1.5rem !important;
        }

        .ps-5 {
            padding-left: 3rem !important;
        }

        .fs-1 {
            font-size: calc(1.375rem + 1.5vw) !important;
        }

        .fs-2 {
            font-size: calc(1.325rem + 0.9vw) !important;
        }

        .fs-3 {
            font-size: calc(1.3rem + 0.6vw) !important;
        }

        .fs-4 {
            font-size: calc(1.275rem + 0.3vw) !important;
        }

        .fs-5 {
            font-size: 1.25rem !important;
        }

        .fs-6 {
            font-size: 1rem !important;
        }

        .fst-italic {
            font-style: italic !important;
        }

        .fst-normal {
            font-style: normal !important;
        }

        .fw-light {
            font-weight: 300 !important;
        }

        .fw-lighter {
            font-weight: lighter !important;
        }

        .fw-normal {
            font-weight: 400 !important;
        }

        .fw-bold {
            font-weight: 700 !important;
        }

        .fw-semibold {
            font-weight: 600 !important;
        }

        .fw-bolder {
            font-weight: bolder !important;
        }

        .lh-1 {
            line-height: 1 !important;
        }

        .lh-sm {
            line-height: 1.25 !important;
        }

        .lh-base {
            line-height: 1.5 !important;
        }

        .lh-lg {
            line-height: 2 !important;
        }

        .text-start {
            text-align: left !important;
        }

        .text-end {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-decoration-none {
            text-decoration: none !important;
        }

        .text-decoration-underline {
            text-decoration: underline !important;
        }

        .text-decoration-line-through {
            text-decoration: line-through !important;
        }

        .text-lowercase {
            text-transform: lowercase !important;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }

        .text-capitalize {
            text-transform: capitalize !important;
        }

        .text-wrap {
            white-space: normal !important;
        }

        .text-nowrap {
            white-space: nowrap !important;
        }

        .visible {
            visibility: visible !important;
        }

        .invisible {
            visibility: hidden !important;
        }

        .a4-size {
            width: 8.27in;
            height: 11.69in;
            margin: 0 auto;
        }

        .totals {
            margin-top: 20px;
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }

        .totals-wrapper {
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .totals-table {
            width: 400px;
            border-collapse: collapse;
            font-size: 15px;
        }

        @media (max-width: 575px) {
            .totals-table {
                width: unset;
                max-width: 400px;
            }
        }

        .totals-table td {
            padding: 8px 12px;
            color: rgba(0, 0, 0, 0.5);
        }

        .totals-table tr td {
            border: 0;
        }

        .totals-table tr td:last-child {
            font-weight: bold;
            color: rgba(0, 0, 0, 0.8);
        }

        @media print {
            body>*:not(.print-content) {
                display: none !important;
            }

        }
    </style>
@endpush
