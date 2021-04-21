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

Route::namespace('Guest')->group(function(){
    Route::post('/login','AuthController@login');
    Route::post('/register','AuthController@register');
});

Route::middleware('auth:sanctum')->group(function(){
    Route::namespace('User')->group(function(){
        Route::namespace('Admin')->group(function(){
            Route::prefix('admin')->group(function(){
                Route::get('/admins','AdminController@index');
                Route::post('/register','AdminController@store');
                Route::put('/update/{user}','AdminController@update');
                Route::delete('/delete/{user}','AdminController@destroy');
                Route::post('/logout/{user}','AdminController@logout');
            });
            Route::namespace('Item')->group(function(){
                Route::prefix('category')->group(function(){
                    Route::get('/all','CategoryController@index');
                    Route::post('/newCategory','CategoryController@store');
                    Route::post('/newCategoryProduct/{category}','CategoryController@storeProduct');
                });
                Route::prefix('product')->group(function(){
                    Route::get('/all','ProductController@index');
                    Route::post('/newProduct','ProductController@store');
                    Route::put('/update/{product}','ProductController@update');
                    Route::delete('/delete/{product}','ProductController@destroy');
                });
            });
        });
        Route::namespace('Customer')->group(function(){

        });
    });
});



