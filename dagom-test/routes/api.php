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
    // Unauthorized
    Route::get('/UnAuthorized','AuthController@Unauthorized')->name('unauthorized');

    //LogIn and Register Verification
    Route::post('/login','AuthController@login');
    Route::post('/register','AuthController@register')->name('verification.send');
    Route::get('/email/verify/{id}/{hash}','VerificationController@verifyEmail')->middleware(['auth:sanctum','signed'])->name('verification.verify');

    // Showing Products
    Route::get('/home','ProductController@index');
    Route::get('/dagom/{product}','ProductController@show');

    // Search Products or Category
    Route::prefix('search')->group(function(){
        Route::post('/products','SearchEngineController@Products');
        Route::post('/products/{category}','SearchEngineController@productByCategory');
    });
});

// User
Route::middleware('auth:sanctum')->group(function(){
    Route::namespace('Guest')->group(function(){
        Route::prefix('search')->group(function(){
            Route::post('/customers','SearchEngineController@Customers');
            Route::post('/admin','SearchEngineController@Admins');
            Route::post('/category','SearchEngineController@Category');
        });
        Route::any('/logout','AuthController@logout');
    });
    Route::namespace('User')->group(function(){
        Route::namespace('Admin')->middleware('admin')->group(function(){
            Route::prefix('admin')->group(function(){
                Route::get('/customers','AdminController@customers');
                Route::get('/admins','AdminController@index');
                Route::get('/show/{admin}','AdminController@show');
                Route::post('/register','AdminController@store');
                Route::put('/update/{user}','AdminController@update');
                Route::delete('/delete/{user}','AdminController@destroy');
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
            Route::prefix('comment')->group(function(){
                Route::post('/create/{customer}/{product}','CommentController@store');
                Route::post('/delete/{customer}/{product}','CommentController@destroy');
            });
        });
    });
});


