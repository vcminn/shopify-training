<?php

namespace App\Http\Controllers;

use App\Bundle;
use App\BundleProduct;
use App\BundleResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BundleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $store_id = session()->get('store_id');
        $bundles = Bundle::all()->where('store_id', $store_id);
        $bundle_response = BundleResponse::with('bundle')->get();
        return view('bundle/index', compact('bundles', 'bundle_response'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bundle/index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $imageName = self::fileUpload($request);
        $bundles = new Bundle([
            'store_id' => $request->get('store_id'),
            'bundle_style' => $request->get('bundle_style'),
            'active' => 1,
            'image_style' => $request->get('image_style'),
            'internal_name' => $request->get('internal_name'),
            'widget_title' => $request->get('widget_title'),
            'description' => $request->get('description'),
            'image' => $imageName,
            'discount' => $request->get('discount'),
            'discount_price' => $request->get('discount_price'),
            'base_total_price' => $request->get('base_price'),
            'test' => 1,
        ]);
        $bundles->save();

        $bundle_response = new BundleResponse([
            'store_id' => $request->get('store_id'),
            'bundle_id' => $bundles->id,
            'sales' => 0,
            'visitors' => 0,
            'added_to_cart' => 0,
        ]);
        $bundle_response->save();
        //var_dump($bundles);
        $products = session()->get('all_products');
        $selected_id = $request->input('selected_products');
        foreach ($selected_id as $id) {
            foreach ($products as $key => $product) {
                foreach ($product['variants'] as $variant) {
                    if ($id == $variant['id']) {
                        $stock = $variant["inventory_quantity"];
                        $bundle_product = new BundleProduct([
                            'store_id' => session()->get('store_id'),
                            'bundle_id' => $bundles->id,
                            'variant_id' => $id,
                            'price' => $request->get('reg_price' . $id),
                            'image' => $request->get('variant_' . $id . '_image'),
                            'quantity' => $request->get('quantity' . $id),
                            'stock' => $stock
                        ]);
                        $bundle_product->save();
                    }
                }
            }
        }
        //return null;
        return redirect('/home')->with('success', 'Added bundle');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bundle = Bundle::find($id);
        if ($bundle->active == 1) {
            return $bundle;
        } else {
            return null;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bundle = Bundle::find($id);
        $bundle_products = BundleProduct::with('bundle')->where('bundle_id', $bundle->id)->get();
        return view('bundle/edit', compact('bundle', 'bundle_products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $imageName = self::fileUpload($request);
        $bundles = Bundle::find($id);
        $bundles->bundle_style = $request->get('bundle_style');
        $bundles->image_style = $request->get('image_style');
        $bundles->internal_name = $request->get('internal_name');
        $bundles->widget_title = $request->get('widget_title');
        $bundles->description = $request->get('description');
        $bundles->base_total_price = $request->get('base_price');
        if ($imageName) {
            $bundles->image = $imageName;
        }
        $bundles->discount_price = $request->get('discount_price');
        $bundles->discount = $request->get('discount');
        $bundles->save();
        $bundle_products = BundleProduct::where('bundle_id', $bundles->id)
            ->where('store_id', session()->get('store_id'))
            ->get();
        foreach ($bundle_products as $bundle_product) {
            $variant_id = $bundle_product->variant_id;
            $bundle_product->quantity = $request->get('quantity' . $variant_id);
            $bundle_product->save();
        }


        //return null;
        return redirect('/home')->with('success', 'Stock has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bundles = Bundle::find($id);
        $bundles->delete();
        DB::table('bundle_products')->where('bundle_id', '=', $id)->delete();
        DB::table('bundle_responses')->where('bundle_id', '=', $id)->delete();
        return redirect('/home')->with('success', 'Bundle has been deleted successfully');
    }

    public function fileUpload(Request $request)
    {
        $this->validate($request, [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            return $name;
        }
    }

    function captureOrderCreateWebhook(Request $request)
    {
        $method = $request->method();
        if ($method == 'POST') {
            $data = file_get_contents('php://input');
            $data = json_decode($data, true);
            $line_items = $data["line_items"];
            $bundles = [];
            foreach ($line_items as $line_item) {
                foreach ($line_item['properties'] as $property) {
                    if ($property['name'] == '_discount') {
                        $discount = $property['value'];
                    } else if ($property['name'] == '_bundle_id') {
                        $id = $property['value'];
                    }
                }
            }
            if ($discount != 0 && $id)
                $bundles[] = $id;
        }
        foreach ($bundles as $bundle) {
            $response = BundleResponse::find($bundle);
            $response->sales += 1;
            $response->save();
        }
    }

    function addVisitors()
    {
        $domain = $_GET['domain'];
        $variant_id = $_GET['variant_id'];
        $store_id = DB::table('stores')->where('domain', '=', $domain)->value('id');
        $bundle_id = DB::table('bundle_products')->where('variant_id', '=', $variant_id)->value('bundle_id');
        $response_id = DB::table('bundle_responses')->where('store_id', '=', $store_id)->where('bundle_id', '=', $bundle_id)->value('id');
        $response = BundleResponse::find($response_id);
        $response->visitors += 1;
        $response->save();
        return $response;
    }

    function addedToCart()
    {
        $domain = $_GET['domain'];
        $variant_id = $_GET['variant_id'];
        $store_id = DB::table('stores')->where('domain', '=', $domain)->value('id');
        $bundle_id = DB::table('bundle_products')->where('variant_id', '=', $variant_id)->value('bundle_id');
        $response_id = DB::table('bundle_responses')->where('store_id', '=', $store_id)->where('bundle_id', '=', $bundle_id)->value('id');
        $response = BundleResponse::find($response_id);
        $response->added_to_cart += 1;
        $response->save();
        return $response;
    }

    function searchBundle($key, $value)
    {
        $bundles = DB::table('bundles')->where($key, 'like', '%' . $value . '%')->where('active', 1)->get();
        return $bundles;
    }

    function getBundle($bundle_id)
    {
        $variant_ids = DB::table('bundle_products')->where('bundle_id', $bundle_id)->pluck('variant_id')->toArray();
        return $variant_ids;
    }

    function getPrices($bundle_id){
        $prices = [];
        $variant_ids = $this->getBundle($bundle_id);
        foreach ($variant_ids as $variant_id){
            $prices[$variant_id]=DB::table('bundle_products')->where('variant_id', $variant_id)->where('bundle_id',$bundle_id)->value('price');
        }
        return $prices;
    }

    function getVariants()
    {
        $variant_id = $_GET['variant_id'];
        $bundle_id = DB::table('bundle_products')->where('variant_id', $variant_id)->value('bundle_id');
        $variants = DB::table('bundle_products')->select('variant_id', 'quantity')->where('bundle_id', $bundle_id)->get();
        $bundle = DB::table('bundles')->where('id', $bundle_id)->get();
        return [$variants, $bundle];
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
}
