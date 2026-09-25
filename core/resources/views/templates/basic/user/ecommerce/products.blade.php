@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        @if (!$errorMessage)
            <div class="container-top">
                <div class="container-top__left">
                    <h5 class="container-top__title">{{ __($pageTitle ?? '') }}</h5>
                    <p class="container-top__desc">
                        @lang('After exploring, the product list will be cached to ensure you always get the latest data.')
                        <a href="{{ route('user.ecommerce.woocommerce.clear.cache') }}">
                            <i class="la la-external-link"></i> @lang('Refresh Cache')
                        </a>
                    </p>
                </div>
                <div class="container-top__right">
                    <form class="search-form">
                        <input type="search" class="form--control" placeholder="@lang('Search here')..." name="search"
                            value="{{ request()->search }}">
                        <span class="search-form__icon"> <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                    </form>
                </div>
            </div>
            <div class="dashboard-container__body">
                <div class="dashboard-table">
                    <table class="table table--responsive--md">
                        <thead>
                            <tr>
                                <th>@lang('Image')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Price')</th>
                                <th>@lang('Stock')</th>
                                <th>@lang('Last Update')</th>
                                <th>@lang('Links')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>
                                        @php
                                            $image =
                                                isset($product['images']) &&
                                                isset($product['images'][0]) &&
                                                isset($product['images'][0]['thumbnail'])
                                                    ? $product['images'][0]['thumbnail']
                                                    : getImage('assets/images/default_product_image.png');
                                        @endphp
                                        <img class="product-img" src="{{ $image }}" alt="img">
                                    </td>

                                    <td>{{ __($product['name'] ?? '') }}</td>
                                    <td>
                                        @if ($product['sale_price'] && $product['regular_price'])
                                            <p>{{ showAmount($product['sale_price'] ?? 0, currencyFormat: false) }}
                                                {{ gs('cur_text') }}</p>
                                            <p><del>{{ showAmount($product['regular_price'] ?? 0, currencyFormat: false) }}
                                                    {{ gs('cur_text') }}</del></p>
                                        @elseif ($product['sale_price'] && !$product['regular_price'])
                                            <p>{{ showAmount($product['sale_price'] ?? 0, currencyFormat: false) }}
                                                {{ gs('cur_text') }}</p>
                                        @else
                                            <p>{{ showAmount($product['price'] ?? 0, currencyFormat: false) }}
                                                {{ gs('cur_text') }}</p>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $product['manage_stock'] ?? false
                                            ? (($product['stock_quantity'] ?? 0) > 0
                                                ? $product['stock_quantity']
                                                : trans('Stock Out'))
                                            : trans('N/A') }}
                                    </td>
                                    <td>{{ showDateTime($product['date_modified'] ?? now()) }}</td>
                                    <td>
                                        @php
                                            $permalink = isset($product['permalink']) ? e($product['permalink']) : '#';
                                        @endphp
                                        <a class="text--info" title="{{ __('Product Link') }}" href="{{ $permalink }}"
                                            target="_blank" rel="noopener noreferrer">
                                            <i class="fa-solid fa-link"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                @include('Template::partials.empty_message')
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ paginateLinks($products) }}
            </div>
        @else
            <div class="dashboard-container__body">
                <div class="mb-3 py-5 text-center">
                    <h4 class="text--warning mb-1">@lang('Woo-Commerce Configuration Error')</h4>
                    <p>{{ __($errorMessage) }}</p>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('style')
    <style>
        .product-img {
            width: 60px;
        }
    </style>
@endpush
