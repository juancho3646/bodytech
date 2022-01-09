<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API;

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

/** Auth */
Route::post('auth/register', 'API\UserController@register');
Route::post('auth/login', 'API\UserController@login');
/** End Auth */

Route::group(['middleware' => ['auth:api']], function() {
    /** Products */
    Route::get('products', 'API\ProductController@index');
    Route::post('products', 'API\ProductController@store');
    Route::get('products/{id}', 'API\ProductController@show');
    Route::put('products/{id}', 'API\ProductController@update');
    Route::delete('products/{id}', 'API\ProductController@destroy');
    /** End Products */

    /** Shopping Cart */
    Route::post('shoppingCart/addItem', 'API\ShoppingCartController@addItemToShoppingCart');
    Route::delete('shoppingCart/removeItem/{id}', 'API\ShoppingCartController@removeItemFromShoppingCart');
    Route::get('shoppingCart/myShoppingCart', 'API\ShoppingCartController@showMyShoppingCart');
    Route::get('shoppingCart/payMyShoppingCart', 'API\ShoppingCartController@payMyShoppingCart');
    /** End Shopping Cart */
});
