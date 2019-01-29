<?php

namespace App\Http\Controllers;

use App;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        self::getProducts();
        return redirect('/home');
    }

    public function sync()
    {
        $products = self::getProducts();
        //var_dump($products);
        $store_id = session()->get('store_id');
        $existeds = DB::table('bundle_products')->where('store_id', $store_id)->pluck('product_id')->toArray();
        //var_dump($existeds);
        foreach ($existeds as $existed) {
            foreach ($products as $key => $product) {
                if ($existed == $product['id']) {
                    DB::table('bundle_products')
                        ->where('store_id', $store_id)->where('product_id', $existed)
                        ->update(['stock' => $products[$key]["variants"][0]["inventory_quantity"]]);
                    echo 'updated';
                }
            }
        }
        $this->checkStock();
        $this->syncPrice();
    }

    function syncPrice()
    {
        $products = self::getProducts();
        $store_id = session()->get('store_id');
        $bundle_ids = DB::table('bundles')->where('store_id', $store_id)->pluck('id')->toArray();
        foreach ($bundle_ids as $bundle_id) {
            $base_price = 0;
            $bundle_products = DB::table('bundle_products')->where('bundle_id', $bundle_id)->get();
            foreach ($bundle_products as $bundle_product) {
                foreach ($products as $key => $product) {
                    if ($product['id'] == $bundle_product->product_id) {
                        $base_price += $bundle_product->quantity * $product["variants"][0]["price"];
                        //var_dump($product);
                    }
                }
            }
            DB::table('bundles')->where('id', $bundle_id)->update(['base_total_price' => $base_price]);
        }
    }

    function checkStock()
    {
        $stocked = null;
        $store_id = session()->get('store_id');
        $existeds = DB::table('bundle_products')->where('store_id', $store_id)->pluck('product_id')->toArray();
        $bundle_id = DB::table('bundle_products')->where('store_id', $store_id)->where('product_id', $existeds[0])
            ->value('bundle_id');
        foreach ($existeds as $existed) {
            $quantity = DB::table('bundle_products')->where('store_id', $store_id)->where('product_id', $existed)
                ->value('quantity');
            $stock = DB::table('bundle_products')->where('store_id', $store_id)->where('product_id', $existed)
                ->value('stock');
            if ($quantity > $stock) {
                DB::table('bundles')
                    ->where('store_id', $store_id)->where('id', $bundle_id)
                    ->update(['active' => 0]);
            }
        }
    }

    function changeState()
    {
        if (isset($_GET['value'])) {
            $store_id = session()->get('store_id');
            $value = $_GET['value'];
            $bundle_id = $_GET['id'];
            DB::table('bundles')
                ->where('store_id', $store_id)->where('id', $bundle_id)
                ->update(['active' => $value]);
        }
    }

    public function searchProducts(Request $request)
    {
        $products = session()->get('all_products');

        //var_dump($products);
        $resultProducts = array();
        $output = "";
        if (!empty($_GET["search"])) {
            foreach ($products as $key => $product) {
                if (strpos(strtolower($product['title']), strtolower($_GET["search"])) !== false) {
                    $resultProducts[$key] = $product;
                }
            }
            $resultProducts = self::filterExisted($resultProducts);
            //var_dump($resultProducts);
            foreach ($resultProducts as $key => $product) {
                $output .= '<input type="checkbox" name="selected_products[]" value="' . $product['id'] . '" id="checkbox' . $product['id'] . '"/>
                        <label class="list-group-item" for="checkbox' . $product['id'] . '">
                            <div class="list-item product-item selector-item clickable"
                                 data-bold-component-id="product-selector-product-item">
                                <div class="list-item-header">
                                    <div class="list-item-image-container">
                                        <div class="list-item-image"><img
                                                src="' . $product["image"]["src"] . '"></div>
                                    </div>
                                </div>
                                <div class="list-item-body">
                                    <div class="selector-item-primary-text">' . $product["title"] . '
                                    </div>
                                    <div class="selector-item-secondary-text"></div>
                                </div>
                                <div class="list-item-foot">
                                    <div
                                        class="selector-item-primary-text">' . $product["variants"][0]["price"] . '</div>
                                    <div class="selector-item-secondary-text"></div>
                                </div>
                            </div>
                        </label>';
            }
            echo $output;
        } else {
            $products = self::filterExisted($products);
            foreach ($products as $key => $product) {
                $output .= '<input type="checkbox" name="selected_products[]" value="' . $product['id'] . '" id="checkbox' . $product['id'] . '"/>
                        <label class="list-group-item" for="checkbox' . $product['id'] . '">
                            <div class="list-item product-item selector-item clickable"
                                 data-bold-component-id="product-selector-product-item">
                                <div class="list-item-header">
                                    <div class="list-item-image-container">
                                        <div class="list-item-image"><img
                                                src="' . $product["image"]["src"] . '"></div>
                                    </div>
                                </div>
                                <div class="list-item-body">
                                    <div class="selector-item-primary-text">' . $product["title"] . '
                                    </div>
                                    <div class="selector-item-secondary-text"></div>
                                </div>
                                <div class="list-item-foot">
                                    <div
                                        class="selector-item-primary-text">' . $product["variants"][0]["price"] . '</div>
                                    <div class="selector-item-secondary-text"></div>
                                </div>
                            </div>
                        </label>';
            }
            echo $output;
        }
    }

    public function filterExisted($resultProducts)
    {
        $store_id = session()->get('store_id');
        $existeds = DB::table('bundle_products')->where('store_id', $store_id)->pluck('product_id')->toArray();
        foreach ($resultProducts as $id => $product) {
            if (in_array($product['id'], $existeds) || $product["variants"][0]["inventory_quantity"] == 0) {
                unset($resultProducts[$id]);
            }
        }
        return $resultProducts;
    }

    public function productsToTable(Request $request)
    {
        $products = session()->get('all_products');
        //var_dump($_GET);
        if (!empty($_GET['products'])) {
            $selected_id = $_GET['products'];
            //var_dump($selected_id);
            $selectedProducts = array();
            foreach ($products as $key => $product) {
                foreach ($selected_id as $id) {
                    if ($id == $product['id']) {
                        //echo "added";
                        $selectedProducts[$id] = $product;
                    }
                }
            }
            //var_dump($selectedProducts);
            //var_dump(session()->get('selectedProducts'));
            //var_dump($selectedProducts);
            if (!empty($selectedProducts)) {
                echo '<tbody>
                        <tr>
                            <th style="width: 5%">Image</th>
                            <th style="width: 45%">Title</th>
                            <th style="width: 10%">Quantity</th>
                            <th style="width: 15%">Regular Price</th>
                            <th style="width: 15%">Bundle Price</th>
                            <th style="width: 5%">Edit</th>
                            <th style="width: 5%">Remove</th>
                        </tr>';
                //var_dump($products);
                foreach ($selectedProducts as $key => $product) {
                    echo '<tr>
                            <td><img src="' . $product["image"]["src"] . '"></td>
                            <td>' . $product["title"] . '</td>
                            <td><select id="quantity' . $product['id'] . '" name="quantity' . $product['id'] . '">';
                    for ($i = 1; $i < 11; $i++) {
                        echo '
                                <option value="' . $i . '">' . $i . '</option>
                                ';
                    }
                    $price = explode(".00", $product["variants"][0]["price"]);
                    echo '</select></td>
                        <td id="price' . $product['id'] . '">' . $price[0] . '</td>
                            <td id="discount-price' . $product['id'] . '"></td>
                            <td></td>
                            <td></td>';
                }
            }
        }
    }

    public function getProducts()
    {
        $shop = session()->get('shopifyUser')->nickname;
        $access_token = session()->get('access_token');
        $user_id = Auth::user()->id;
        $store_id = DB::table('store_users')->where('user_id', $user_id)->value('store_id');
        session(['store_id' => $store_id]);
        // Run API call to get all products
        $products = self::shopify_call($access_token, $shop, "/admin/products.json", array(), 'GET');
        $products = json_decode($products, true);
        $products = $products['products'];
        //print_r($products);
//        $vendors = array();
//        $types = array();
//        $vendor_types = self::shopify_call($access_token, $shop, "/admin/products.json?fields=vendor,product_type", array(), 'GET');
//        $vendor_types = json_decode($vendor_types, true);
//        $vendor_types = $vendor_types['products'];
//        //print_r($vendor_types);
//        foreach ($vendor_types as $vendor_type) {
//            if (!in_array($vendor_type['vendor'], $vendors)) {
//                $vendors[] = $vendor_type['vendor'];
//            }
//            if (!in_array($vendor_type['product_type'], $types)) {
//                $types[] = $vendor_type['product_type'];
//            }
//        }
////        print_r($types);
////        print_r($vendors);
//        session(['vendor' => $vendors]);
//        session(['product_type' => $types]);
        session(['all_products' => $products]);
        return $products;
    }

    public function showCateValue(Request $request)
    {
        $category = $_GET['category'];
        if ($category == 'vendor') {
            $cateValues = session()->get('vendor');
        } else if ($category == 'product_type') {
            $cateValues = session()->get('product_type');
        }
        //var_dump($cateValues);
        echo '<option>--</option>';
        foreach ($cateValues as $cateValue) {
            if (!empty($cateValue)) {
                //var_dump($cateValue);
                echo '<option value="' . $cateValue . '">' . $cateValue . '</option>';
            }
        }
    }

    public function showPrice(Request $request)
    {
        if (!empty($_GET['price'])) {
            $discounts = $_GET['price'];
            $index = $_GET['index'];
            //var_dump($_GET['price']);
            echo $discounts[$index];
        } else {
            echo '';
        }
    }

    function loadWidget(Request $request)
    {
        if (isset($_GET['style']) && $_GET['style'] == 1) {
            echo '<img src="' . $_GET['img_src'] . '">';
        } else {
            $products = session()->get('all_products');
            if (!empty($_GET['products'])) {
                $selected_id = $_GET['products'];
                //var_dump($selected_id);
                $selectedProducts = array();
                foreach ($products as $key => $product) {
                    foreach ($selected_id as $id) {
                        if ($id == $product['id']) {
                            $selectedProducts[$id] = $product;
                        }
                    }
                }
                echo '<div class="row">';
                $selected = $selectedProducts;
                //var_dump(session()->get('selectedProducts'));
                $first_value = reset($selected);
                $first_key = key($selected);
                //var_dump($first_key);
                echo '<div class="col-md-3"><img src="' . $first_value["image"]["src"] . '"></div>';
                unset($selected[$first_key]);
                //var_dump($selected);
                if (isset($selected)) {
                    foreach ($selected as $product) {
                        echo '<div class="col-md-1"><i class="fa fa-plus-circle"></i></div>';
                        echo '<div class="col-md-3">
                            <img src="' . $product["image"]["src"] . '">
                            
                          </div>';
                    }
                }
                echo '</div>';
            }
        }
    }

    function loadStyle()
    {
        if (isset($_GET['value'])) {
            $value = $_GET['value'];
            $save = $_GET['bundle_base'] - $_GET['bundle_price'];
            if ($value == 0) {
                echo ' <button type="button" class="btn btn-primary" > Add Bundle to Cart <br> <strike>' . $_GET['bundle_base'] . '</strike>&nbsp' . $_GET['bundle_price'] . ' <br> Save ' . $save . '</button>';
            } else {
                echo ' <button type="button" class="btn btn-primary" > Add Bundle to Cart <br> Save ' . $_GET['discount'] . '%</button>';
            }
        }
    }

    function generate_bundle()
    {
        $product_id = $_GET['product_id'];
        $bundle_id = DB::table('bundle_products')->where('product_id', $product_id)->value('bundle_id');
        $curl = curl_init('https://bundle.local/api/bundles/' . $bundle_id);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);
        $bundle = curl_exec($curl);
        curl_close($curl);
        $bundle = json_decode($bundle);
        $bundle_image = '<div class="container" id ="full_widget" style="width: 450px; height:auto">
                        <div class="row justify-content-md-center">
                            <div class="col col-lg-6" style="border:solid rgba(0,0,0,0.47) 2px;" id="widget">
                            <img height="420" width="420" src="/images/'.$bundle->image.'">
                            </div>
                            </div>';
        if ($bundle ->bundle_style == 0){
            $bundle_button = '<div class="row row justify-content-md-center">
                            <div class="col align-self-center" id="style_announce">
                            <button type="button" class="btn btn-primary" > Add Bundle to Cart <br> 
                            <strike>' . $bundle->base_total_price*(100-$bundle->discount)/100 . '</strike>&nbsp' . $bundle->base_total_price . ' <br> 
                            Save ' . $bundle->base_total_price*$bundle->discount/100 . '</button>
                            </div>
                            </div>
                            </div>';
        } else{
            $bundle_button = '<div class="row row justify-content-md-center">
                            <div class="col align-self-center" id="style_announce">
                            <button type="button" class="btn btn-primary" > Add Bundle to Cart <br> Save ' . $bundle->discount . '%</button>
                            </div>
                            </div>
                            </div>';
        }
        $full_widget = $bundle_image.$bundle_button;

        return $full_widget;

    }

    function shopify_call($token, $shop, $api_endpoint, $query = array(), $headers = array(), $method = 'GET')
    {

        // Build URL
        $url = "https://" . $shop . $api_endpoint;
        if (is_null($query)) $url = $url . "?" . http_build_query($query);

        // Configure cURL
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 3);
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'My New Shopify App v.1');
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);

        // Setup headers
        $request_headers[] = "Content-type: text/plain";
        $request_headers[] = "X-Shopify-Access-Token: " . $token;
        curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);

        // Send request to Shopify and capture any errors
        $response = curl_exec($curl);
        $error_number = curl_errno($curl);
        $error_message = curl_error($curl);

        // Close cURL to be nice
        curl_close($curl);

        // Return an error is cURL has a problem
        if ($error_number) {
            return $error_message;
        } else {

            // No error, return Shopify's response by parsing out the body
            $response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);
            return $response[1];
        }
    }
}
