<?php

/*
 * Taken from
 * https://github.com/laravel/framework/blob/5.3/src/Illuminate/Auth/Console/stubs/make/controllers/HomeController.stub
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $user_id = \Auth::user()->id;
        $store_id = DB::table('store_users')->where('user_id',$user_id)->value('store_id');
        session(['store_id' => $store_id]);
        return view('home');
    }

    public function toSession(Request $request)
    {
        $data = $request->get('total');
        session(['product_count'=>$data]);
    }
}
