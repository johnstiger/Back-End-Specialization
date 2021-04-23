<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::namespace('Guest')->group(function(){
    Route::post('/login','AuthController@login');
    Route::post('/register','AuthController@register');
    Route::prefix('search')->group(function(){
        Route::post('/products','SearchEngineController@Products');
        Route::post('/products/{category}','SearchEngineController@productByCategory');
    });
});


Route::middleware('auth:sanctum')->group(function(){
    Route::post('/search/customers','Guest\SearchEngineController@Customers');
    Route::post('/search/admin','Guest\SearchEngineController@Admins');
    Route::post('/search/category','Guest\SearchEngineController@Category');
    Route::namespace('User')->group(function(){

        Route::namespace('Admin')->group(function(){
            Route::prefix('admin')->group(function(){
                Route::get('/admins','AdminController@index');
                Route::get('/show/{admin}','AdminController@show');
                Route::post('/register','AdminController@store');
                Route::put('/update/{user}','AdminController@update');
                Route::delete('/delete/{user}','AdminController@destroy');
                Route::post('/logout/{user}','AdminController@logout');
            });
            Route::namespace('Item')->group(function(){
                Route::prefix('category')->group(function(){
                    Route::get('/all','CategoryController@index');
                    Route::get('/show/{category}','CategoryController@show');
                    Route::post('/newCategory','CategoryController@store');
                    Route::post('/newCategoryProduct/{category}','CategoryController@storeProduct');
                    Route::put('/update/{category}','CategoryController@update');
                    Route::delete('/delete/{category}','CategoryController@destroy');
                });
                Route::prefix('product')->group(function(){
                    Route::get('/all','ProductController@index');
                    Route::get('/show/{product}','ProductController@show');
                    Route::post('/newProduct','ProductController@store');
                    Route::put('/update/{product}','ProductController@update');
                    Route::delete('/delete/{product}','ProductController@destroy');
                });
            });
        });
        Route::namespace('Customer')->group(function(){
            Route::prefix('cart')->group(function(){
                Route::get('/show/{customer}','CartController@show');
                Route::post('/add/{customer}/{product}','CartController@store');
                Route::put('/update{customer}/{product}','CartController@update');
                Route::delete('/delete/{customer}/{product}','CategoryController@destroy');
            });
        });
    });
});



