<div class="modal fade custom--modal" id="ecommerceModal">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">@lang('E-Commerce Products')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>

            <div class="modal-body" id="ecommerceModalBody">
                <ul class="nav custom--tab nav-pills product-tabs mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button data-channel="woocommerce" class="nav-link active product-channel"
                            type="button">@lang('WooCommerce')
                        </button>
                    </li>
                </ul>

                <form id="productSearchForm" class="mb-3" autocomplete="off">
                    <input type="search" name="search" class="form-control form--control"
                        placeholder="@lang('Search WooCommerce Products...')">
                </form>



                <div class="product-list position-relative">
                    <div id="productWrapper" class="product-wrapper">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="offcanvas offcanvas-bottom product-details-offcanvas" tabindex="-1" id="offcanvasRight"
    aria-labelledby="offcanvasRightLabel">
    <div class="container custom-container">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasRightLabel">@lang('Porduct Details')</h5>
            <button type="button" class="btn-close text-dark" data-bs-dismiss="offcanvas" aria-label="Close"><i
                    class="las la-times"></i></button>
        </div>
    </div>
    <div class="offcanvas-body">
        <div class="container custom-container product-details-scroll">
            <div class="row g-4 product-details-container">

            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        (function($) {
            'use strict';

            const $eCommerceModal = $('#ecommerceModal');
            const $productWrapper = $eCommerceModal.find('.product-wrapper');
            const $productListDiv = $eCommerceModal.find('.product-list');

            let ecommercePlan = Number("{{ $user->ecommerce_available }}");

            const $previewContainer = $(document).find('.image-preview-container');

            window.customerFirstName  = null;
            window.customerLastName   = null;
            window.customerMobileCode = null;
            window.customerMobile     = null;
            window.customerCity       = null;
            window.customerState      = null;
            window.customerPostCode   = null;
            window.customerAddress    = null;
            window.customerCountry    = null;

            $(document).on('click', '.createOrderSendBtn', e => {
                let $orderBtn = $(e.currentTarget);
                let productId = $orderBtn.data('product_id');

                if (!productId) return notify('error', 'Product information missing');
                if (!window.customerMobileCode) return notify('error', 'Customer mobile code missing');
                if (!window.customerMobile) return notify('error', 'Customer mobile number missing');
                if (!window.customerCountry) return notify('error', ['To create a WooCommerce order via the API, the customer address is required.','Please complete the customer address from the Contact Edit section.']);

                if (!ecommercePlan) return notify('error',
                    'Currently you have not plan for e-commerce configured action. Please upgrade your plan'
                );

                $orderBtn.text('Order processing...').prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: "{{ route('user.ecommerce.woocommerce.create.order') }}",
                    data: {
                        product_id : productId,
                        customer_first_name : window.customerFirstName,
                        customer_last_name : window.customerLastName,
                        customer_mobile_code : window.customerMobileCode,
                        customer_mobile : window.customerMobile,
                        customer_city : window.customerCity,
                        customer_state : window.customerState,
                        customer_post_code : window.customerPostCode,
                        customer_address : window.customerAddress,
                        customer_country : window.customerCountry,
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: response => {
                        if (response.success) {
                            notify('success', response.message);
                            window.createdOrderData = {
                                order_url: response.order_url,
                                woo_response: response.woo_response
                            };

                            $('.message-input').attr('readonly', true);

                            $previewContainer.empty();
                            $previewContainer.append(`
                            <div class="preview-item url-preview text-dark">
                                @lang('Order Info')
                                <button class="remove-preview">&times;</button>
                            </div>
                            `);

                            $(document).find('#message-form').trigger('submit');

                        } else {
                            notify('error', response.message);
                        }

                        $orderBtn.text('Create Order & Send').prop('disabled', false);
                        $eCommerceModal.modal('hide');
                    },
                    error: () => {
                        notify('error', 'Something went wrong while creating order');
                        $orderBtn.text('Create Order & Send').prop('disabled', false);
                    }
                });
            });

            // Send info
            $eCommerceModal.on('click', '.woo-info-btn', function() {

                if (!ecommercePlan) {
                    return notify('error','Currently you have not plan for e-commerce configured action. Please upgrade your plan');
                }

                let product = $(this).data('product');
                window.wooCommerceProduct = product;
                $('.message-input').attr('readonly', true);

                const shortName = product.name.length > 8 ? product.name.substring(0, 12) + '...' : product
                    .name;

                $previewContainer.empty();
                $previewContainer.append(`
                    <div class="preview-item url-preview text-dark">
                        ${shortName}
                        <button class="remove-preview">&times;</button>
                    </div>
                    `);

                $(document).find('#message-form').trigger('submit');

                $eCommerceModal.modal('hide');
            });

            let productChannel    = "woocommerce";
            let page              = 1;
            let productIsFetching = false;
            let hasMoreProducts   = true;
            let searchValue       = '';


            const modalReset = () => {
                $('.product-channel').removeClass('active');
                $('.product-channel[data-channel="woocommerce"]').addClass('active');
                productChannel    = "woocommerce";
                page              = 1;
                productIsFetching = false;
                hasMoreProducts   = true;
                searchValue       = '';
                $eCommerceModal.find('#productSearchForm [name=search]').val('');
                removeLoadMoreButton();
            };


            $('.ecommerceBtn').on('click', () => {
                if (!ecommercePlan) {
                    return notify('error','Currently you have not plan for e-commerce configured action. Please upgrade your plan');
                }
                $eCommerceModal.modal('show');
                
                modalReset();
                loadProducts();
            });

            $eCommerceModal.on('submit', "#productSearchForm", e => {
                e.preventDefault();

                searchValue = $(e.currentTarget)
                    .find('[name=search]')
                    .val()
                    .trim();

                page = 1;
                productIsFetching = false;
                hasMoreProducts = true;

                removeLoadMoreButton();
                loadProducts();
            });

            const createProductCardSkeleton = () => `
                <div class="product-card loader-with-skeleton">
                    <div class="product-card__thumb skeleton-animation">
                        <img src="" alt="">
                    </div>

                    <div class="product-card__content">
                        <h6 class="title skeleton-animation"></h6>
                        <p class="desc skeleton-animation"></p>
                        <span class="product-card__price skeleton-animation"></span>
                    </div>
                </div>
            `;

            const productFetchLoader      = (count = 8) => createProductCardSkeleton().repeat(count);
            const removeLoadMoreButton    = () => $('.loadMoreBtnDiv').remove();
            const removeAppendedSkeletons = () => $('.loader-with-skeleton').remove();

            const addLoadMoreBtn = (nextPage) => `
                <div class="loadMoreBtnDiv text-center mt-3">
                    <button class="btn btn--base loadMoreBtn" data-next_page="${nextPage}">@lang('Load More')</button>
                </div>
            `;

            const loadProducts = () => {
                if (!hasMoreProducts || productIsFetching) return false;

                productIsFetching = true;
                

                $.ajax({
                    type: "GET",
                    url: "{{ route('user.ecommerce.fetch.products') }}",
                    dataType: "json",
                    data: {
                        "page"   : page,
                        "search" : searchValue,
                        "channel": productChannel
                    },
                    beforeSend: () => {
                        if(page == 1){
                            $productWrapper.html(productFetchLoader());
                        }else{
                            removeLoadMoreButton();
                            $productWrapper.append(productFetchLoader());
                            setTimeout(() => {
                                $('#productWrapper').scrollTop($('#productWrapper')[0].scrollHeight);
                            }, 100);
                        }
                    },
                    complete: () => {
                        productIsFetching = false;
                    },
                    success: response => {

                        if (response.status == 'success') {
                            hasMoreProducts = response.data.has_more_products;

                            if (page == 1) {
                                $productWrapper.html(response.data.html);
                            } else {
                               

                                removeAppendedSkeletons();
                                $productWrapper.append(response.data.html);
                            }

                            page = Number(response.data.nextPage) || 1;

                            if (hasMoreProducts) {
                                $productListDiv.append(addLoadMoreBtn(page));
                            } else {
                                removeLoadMoreButton();
                            }
                        } else {
                            notify('error', response.message);
                            $eCommerceModal.modal('hide');
                        }

                    },
                    error: () => {
                        $eCommerceModal.modal('hide');
                        notify('error', 'Something went wrong while fetching products from e-commerce api');
                    }
                });

            }

            $('body').on('click', '.product-channel', e => {
                const $target  = $(e.currentTarget);
                productChannel = $target.data('channel');

                $('.product-channel').removeClass('active');
                $target.addClass('active');

                page            = 1;
                hasMoreProducts = true;

                removeLoadMoreButton();
                loadProducts();
            });

            $(document).on('click', '.loadMoreBtn', e => {
                page = $(e.currentTarget).data('next_page');
                loadProducts();
            });

            const ratingBuild = (rating = 0) => {
                let full  = Math.floor(rating);
                let half  = rating % 1 >= 0.5;
                let empty = 5 - full - (half ? 1 : 0);

                return '★'.repeat(full) + (half ? '⯨' : '') + '☆'.repeat(empty);
            }

            const productPrice = (data) => {
                if (data.regular_price && data.sale_price) {
                    return `
                        <del>${data.cur_sym}${(data.regular_price).toFixed(data.fraction_digit)}</del> ${data.cur_sym}${(data.sale_price).toFixed(data.fraction_digit)}
                    `;
                } else {
                    return `${data.cur_sym}${(data.price).toFixed(data.fraction_digit)}`;
                }
            }
            const additionalInfo = (additionalInfo = null) => {
                if (additionalInfo && Object.keys(additionalInfo).length > 0) {
                    return `
                    <div class="col-12">
                        <div class="products-description pt-0">
                            <h4 class="title">@lang('Additional information')</h4>
                            <ul class="products-description-list">
                                ${Object.entries(additionalInfo)
                                    .map(([key, value]) => `
                                        <li>
                                            <span class="title">${key}</span>
                                            <span class="value">${value}</span>
                                        </li>
                                    `).join('')}
                            </ul>
                        </div>
                    </div>
                    `
                }

                return ``;
            }

            const buildProductDetailsForCanvas = (data) => `
                <div class="col-md-5">
                    <div class="product-gallery">
                        <img src="${data.image}" alt="">
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="product-details">
                        <div class="product-header">
                            <h4 class="product-title mb-2">${data.name}</h4>

                            <div class="ratings-area">
                                <span class="ratings">
                                    ${ratingBuild(data.average_rating)}
                                </span>
                                <span> | ${data.total_rating || 0} @lang('Review')</span>
                            </div>

                            <div class="d-flex flex-wrap align-items-center gap-2 product-detail-price">
                                <span class="product-price">

                                    ${productPrice(data)}
                                </span>
                            </div>
                        </div>

                        <p class="product-summary">${data.description}</p>

                        <div class="product-types d-flex flex-column">
                            <span><b class="product-details-label">@lang('Categories'):</b> ${data.category}</span>
                            <span><b class="product-details-label">@lang('Brand'):</b> ${data.brands}</span>
                            <span><b class="product-details-label">@lang('SKU'):</b> ${data.sku || 'N/A'}</span>
                        </div>
                    </div>
                </div>

                ${additionalInfo(data.additional_info)}
            `;

            $(document).on('click', '.productOffCanvasBtn', e => {
                const $btn = $(e.currentTarget);

                const data = {
                    image          : $btn.data('product_image'),
                    name           : $btn.data('product_name'),
                    price          : Number($btn.data('product_price')),
                    regular_price  : Number($btn.data('product_regular_price')),
                    sale_price     : Number($btn.data('product_sale_price')),
                    description    : $btn.data('product_description'),
                    category       : $btn.data('product_category'),
                    brands         : $btn.data('product_brands'),
                    sku            : $btn.data('product_sku'),
                    total_rating   : Number($btn.data('product_total_rating')),
                    average_rating : Number($btn.data('product_average_rating')),
                    additional_info: JSON.parse(atob($btn.data('product_additional_info'))),
                    cur_sym        : "{{ gs('cur_sym') }}",
                    fraction_digit : parseInt("{{ gs('allow_precision') }}")
                };

                $('.product-details-container').html(buildProductDetailsForCanvas(data));

                const offcanvas = new bootstrap.Offcanvas('#offcanvasRight');
                offcanvas.show();
            });

        })(jQuery);
    </script>
@endpush
