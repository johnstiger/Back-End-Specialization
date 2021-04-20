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
            Route::prefix('category')->group(function(){

            });
            Route::prefix('products')->group(function(){

            });
        });
        Route::namespace('Customer')->group(function(){

        });
    });
});

Route::namespace('Guest')->group(function(){
    Route::post('/login','AuthController@login');
    Route::post('/register','AuthController@register');
});

