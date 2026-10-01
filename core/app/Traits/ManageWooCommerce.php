<?php

namespace App\Traits;

use App\Constants\Status;
use App\Models\EcommerceConfiguration;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

trait ManageWooCommerce
{
    public function wooCommerceConfig()
    {
        $pageTitle       = 'WooCommerce Setting';
        $ecommerceConfig = EcommerceConfiguration::where('user_id', getParentUser()->id)->where('provider', Status::WOO_COMMERCE)->first();
        return view('Template::user.ecommerce.woo_commerce_config', compact('pageTitle', 'ecommerceConfig'));
    }

    public function wooCommerceConfigStore(Request $request)
    {
        $request->validate([
            'domain_name'     => 'required|string',
            'consumer_key'    => 'required|string',
            'consumer_secret' => 'required|string',
        ]);

        $user = getParentUser();

        if (!featureAccessLimitCheck($user->ecommerce_available)) {
            return responseManager('not_available', 'Your current plan does not support ecommerce. Please upgrade your plan.');
        }

        $ecommerceConfig = EcommerceConfiguration::where('user_id', $user->id)->where('provider', Status::WOO_COMMERCE)->first();

        if (!$ecommerceConfig) {
            $ecommerceConfig           = new EcommerceConfiguration();
            $ecommerceConfig->user_id  = $user->id;
            $ecommerceConfig->provider = Status::WOO_COMMERCE;
        }

        $ecommerceConfig->config   = [
            'domain_name'     => $request->domain_name,
            'consumer_key'    => $request->consumer_key,
            'consumer_secret' => $request->consumer_secret
        ];

        $ecommerceConfig->save();

        $notify[] = ['success', 'Woo-Commerce configuration stored successfully'];
        return back()->withNotify($notify);
    }

    public function wooCommerceProducts(Request $request)
    {
        $config = $this->getWooCommerceConfig();


        if (isset($config['error'])) {
            $notify[] = ['error', $config['error']];
            return back()->withNotify($notify);
        }

        extract($config);

        $perPage = getPaginate();
        $page    = $request->page ?? 1;
        $search  = $request->search ?? '';

        $cacheKey = "woocommerce_products_page_{$page}_search_" . md5($search);
        $cacheTTL = 7200;

        $cacheKeysIndex = Cache::get('woocommerce_products_cache_keys', []);


        if (!in_array($cacheKey, $cacheKeysIndex)) {
            $cacheKeysIndex[] = $cacheKey;
            Cache::put('woocommerce_products_cache_keys', $cacheKeysIndex, now()->addDays(7));
        }


        $response = Cache::remember($cacheKey, $cacheTTL, function () use ($domain, $consumerKey, $consumerSecret, $perPage, $page, $search) {

            $url = "{$domain}/wp-json/wc/v3/products?per_page={$perPage}&page={$page}";
            if ($search) $url .= '&search=' . urlencode($search);

            return $this->wooRequest($url, $consumerKey, $consumerSecret);
        });

        if (isset($response['error'])) {
            $products = collect([]);
            $paginator = new LengthAwarePaginator($products, 0, $perPage, $page, [
                'path'  => $request->url(),
                'query' => $request->query()
            ]);
            $errorMessage = $response['error'];
        } else {
            $products = collect($response['data']);
            $paginator = new LengthAwarePaginator($products, $response['total'], $perPage, $page, [
                'path'  => $request->url(),
                'query' => $request->query()
            ]);
            $errorMessage = null;
        }


        return view('Template::user.ecommerce.products', [
            'pageTitle' => 'WooCommerce Products',
            'products'  => $paginator,
            'errorMessage' => $errorMessage
        ]);
    }

    public function wooCommerceClearCache()
    {
        $cacheKeysIndex = Cache::get('woocommerce_products_cache_keys', []);

        foreach ($cacheKeysIndex as $key) Cache::forget($key);
        Cache::forget('woocommerce_products_cache_keys');

        $notify[] = ['success', 'WooCommerce products cache cleared successfully'];
        return back()->withNotify($notify);
    }

    public function fetchWoocommerceProducts($request)
    {
        $config = $this->getWooCommerceConfig();
        if (isset($config['error'])) return apiResponse('error', 'error', [$config['error']]);

        extract($config);

        $limit = 20;
        $page  = (int) ($request->page ?? 1);

        $url   = "{$domain}/wp-json/wc/v3/products?per_page={$limit}&page={$page}";
        if ($request->search) $url .= '&search=' . urlencode($request->search);

        $response = $this->wooRequest($url, $consumerKey, $consumerSecret);
        if (isset($response['error'])) return apiResponse('error', 'error', [$response['error']]);

        $hasMoreProducts = $request->page < $response['totalPages'];
        $html = view('Template::user.inbox.ecommerce.products', [
            'products' => $response['data']
        ])->render();

        $notify[] = "The product fetch successfully";
        return apiResponse('success', 'success', $notify, [
            'html'              => $html,
            'has_more_products' => $hasMoreProducts,
            'nextPage'          => $page + 1
        ]);
    }

    public function createWooOrder(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'product_id'           => 'required|integer|gt:0',
            'customer_first_name'  => 'nullable|string',
            'customer_last_name'   => 'nullable|string',
            'customer_mobile_code' => 'required',
            'customer_mobile'      => 'required',
            'customer_city'        => 'nullable|string',
            'customer_post_code'   => 'nullable|string',
            'customer_address'     => 'nullable|string',
            'customer_state'       => 'nullable|string',
            'customer_country'     => 'required|string'
        ]);

        if ($validate->fails()) return responseBack(false, $validate->errors());

        $config = $this->getWooCommerceConfig();
        if (isset($config['error'])) return responseBack(false, $config['error']);

        extract($config);

        $url = "{$domain}/wp-json/wc/v3/orders";

        $body = [
            'payment_method'       => 'cod',
            'payment_method_title' => 'Cash on Delivery',
            'set_paid'             => false,
            'billing' => [
                'first_name' => $request->customer_first_name ?? 'Customer',
                'last_name'  => $request->customer_last_name ?? 'Customer',
                'address_1'  => $request->customer_address ?? '',
                'city'       => $request->customer_city ?? 'N/A',
                'state'      => $request->customer_state ?? 'N/A',
                'postcode'   => $request->customer_post_code ?? '0000',
                'country'    => $request->customer_country ?? 'US',
                'email'      => $request->customer_email ?? 'customer@example.com',
                'phone'      => $request->customer_mobile_code . $request->customer_mobile,
            ],

            'shipping' => [
                'first_name' => $request->customer_first_name ?? 'Customer',
                'last_name' =>  $request->customer_last_name ?? 'Customer',
                'address_1'  => $request->customer_address ?? 'N/A',
                'city'       => $request->customer_city ?? 'N/A',
                'state'      => $request->customer_state ?? 'N/A',
                'postcode'   => $request->customer_post_code ?? '0000',
                'country'    => $request->customer_country ?? 'US',
            ],

            'line_items' => [
                [
                    'product_id' => $request->product_id,
                    'quantity'   => 1
                ]
            ]
        ];

        try {
            $response = Http::withBasicAuth($consumerKey, $consumerSecret)->timeout(10)->post($url, $body);

            if ($response->failed()) {
                return responseBack(false, 'Woo-Commerce could not create the order');
            }

            $data     = $response->json();
            $orderUrl = @$data['payment_url'];

            if (!$orderUrl) {
                return responseBack(false, 'Order not created');
            }

            return response()->json([
                'success'      => true,
                'message'      => 'Order created successfully',
                'order_id'     => $data['id'],
                'order_key'    => $data['order_key'],
                'order_url'    => $orderUrl,
                'woo_response' => $data
            ]);
        } catch (\Exception $exp) {
            return responseBack(false, $exp->getMessage());
        }
    }

    private function getWooCommerceConfig()
    {
        $user   = getParentUser();
        $config = EcommerceConfiguration::where('user_id', $user->id)
            ->where('provider', Status::WOO_COMMERCE)
            ->first();

        if (!$config) return ['error' => 'Woo-Commerce configuration not found'];

        $domain         = $config?->config?->domain_name     ?? null;
        $consumerKey    = $config?->config?->consumer_key    ?? null;
        $consumerSecret = $config?->config?->consumer_secret ?? null;

        if (!$domain)         return ['error' => 'Woo-Commerce domain not found'];
        if (!$consumerKey)    return ['error' => 'Consumer key not found'];
        if (!$consumerSecret) return ['error' => 'Consumer secret not found'];

        if (!$user->ecommerce_available) {
            return ['error' => 'Your active plan does not support Woo-Commerce integration'];
        }

        return [
            'domain'         => rtrim($domain, '/'),
            'consumerKey'    => $consumerKey,
            'consumerSecret' => $consumerSecret
        ];
    }

    private function wooRequest($url, $consumerKey, $consumerSecret)
    {

        try {
            $response = Http::withBasicAuth($consumerKey, $consumerSecret)
                ->get($url);

            $data = $response->json();
            
            if ($response->failed()) {

                if (isset($data['message'])) {
                    $errorMessage = $data['message'];
                } else {
                    $errorMessage = "Invalid Woo-Commerce API response";
                }
                return ['error' => "$errorMessage, Please make sure your Woo-Commerce API details is correct and has the necessary permissions."];
                return ['error' => $errorMessage];
            }
            return [
                'data'       => $data,
                'total'      => (int) ($response->header('X-WP-Total') ?? 0),
                'totalPages' => (int) ($response->header('X-WP-TotalPages') ?? 0),
            ];
        } catch (\Exception $exp) {
            return ['error' => $exp->getMessage()];
        }
    }
}
