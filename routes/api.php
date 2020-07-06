<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user', 'UserController@store');
Route::get('/user', 'UserController@show');
Route::get('/product', 'ProductController@show');
route::get('/products', 'ProductController@index');
Route::post('/product', 'ProductController@store');
Route::put('/product', 'ProductController@update');
Route::delete('/product', 'ProductController@destroy');

Route::get('/products/filter', 'ProductController@list');
Route::post('/login', 'SessionController@login');
Route::delete('/logout', 'SessionController@logout');
Route::get('/refresh', 'SessionController@refresh');
Route::get('/profile', 'SessionController@profile');

Route::fallback(function () {
    return response()->json([
        'message' => 'Route not found'
    ], 404);
});
