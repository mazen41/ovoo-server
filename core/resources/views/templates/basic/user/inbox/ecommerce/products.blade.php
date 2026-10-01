@if (!empty($products))
    @forelse ($products as $product)
        <div class="product-card">
            @php
                $image =
                    isset($product['images']) &&
                    isset($product['images'][0]) &&
                    isset($product['images'][0]['thumbnail'])
                        ? $product['images'][0]['thumbnail']
                        : getImage('assets/images/default_product_image.png');

                $category =
                    isset($product['categories']) &&
                    isset($product['categories'][0]) &&
                    isset($product['categories'][0]['name'])
                        ? $product['categories'][0]['name']
                        : trans('N/A');

                $brands =
                    isset($product['brands']) && $product['brands']
                        ? implode(', ', array_map(fn($brand) => $brand['name'], $product['brands']))
                        : trans('N/A');

                $additionalInfo =
                    isset($product['attributes']) && !empty($product['attributes']) && is_array($product['attributes'])
                        ? array_reduce(
                            $product['attributes'],
                            function ($carry, $attribute) {
                                if (
                                    isset($attribute['name']) &&
                                    isset($attribute['options']) &&
                                    is_array($attribute['options']) &&
                                    count($attribute['options']) > 0
                                ) {
                                    $carry[$attribute['name']] = $attribute['options'][0];
                                } else {
                                    $carry[$attribute['name'] ?? ''] = null;
                                }
                                return $carry;
                            },
                            [],
                        )
                        : [];
            @endphp

            <div class="product-card-buttons">
                <div class="dropdown product-action-dropdown">
                    <button class="product-action-dropdown__btn" type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="las la-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><button class="woo-info-btn product-action-dropdown-item"
                                data-product="{{ json_encode($product) }}"><i class="las la-info-circle"></i>
                                @lang('Send Product Information')</button>
                        </li>
                        <li><button class="createOrderSendBtn product-action-dropdown-item"
                                data-product_id="{{ $product['id'] }}"><i class="las la-plus-circle"></i>
                                @lang('Create Order & Send Payment Link')</button>
                        </li>
                        <li><button class="product-action-dropdown-item productOffCanvasBtn" type="button"
                                data-product_image="{{ $image }}"
                                data-product_name="{{ $product['name'] ?? '' }}"
                                data-product_price="{{ $product['price'] ?? 0 }}"
                                data-product_regular_price="{{ $product['regular_price'] ?? 0 }}"
                                data-product_sale_price="{{ $product['sale_price'] ?? 0 }}"
                                data-product_description="{{ strLimit(strip_tags($product['description']), 200) ?? '' }}"
                                data-product_category="{{ $category }}" data-product_brands="{{ $brands }}"
                                data-product_sku="{{ $product['sku'] ?? '' }}"
                                data-product_total_rating="{{ $product['rating_count'] ?? 0 }}"
                                data-product_average_rating="{{ $product['average_rating'] ?? 0 }}"
                                data-product_additional_info="{{ base64_encode(json_encode($additionalInfo)) }}"><i
                                    class="las la-eye"></i>
                                @lang('Quick View')
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="product-card__thumb">
                <img src="{{ $image }}" alt="">
            </div>

            <div class="product-card__content">
                <h6 class="title">{{ $product['name'] ?? '' }}</h6>
                <p class="desc">{{ strLimit(strip_tags($product['short_description']), 60) ?? '' }}</p>
                <span class="product-card__price">
                    @if (isset($product['regular_price']) &&
                            ($product['regular_price'] ?? 0) > 0 &&
                            isset($product['sale_price']) &&
                            ($product['sale_price'] ?? 0 > 0))
                        <del>{{ gs('cur_sym') }}{{ showAmount($product['regular_price'], currencyFormat: false) }}</del>
                        {{ gs('cur_sym') }}{{ showAmount($product['sale_price'], currencyFormat: false) }}
                    @elseif (isset($product['price']) && ($product['price'] ?? 0) > 0)
                        {{ gs('cur_sym') }}{{ showAmount($product['price'], currencyFormat: false) }}
                    @else
                        @lang('Not provided')
                    @endif
                </span>
            </div>
        </div>
    @empty
        <div class="py-5 text-center w-100 empty-product-box">
            <img src="{{ asset('assets/images/no-data.gif') }}" class="empty-message">
            <span class="d-block">@lang('Products not available')</span>
            <span class="d-block fs-13 text-muted">@lang('There are no available data to display on this table at the moment.')</span>
        </div>
    @endforelse
@else
    <div class="py-5 text-center w-100 empty-product-box">
        <img src="{{ asset('assets/images/no-data.gif') }}" class="empty-message">
        <span class="d-block">@lang('Products not available')</span>
        <span class="d-block fs-13 text-muted">@lang('There are no available data to display on this table at the moment.')</span>
    </div>
@endif
