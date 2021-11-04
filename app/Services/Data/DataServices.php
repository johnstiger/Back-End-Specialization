<?php

namespace App\Services\Data;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\SalesItem;
use App\Models\Sizes;
use App\Models\User;

class DataServices
{
    public function getUser($request)
    {
        return User::where('email',$request->email)->first();
    }

    public function allCustomers()
    {
        return User::where('is_admin',0)->with('addresses')->get();
    }

    public function allAdmins()
    {
        return User::where('is_admin',1)->get();
    }

    public function getAdmin($request)
    {
        return User::where('is_admin',1)->where('email',$request->email)->first();
    }

    public function Categories()
    {
       return Category::all();
    }

    public function categoriesProducts()
    {
        return Category::with('products')->get();
    }

    public function Orders()
    {
        return Order::where('status',)->with('products')->get();
    }

    public function pendingOrders()
    {
        return Order::where('status',0)->get();
    }

    public function countCostumers()
    {
        return User::where('is_admin',0)->count();
    }

    public function countOrders()
    {
        return Order::where('status',1)->count();
    }

    public function countProducts()
    {
        return Product::where('status',1)->count();
    }

    public function countSales()
    {
        return Order::where('status',1)->count();
    }

    public function products()
    {
        return Product::where('status',1)->get();
    }

    public function salesItem()
    {
        return SalesItem::where('status',1)->with('products')->get();
    }

    public function getProductToSales($data)
    {
        return Product::where('id',$data)->where('status',1)->first();
    }

    public function getSizes()
    {
        return Sizes::all();
    }

}



?>
