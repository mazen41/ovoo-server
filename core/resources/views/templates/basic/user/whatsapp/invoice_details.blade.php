@php
    $contactContent = @getContent('contact.content', true)->data_values;
@endphp
<div class="invoice invoice-wrapper">
    <div class="invoice-header clearfix">
        <table class="w-100">
            <tbody>
                <tr>
                    <td class="w-50 align-middle">
                        <img class="invoice-logo light-show float-start"
                            src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(siteLogo())) }}" />
                    </td>
                    <td class="w-50 align-middle">
                        <ul class="invoice-info clearfix">
                            <li class="invoice-info__item float-end ms-3">
                                <span class="label">@lang('Invoice Date')</span>
                                <p class="value">
                                    {{ showDateTime(@$subscription->created_at) }}
                                </p>
                            </li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="invoice-body">
        <table class="w-100">
            <tbody>
                <tr>
                    <td class="w-50">
                        <div class="invoice-customer">
                            <h6 class="invoice-customer__title">@lang('Customer Information')</h6>
                            <ul class="invoice-customer-info">
                                <li class="invoice-customer-info__item"><span class="label">@lang('Name'):</span>
                                    <span class="value">{{ __(@$subscription->user?->fullName) }}</span>
                                </li>
                                <li class="invoice-customer-info__item"><span class="label">@lang('Email'):</span>
                                    <span class="value">{{ @$subscription->user?->email }}</span>
                                </li>
                                <li class="invoice-customer-info__item"><span class="label">@lang('Mobile'):</span>
                                    <span class="value">+{{ __(@$subscription->user?->mobileNumber) }}</span>
                                </li>
                                <li class="invoice-customer-info__item"><span class="label">@lang('Address'):</span>
                                    <span class="value">{{ __(@$subscription->user?->address) }}</span>
                                </li>
                            </ul>
                        </div>
                    </td>

                    <td class="w-50 align-middle">
                        <ul class="invoice-company-info float-end">
                            <li class="invoice-company-info__item">
                                <span class="label">@lang('Email'):</span>
                                {{ $contactContent->contact_email }}
                            </li>
                            <li class="invoice-company-info__item">
                                <span class="label">@lang('Phone'):</span>
                                {{ $contactContent->contact_number }}
                            </li>
                            <li class="invoice-company-info__item">
                                <span class="label">@lang('Address'):</span>
                                {{ $contactContent->contact_address }}
                            </li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="invoice-pdt">
            <div class="invoice-pdt__body">
                <div class="invoice-pdt-table-wrapper">
                    <table class="invoice-pdt-table w-100">
                        <thead>
                            <tr>
                                <th class="text-start">@lang('Plan')</th>
                                <th class="text-center">@lang('Price')</th>
                                <th class="text-end">@lang('Payment Status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="tr-last">
                                <td class="text-start text-nowrap">
                                    {{ __(@$subscription?->plan?->name) }}
                                </td>
                                <td class="text-center text-nowrap">
                                    {{ gs('cur_sym') }}{{ showAmount(@$subscription->amount, currencyFormat: false) }}
                                </td>

                                <td class="text-end text-nowrap">
                                    @lang('Paid')
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="totals">
            <div class="totals-wrapper">
                <table class="totals-table">
                    <tr>
                        <td>@lang('Subtotal')</td>
                        <td class="text-end">{{ showAmount($subscription->amount) }}</td>
                    </tr>
                    <tr>
                        <td>@lang('Discount')</td>
                        <td class="text-end">{{ showAmount($subscription->discount_amount) }}</td>
                    </tr>
                    <tr>
                        <td>@lang('Tax')</td>
                        <td class="text-end">{{ showAmount(0) }}</td>
                    </tr>
                    <tr>
                        <td>@lang('Grand Total')</td>
                        <td class="text-end">{{ showAmount(@$subscription->amount) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
