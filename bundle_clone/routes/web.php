<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
URL::forceScheme('https');
Route::get('/', function () {
    return view('home');
});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('login/shopify', 'Auth\LoginShopifyController@redirectToProvider')->name('login.shopify');
Route::get('login/shopify/callback', 'Auth\LoginShopifyController@handleProviderCallback');
Route::get('/store/{storeId}', function () {
    // Display the store dashboard
})->middleware(['auth', 'subscribed']);
Route::get('stores/{storeId}/shopify/subscribe', function (\Illuminate\Http\Request $request, $storeId) {
    $store = \App\Store::find($storeId);
    $user = auth()->user()->providers->where('provider', 'shopify')->first();
    $shopify = \Shopify::retrieve($store->domain, $user->provider_token);

    $activated = \ShopifyBilling::driver('RecurringBilling')
        ->activate($store->domain, $user->provider_token, $request->get('charge_id'));

    $response = array_get($activated->getActivated(), 'recurring_application_charge');

    \App\Charge::create([
        'store_id' => $store->id,
        'name' => 'default',
        'shopify_charge_id' => $request->get('charge_id'),
        'shopify_plan' => array_get($response, 'name'),
        'quantity' => 1,
        'charge_type' => \App\Charge::CHARGE_RECURRING,
        'test' => array_get($response, 'test'),
        'trial_ends_at' => array_get($response, 'trial_ends_on'),
    ]);

    return redirect('/home');

})->name('shopify.subscribe');
Route::match(['get', 'post'],'webhook/shopify/order-created', 'BundleController@captureOrderCreateWebhook');
//Route::match(['get', 'post'],'webhook/shopify/product-updated', 'BundleController@captureWebhook');
Route::post('webhook/shopify/uninstall', function (\Illuminate\Http\Request $request) {
    // Handle app uninstall
})->middleware('webhook');
Route::post('webhook/shopify/gdpr/customer-redact', function (\Illuminate\Http\Request $request) {
    // Remove customer data
})->middleware('auth.webhook');

Route::get('/bundle', function () {
    return view('bundle/create');
})->name('bundle');
Route::get('/bundle/{id}', 'BundleController@edit');
Route::get('/home', 'BundleController@index');
Route::get('/delete/{id}', 'BundleController@destroy');
Route::resource('bundles', 'BundleController')->only([
    'store', 'update'
]);
Route::get('/search', function () {
    return view('bundle/search');
});

Route::get('/add-visitors/', 'BundleController@addVisitors');
Route::get('/added-to-cart/', 'BundleController@addedToCart');
Route::get('/get-other-existed/{id}', 'ProductController@getOtherExisted');
Route::get('/get-variants', 'BundleController@getVariants');
Route::get('/get-bundle/{id}', 'BundleController@getBundle');
Route::get('/get-prices/{id}', 'BundleController@getPrices');
Route::get('/generate-image','ProductController@generateImage')->name('generate-image');
Route::get('/search/load-widget','ProductController@loadWidget')->name('load-widget');
Route::get('/search/search-products','ProductController@searchProducts')->name('search-products');
Route::get('/search/table-products','ProductController@productsToTable')->name('table-products');
Route::get('/search/category','ProductController@showCateValue')->name('category');
Route::get('/show-price','ProductController@showPrice')->name('show-price');
Route::get('/show-percent','ProductController@showPercent')->name('show-percent');
Route::get('/products','ProductController@index')->name('products');
Route::get('/save', 'HomeController@toSession')->name('save');
Route::get('/sync', 'ProductController@sync')->name('sync');
Route::get('/generate-bundle/', 'ProductController@generate_bundle')->name('generate-bundle');
Route::get('/generate-pagination/', 'ProductController@generatePagination');
Route::get('/sync-price', 'ProductController@syncPrice')->name('sync-price');
Route::get('/change-state', 'BundleController@changeState')->name('change-state');
Route::get('/load-style', 'ProductController@loadStyle')->name('load-style');
Route::group(['middleware' => 'auth'], function () {
    //    Route::get('/link1', function ()    {
//        // Uses Auth Middleware
//    });

    //Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
    #adminlte_routes
});

Route::get('/oauth/authorize', 'ShopifyController@getResponse');
Route::get('/shopify', 'Auth\LoginShopifyController@getAccessToken')->name('token');

