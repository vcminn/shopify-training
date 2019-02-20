<?php

namespace App\Http\Middleware;

use Closure;
use App\Bundle;
use App\BundleProduct;
use App\BundleResponse;
use Illuminate\Support\Facades\DB;

class VerifyWebhook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $hmac = request()->header('x-shopify-hmac-sha256') ?: '';
        $shop = request()->header('x-shopify-shop-domain');
        $data = file_get_contents('php://input');

        // From https://help.shopify.com/api/getting-started/webhooks#verify-webhook
        $hmacLocal = base64_encode(hash_hmac('sha256', $data, env('SHOPIFY_SECRET'), true));
        if (!hash_equals($hmac, $hmacLocal) || empty($shop)) {
            // Issue with HMAC or missing shop header
            abort(401, 'Invalid webhook signature');
        }
        $response = BundleResponse::find(3);
        $response->sales += 1;
        $response->save();

        return $data;
    }
}
