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


// Showing Products
Route::get('/home', 'Items\ProductController@index');
Route::get('/sales', 'Items\SalesItemController@index');
Route::get('/dagom/{product}', 'Items\ProductController@show');
Route::get('/allCategory', 'Items\CategoryController@index');
Route::get('/getCategory/{category}', 'Items\CategoryController@getCategory');

Route::namespace('Guest')->group(function () {
    // Unauthorized
    Route::get('/UnAuthorized', 'AuthController@Unauthorized')->name('unauthorized');
    Route::get('/NotVerified', 'VerificationController@notVerifyEmail')->name('verification.notice');

    //LogIn and Register Verification
    Route::post('/login', 'AuthController@login')->middleware('cors');
    Route::post('/register', 'AuthController@register')->middleware('cors')->name('verification.send');
    Route::get('/email/verification/{id}/{token}', 'VerificationController@verifyEmail')->name('verified');

    //Forgot Password using Verification Code
    Route::post('/forgot-password', 'AuthController@forgotPassword');
    Route::post('/reset-password/{user}', 'AuthController@verificationCodeCheck');
    Route::post('/new-password/{user}', 'AuthController@resetPassword');

    // Search Products or Category
    Route::prefix('search')->group(function () {
        Route::post('/products', 'SearchEngineController@Products');
        Route::post('/products/{category}', 'SearchEngineController@productByCategory');
    });
});

// User
Route::middleware('auth:sanctum')->group(function () {
    Route::namespace('Guest')->middleware('admin')->group(function () {
        Route::prefix('search')->group(function () {
            Route::post('/admin/products', 'SearchEngineController@Products');
            Route::post('/admin/products/{category}', 'SearchEngineController@productByCategory');
            Route::post('/customers', 'SearchEngineController@Customers');
            Route::post('/admin', 'SearchEngineController@Admins');
            Route::post('/items', 'SearchEngineController@Products');
            Route::post('/category', 'SearchEngineController@Category');
        });
        Route::get('/user', 'AuthController@getUser');
    });
    Route::get('/logout', 'Guest\AuthController@logout');
    Route::namespace('User')->group(function () {
        Route::namespace('Admin')->middleware('admin')->group(function () {
            Route::prefix('admin')->group(function () {
                Route::get('/dashboard', 'DashboardController@index');
                Route::get('/customers', 'AdminController@customers');
                Route::get('/admins', 'AdminController@index');
                Route::get('/show/{admin}', 'AdminController@show');
                Route::post('/register', 'AdminController@store');
                Route::post('/update/{user}', 'AdminController@update');
                Route::post('/saveImage/{user}', 'AdminController@savingImage');
                Route::put('/resetPassword/{user}', 'AdminController@updatePassword');
                Route::delete('/delete/{user}', 'AdminController@destroy');
            });
        });
        Route::namespace('Customer')->middleware('verified')->group(function(){
            Route::prefix('cart')->group(function(){
                Route::get('/count','CartController@countProductsInCart');
                Route::get('/show','CartController@show');
                Route::post('/add/{product}','CartController@store');
                Route::put('/update/{product}','CartController@update');
                Route::delete('/delete/{product}','CartController@destroy');
            });
            Route::prefix('customer')->group(function () {
                Route::get('/myProfile/{customer}', 'CustomerController@show');
                Route::get('/myProfile', 'CustomerController@showAll');
                Route::put('/information', 'CustomerController@update');
                Route::get('/address/{customerId}/all', 'CustomerController@findAllCustomerAddress');
                Route::get('/address/{id}', 'CustomerController@findById');
                Route::post('/address/{customer}', 'CustomerController@address');
                Route::put('/address/{id}', 'CustomerController@updateAddress');
                Route::post('/reset-password', 'CustomerController@resetPassword');
            });
            Route::prefix('comment')->group(function () {
                Route::post('/create/{product}', 'CommentController@store');
                Route::post('/delete/{product}', 'CommentController@destroy');
            });
            Route::prefix('orders')->group(function () {
                Route::get('/all/{user_id}', 'CustomerController@orders');
                Route::get('/receivedOrders', 'CustomerController@showReceivedOrders');
                Route::post('/removeOrder/{order}', 'CustomerController@removeItem');
            });
        });
    });
    Route::namespace('Orders')->group(function () {
        Route::prefix('order')->group(function () {
            Route::get('/checkout', 'OrderController@create');
            Route::post('/placed', 'OrderController@store');
            Route::get('/show', 'OrderController@show');
            Route::get('/cancel', 'OrderController@cancel');
        });
    });
    Route::namespace('Items')->middleware('admin')->group(function () {
        Route::prefix('category')->group(function () {
            Route::get('/getProducts', 'CategoryController@getCategories');
            Route::get('/show/{category}', 'CategoryController@show');
            Route::post('/newCategory', 'CategoryController@store');
            Route::post('/newCategoryProduct/{category}', 'CategoryController@storeProduct');
            Route::put('/update/{category}', 'CategoryController@update');
            Route::delete('/delete/{category}', 'CategoryController@destroy');
        });
        Route::prefix('product')->group(function () {
            Route::get('/all', 'ProductController@index');
            Route::get('/show/{product}', 'ProductController@show');
            Route::post('/saveImage/{product}', 'ProductController@savingImage');
            Route::post('/newProduct', 'ProductController@store');
            Route::put('/update/{product}', 'ProductController@update');
            Route::delete('/delete/{product}', 'ProductController@destroy');
            Route::get('/sizes', 'ProductController@sizes');
            Route::put('/notAvailable/{product}', 'ProductController@notAvailable');
        });
        Route::prefix('salesItem')->group(function () {
            Route::get('/', 'SalesItemController@index');
            Route::get('/{salesItem}', 'SalesItemController@show');
            Route::post('/{product}', 'SalesItemController@store');
            Route::delete('/remove/{salesItem}', 'SalesItemController@destroy');
            Route::put('/update/{salesItem}', 'SalesItemController@update');
        });
    });
    Route::namespace('Orders')->middleware('admin')->group(function () {
        Route::prefix('order')->group(function () {
            Route::get('/', 'OrderController@index');
            Route::get('/received/{order}', 'OrderController@receivedOrder');
            Route::get('/pending', 'OrderController@pendingOrders');
            Route::get('/notification', 'OrderController@getNotification');
            Route::get('/updateView', 'OrderController@viewPendingOrders');
            Route::put('/confirmed/{user}', 'OrderController@confirmOrder');
            Route::put('/declined/{user}', 'OrderController@declinedOrder');
            Route::post('/tracking/{user}', 'OrderController@addingTrackingCode');
        });
    });
});


Route::get('/dashboard', 'User\Admin\DashboardController@ordersAnnually');
