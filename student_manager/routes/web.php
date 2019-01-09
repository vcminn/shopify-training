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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/student', function () {
    return view('student/create');
});
Route::get('/student/{id}', 'StudentController@edit');
Route::get('/students','StudentController@index');
Route::get('/delete/{id}','StudentController@destroy');
Route::resource('students', 'StudentController')->only([
    'store','destroy'
]);
Route::group(['middleware' => 'auth'], function () {
    //    Route::get('/link1', function ()    {
//        // Uses Auth Middleware
//    });

    //Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
    #adminlte_routes

});
