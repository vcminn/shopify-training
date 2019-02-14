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
        self::getStoreID();
        return redirect('/home');
    }

    public function sync()
    {
        $products = self::getProducts();
        $store_id = self::getStoreID();
        $existeds = DB::table('bundle_products')->where('store_id', $store_id)->pluck('variant_id')->toArray();
        //var_dump($existeds);
        foreach ($existeds as $existed) {
            foreach ($products as $key => $product) {
                foreach ($product['variants'] as $variant) {
                    if ($existed == $variant['id']) {
                        DB::table('bundle_products')
                            ->where('store_id', $store_id)->where('variant_id', $existed)
                            ->update(['stock' => $variant["inventory_quantity"]]);
                        echo 'updated';
                    }
                }

            }
        }
        $this->checkStock();
        $this->syncPrice($products);
    }

    function syncPrice($products)
    {
        $store_id = session()->get('store_id');
        $bundle_ids = DB::table('bundles')->where('store_id', $store_id)->pluck('id')->toArray();
        foreach ($bundle_ids as $bundle_id) {
            $base_price = 0;
            $bundle_products = DB::table('bundle_products')->where('bundle_id', $bundle_id)->get();
            foreach ($bundle_products as $bundle_product) {
                foreach ($products as $key => $product) {
                    foreach ($product['variants'] as $variant) {
                        if ($variant['id'] == $bundle_product->variant_id) {
                            $base_price += $bundle_product->quantity * $variant["price"];
                        }
                    }
                }
            }
            $discount_rate = DB::table('bundles')->where('id', $bundle_id)->value('discount');
            $discount_price = round(($base_price * (100 - $discount_rate)) / 100, 2);
            DB::table('bundles')->where('id', $bundle_id)->update(['base_total_price' => $base_price]);
            DB::table('bundles')->where('id', $bundle_id)->update(['discount_price' => $discount_price]);
        }
    }

    function getVariants()
    {
        $variant_id = $_GET['variant_id'];
        $bundle_id = DB::table('bundle_products')->where('variant_id', $variant_id)->value('bundle_id');
        $variants = DB::table('bundle_products')->select('variant_id', 'quantity')->where('bundle_id', $bundle_id)->get();
        $bundle = DB::table('bundles')->where('id', $bundle_id)->get();
        return [$variants, $bundle];
    }

    function checkStock()
    {
        $stocked = null;
        $store_id = session()->get('store_id');
        $existeds = DB::table('bundle_products')->where('store_id', $store_id)->pluck('variant_id')->toArray();
        if ($existeds) {
            $bundle_id = DB::table('bundle_products')->where('store_id', $store_id)->where('variant_id', $existeds[0])
                ->value('bundle_id');
            foreach ($existeds as $existed) {
                $quantity = DB::table('bundle_products')->where('store_id', $store_id)->where('variant_id', $existed)
                    ->value('quantity');
                $stock = DB::table('bundle_products')->where('store_id', $store_id)->where('variant_id', $existed)
                    ->value('stock');
                if ($quantity > $stock) {
                    DB::table('bundles')
                        ->where('store_id', $store_id)->where('id', $bundle_id)
                        ->update(['active' => 0]);
                }
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
            //$resultProducts = self::filterExisted($resultProducts);
            //var_dump($resultProducts);
            foreach ($resultProducts as $key => $product) {
                if (count($product['variants']) <= 1) {
                    $output .= '<input type="checkbox" name="selected_products[]" value="' . $product["variants"][0]['id'] . '" id="checkbox' . $product["variants"][0]['id'] . '"/>
                        <label class="list-group-item" for="checkbox' . $product["variants"][0]['id'] . '">
                            <div class="list-item product-item selector-item clickable">
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
                                    <div class="selector-item-primary-text">' . $product["variants"][0]["price"] . '</div>
                                    <div class="selector-item-secondary-text"></div>
                                </div>
                            </div>
                        </label>';
                } else {
                    $output .= '<button type="button" data-toggle="collapse" data-target="#demo'.$product['id'].'" name="'.$product['id'].'" class="collapsible"><img
                                                src="' . $product["image"]["src"] . '" width="40" height="40">' . $product["title"] . '</button>
                        <div id="demo'.$product['id'].'" class="collapse" >';
                    foreach ($product['variants'] as $variant) {
                        $output .= '<input type="checkbox" name="selected_products[]" value="' . $variant['id'] . '" id="checkbox' . $variant['id'] . '"/>
                        <label class="list-group-item" for="checkbox' . $variant['id'] . '">
                            <div class="list-item product-item selector-item clickable">
                                <div class="list-item-body">
                                    <div class="selector-item-primary-text">' . $variant["title"] . '
                                    </div>
                                    <div class="selector-item-secondary-text"></div>
                                </div>
                                <div class="list-item-foot">
                                    <div class="selector-item-primary-text">' . $variant["price"] . '</div>
                                    <div class="selector-item-secondary-text"></div>
                                </div>
                            </div>
                        </label>
                       ';
                    }
                    $output .= ' </div>';
                }
            }
            echo $output;

        } else {
            //$products = self::filterExisted($products);
            foreach ($products as $key => $product) {
                if (count($product['variants']) <= 1) {
                    $output .= '<input type="checkbox" name="selected_products[]" value="' . $product["variants"][0]['id'] . '" id="checkbox' . $product["variants"][0]['id'] . '">
                        <label class="list-group-item" for="checkbox' . $product["variants"][0]['id'] . '">
                            <div class="list-item product-item selector-item clickable">
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
                                    <div class="selector-item-primary-text">' . $product["variants"][0]["price"] . '</div>
                                    <div class="selector-item-secondary-text"></div>
                                </div>
                            </div>
                        </label>';
                } else {
                    $output .= '<button type="button" data-toggle="collapse" data-target="#demo'.$product['id'].'" name="'.$product['id'].'"class="collapsible"><img
                                                src="' . $product["image"]["src"] . '" width="40" height="40">' . $product["title"] . '</button>
                        <div id="demo'.$product['id'].'" class="collapse" >';
                    foreach ($product['variants'] as $variant) {
                        $output .= '<input type="checkbox" name="selected_products[]" value="' . $variant['id'] . '" id="checkbox' . $variant['id'] . '">
                        <label class="list-group-item" for="checkbox' . $variant['id'] . '">
                            <div class="list-item product-item selector-item clickable">
                                <div class="list-item-body">
                                    <div class="selector-item-primary-text">' . $variant["title"] . '
                                    </div>
                                    <div class="selector-item-secondary-text"></div>
                                </div>
                                <div class="list-item-foot">
                                    <div class="selector-item-primary-text">' . $variant["price"] . '</div>
                                    <div class="selector-item-secondary-text"></div>
                                </div>
                            </div>
                        </label>
                       ';
                    }
                    $output .= ' </div>';
                }
            }
        }
        echo $output;
    }

    public function getExisted()
    {
        $store_id = session()->get('store_id');
        $existeds = DB::table('bundle_products')->where('store_id', $store_id)->pluck('variant_id')->toArray();
        return $existeds;
    }

    public function getOtherExisted($bundle_id)
    {
        $store_id = session()->get('store_id');
        $existeds = DB::table('bundle_products')->where('store_id', $store_id)->whereNotIn('bundle_id',[$bundle_id])->pluck('variant_id')->toArray();
        return $existeds;
    }

    public function productsToTable(Request $request)
    {
        $products = session()->get('all_products');
        //var_dump($products);
        if (!empty($_GET['products'])) {
            $selected_id = $_GET['products'];
            //var_dump($selected_id);
            $selectedProducts = array();
            foreach ($products as $product_key => $product) {
                foreach ($product['variants'] as $varient_key => $variant) {
                    foreach ($selected_id as $id) {
                        if ($id == $variant['id']) {
                            //echo "added";
                            $selectedProducts[$id] = $product;
                            $selectedProducts[$id]['variants'] = [];
                            $selectedProducts[$id]['variants'][] = $variant;
                        }
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
                    foreach ($product['variants'] as $variant) {
                        echo '<tr>
                            <td><img src="' . $product["image"]["src"] . '"></td>
                            <td>' . $product['title'] . ' ' . $variant["title"] . '</td>
                            <td><select class="quantity" id="quantity' . $variant['id'] . '" name="quantity' . $variant['id'] . '" onchange="refreshPrice();">';
                        for ($i = 1; $i < 11; $i++) {
                            echo '
                                <option value="' . $i . '">' . $i . '</option>
                                ';
                        }
                        $price = explode(".00", $variant["price"]);
                        echo '</select></td>
                        <td id="price' . $variant['id'] . '">' . $price[0] . '</td>
                            <td id="discount-price' . $variant['id'] . '"></td>
                            <td></td>
                            <td></td>';
                    }
                }
            }
        }
    }

    public
    function getProducts()
    {
        $shop = session()->get('shopifyUser')->nickname;
        $access_token = session()->get('access_token');
        $products = self::shopify_call($access_token, $shop, "/admin/products.json", array(), 'GET');
        $products = json_decode($products, true);
        $products = $products['products'];
        session(['all_products' => $products]);
        return $products;
    }

    public
    function getStoreID()
    {
        $user_id = Auth::user()->id;
        $store_id = DB::table('store_users')->where('user_id', $user_id)->value('store_id');
        session(['store_id' => $store_id]);
        return $store_id;
    }

    public
    function showCateValue(Request $request)
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

    public
    function showPrice(Request $request)
    {
        if (!empty($_GET['price'])) {
            $discounts = $_GET['price'];
            $index = $_GET['index'];
            echo $discounts[$index];
        } else {
            echo $_GET['price'];
        }
    }

    public
    function showPercent(Request $request)
    {
        $discount_price = (float)$_GET['discount_price'];
        $base_price = (float)$_GET['base_price'];
        $discount_percent = round(100 - ($discount_price / $base_price * 100), 2);
        //var_dump($discount_percent);
        echo $discount_percent;
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
                foreach ($products as $product_key => $product) {
                    foreach ($product['variants'] as $varient_key => $variant) {
                        foreach ($selected_id as $id) {
                            if ($id == $variant['id']) {
                                //echo "added";
                                $selectedProducts[$id] = $product;
                                $selectedProducts[$id]['variants'] = [];
                                $selectedProducts[$id]['variants'][] = $variant;
                            }
                        }
                    }
                }
                $output = '<div class="row">';
                $selected = $selectedProducts;
                //var_dump(session()->get('selectedProducts'));
                $first_value = reset($selected);
                $first_key = key($selected);
                //var_dump($first_key);
                $output .= '<div class="col-md-3"><img src="' . $first_value["image"]["src"] . '"></div>';
                unset($selected[$first_key]);
                //var_dump($selected);
                if (isset($selected)) {
                    foreach ($selected as $product) {
                        $output .= '<div class="col-md-1"><i class="fa fa-plus-circle"></i></div>';
                        $output .= '<div class="col-md-3">
                            <img src="' . $product["image"]["src"] . '">
                            
                          </div>';
                    }
                }
                $output .= '</div>';
            }
            return $output;
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
        $variant_id = $_GET['variant_id'];
        $bundle_id = DB::table('bundle_products')->where('variant_id', $variant_id)->value('bundle_id');
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
        return response()->json($bundle);
    }

    function generate_image($bundle_id)
    {
        $products = self::getProducts();
        $variants = DB::table('bundle_products')->select('variant_id')->where('bundle_id', $bundle_id)->get();
        $first_value = reset($variants);
        $first_key = key($variants);
        //var_dump($first_key);
        echo '<div class="col-md-3"><img src="' . $first_value["image"]["src"] . '"></div>';
        unset($variants[$first_key]);
        foreach ($products as $key => $product) {
            foreach ($variants as $variant) {
                if ($variant['id'] == $variant) {
                    $output = '<div class="col-md-1"><i class="fa fa-plus-circle"></i></div>';
                    $output .= '<div class="col-md-3">
                            <img src="' . $product['variants'][$key]["image"]["src"] . '">
                            
                          </div>';
                }
            }
        }
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

