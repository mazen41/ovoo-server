<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\ManageWooCommerce;
use Illuminate\Http\Request;

class EcommerceConfigurationController extends Controller
{
    use ManageWooCommerce;

    public function fetchProducts(Request $request)
    {
        $channel = $request->channel;

        return  match ($channel) {
            "woocommerce" => $this->fetchWoocommerceProducts($request),
        };
    }
}
