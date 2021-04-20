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
            Route::post('/admin/register','AdminController@store');
            Route::post('/logout/{user}','AdminController@logout');
        });
        Route::namespace('Customer')->group(function(){

        });
    });
});

Route::namespace('Guest')->group(function(){
    Route::post('/login','AuthController@login');
    Route::post('/register','AuthController@register');
});

