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
        return User::where('is_admin',config('const.role.customer'))->with('addresses')->get();
    }

    public function allAdmins()
    {
        return User::where('is_admin',config('const.role.admin'))->get();
    }

    public function getAdmin($request)
    {
        return User::where('is_admin',config('const.role.admin'))->where('email',$request->email)->first();
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
        return Order::with(['customer','products'])->get();
    }

    public function pendingOrders()
    {
        return Order::where('status',config('const.order.pending'))->orWhere('tracking_code',null)->with(['customer','products'])->get();
    }

    public function countCostumers()
    {
        return User::where('is_admin',config('const.role.customer'))->count();
    }

    public function countOrders()
    {
        return Order::where('status',config('const.order.confirmed'))->count();
    }

    public function countProducts()
    {
        return Product::where('status',config('const.product.available'))->count();
    }

    public function countSales()
    {
        return Order::where('status',config('const.order.confirmed'))->count();
    }

    public function products()
    {
        return Product::where('status',config('const.product.available'))->get();
    }

    public function salesItem()
    {
        return SalesItem::where('status',config('const.sales_item.available'))->with('products')->get();
    }

    public function getSalesItem($data)
    {
        return SalesItem::where('id',$data->id)->with('products')->first();
    }

    public function getProductToSales($data)
    {
        return Product::where('id',$data)->where('status',config('const.sales_item.available'))->first();
    }

    public function getSizes()
    {
        return Sizes::all();
    }

    public function getCategoryWithProducts($id)
    {
        return Category::where('id',$id)->with('products')->get();
    }

}



?>
