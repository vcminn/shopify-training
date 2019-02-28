<?php

namespace App\Http\Controllers\Auth;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User as User;
use App\UserProvider as UserProvider;
use App\Store as Store;
use Illuminate\Support\Facades\Auth;

class LoginShopifyController extends Controller
{

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider(Request $request)
    {
        $this->validate($request, [
            'domain' => 'string|required'
        ]);

        $config = new \SocialiteProviders\Manager\Config(
            env('SHOPIFY_KEY'),
            env('SHOPIFY_SECRET'),
            env('SHOPIFY_REDIRECT'),
            ['subdomain' => $request->get('domain')]
        );
        return Socialite::with('shopify')
            ->setConfig($config)
            ->scopes(['read_products','write_products','read_orders', 'write_orders'])
            ->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $shopifyUser = Socialite::driver('shopify')->user();
        // Create user
        $user = User::firstOrCreate([
            'name' => $shopifyUser->name,
            'email' => $shopifyUser->email,
            'password' => '',
        ]);

        // Store the OAuth Identity
        UserProvider::firstOrCreate([
            'user_id' => $user->id,
            'provider' => 'shopify',
            'provider_user_id' => $shopifyUser->id,
            'provider_token' => $shopifyUser->token,
        ]);

        // Create shop
        $store = Store::firstOrCreate([
            'name' => $shopifyUser->name,
            'domain' => $shopifyUser->nickname,
        ]);

        //Attabui shop to user
        $store->users()->syncWithoutDetaching([$user->id]);
        $access_token = $shopifyUser->accessTokenResponseBody['access_token'];

        session(['shopifyUser' => $shopifyUser]);
        session(['access_token'=> $access_token]);

        Auth::login($user, true);
//        dispatch(new \App\Jobs\RegisterProductUpdateShopifyWebhook($store->domain, $shopifyUser->token, $store));
//        dispatch(new \App\Jobs\RegisterUninstallShopifyWebhook($store->domain, $shopifyUser->token, $store));
        dispatch(new \App\Jobs\RegisterOrderCreateShopifyWebhook($store->domain, $shopifyUser->token, $store));
        return redirect('/products');
    }

}
