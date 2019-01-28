<?php

namespace App\Http\Controllers;

use Shopify;
use Illuminate\Http\Request;

Class ShopifyController extends Controller
{
    protected $shop = "penify-2.myshopify.com";
    protected $foo;
    protected $scope = ['read_products', 'read_themes'];

    public function getPermission()
    {
        $this->foo = Shopify::retrieve($this->shop, $access_token);

        return $this->foo->redirect();
    }

    public function getResponse(Request $request)
    {
        $this->getPermission();

        // Get user data, you can store it in the data base
        $user = $this->foo->auth()->getUser();

        //GET request to products.json
        return $this->foo->auth()->get('products.json', ['fields' => 'id,images,title']);
    }



    function getAccessToken()
    {
        $query = array(
            "client_id" => "44b61dc046e1a1be5abe405e55d0b309", // Your API key
            "client_secret" => "19b07c891d1d5b4208fce7051e1d2795", // Your app credentials (secret key)
            "code" => "f1cc03dc348ff08fbe67f226bdb049d6" // Grab the access key from the URL
        );

// Generate access token URL
        $access_token_url = "https://penify-2.myshopify.com/admin/oauth/access_token";

// Configure curl client and execute request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $access_token_url);
        curl_setopt($ch, CURLOPT_POST, count($query));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
        $result = curl_exec($ch);
        curl_close($ch);

// Store the access token
        $result = json_decode($result, true);
        $access_token = $result['access_token'];
        print_r($access_token);
        return $access_token;
    }

}
