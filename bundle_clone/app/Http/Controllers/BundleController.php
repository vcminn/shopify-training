<?php

namespace App\Http\Controllers;

use App\Bundle;
use App\BundleProduct;
use App\BundleResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BundleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bundles = Bundle::all();
        $bundle_response = BundleResponse::with('bundle')->get();
        return view('bundle/index', compact('bundles','bundle_response'));
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
            'sales'=>0,
            'visitors'=>0,
            'added_to_cart'=>0,
        ]);
        $bundle_response->save();
        //var_dump($bundles);
        $products = session()->get('all_products');
        $selected_id = $request->input('selected_products');
        foreach ($selected_id as $id) {
            foreach ($products as $key => $product) {
                if ($id == $product['id']) {
                    $stock = $product["variants"][0]["inventory_quantity"];
                    //    echo $stock;
                    echo $request->get('quantity' . $id);
                    $bundle_product = new BundleProduct([
                        'store_id' => session()->get('store_id'),
                        'bundle_id' => $bundles->id,
                        'product_id' => $id,
                        'quantity' => $request->get('quantity' . $id),
                        'stock' => $stock
                    ]);
                    $bundle_product->save();
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
        return Bundle::find($id);
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
        $bundle_products = BundleProduct::with('bundle')->where('bundle_id',$bundle->id)->get();
        return view('bundle/edit',compact('bundle', 'bundle_products'));
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

        if ($imageName) {
            $bundles->image = $imageName;
        }
        $bundles->discount_price = $request->get('discount_price');
        $bundles->discount = $request->get('discount');
        $bundles->save();
        $bundle_products = BundleProduct::where('bundle_id', $bundles->id)
            ->where('store_id', session()->get('store_id'))
            ->get();
        foreach ($bundle_products as $bundle_product){
            $product_id = $bundle_product->product_id;
            $bundle_product -> quantity = $request ->get('quantity'.$product_id);
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

    function searchBundle($key, $value)
    {
        $bundles = DB::table('bundles')->where($key,'like','%'.$value.'%')->where('active',1)->get();
        return $bundles;
    }

}
