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
        return User::orderBy('updated_at', 'desc')->where('is_admin',config('const.role.customer'))->with('addresses')->get();
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
        return Category::orderBy('updated_at', 'desc')->with('products','products.sizes')->get();
    }

    public function Orders()
    {
        return Order::orderBy('updated_at', 'desc')->with(['customer','products'])->get();
    }

    public function pendingOrders()
    {
        return Order::orderBy('updated_at', 'desc')->where('status',config('const.order.pending'))->where('address_id','!=',null)
        ->orWhere('tracking_code',null)->where('status',config('const.order.confirmed'))->where('address_id','!=',null)
        ->with(['customer','customer','address','products','products.sizes'])->get();
    }

    public function getPendingNotification()
    {
        return Order::where('view',0)->get();
    }

    public function updateViewPending()
    {
        $update = \DB::table('orders')->update(
            ['view'=>true]
        );
        $data = [];
        if($update == 0){
            $data = Order::first();
        }
        return $data;
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
        $orders = Order::where('status',config('const.order.confirmed'))->get();
        $totalMoney = 0;
        foreach ($orders as $order) {
            $totalMoney += $order->total;
        }
        return $totalMoney;
    }

    public function products()
    {
        return Product::where('status',config('const.product.available'))->get();
    }

    public function salesItem()
    {
        // return SalesItem::orderBy('updated_at', 'desc')->where('status',config('const.sales_item.available'))->with('products')->get();
        return Product::orderBy('updated_at','desc')->where('is_sale',true)->where('promo_price','!=',null)->with('category','sizes')
        ->whereHas('sizes',function($query){
            $query->where('avail_unit_measure','>',0);
        })->get();
    }

    public function getSalesItem($data)
    {
        // return SalesItem::where('id',$data->id)->with('products')->first();
        return Product::where('id',$data->id)->with('sizes')->first();
    }

    public function getProductToSales($data)
    {
        return Product::where('id',$data)->where('status',config('const.sales_item.available'))->first();
    }

    public function getSizes()
    {
        return Sizes::all();
    }

    public function getOrdersByUser($data) {
        return Order::orderBy('updated_at', 'desc')
        ->where('user_id', $data->user_id)
        ->where('status','<',2)
        ->where('address_id','!=',null)
        ->with('products', 'delivery')
        ->get();
    }

    public function getAllOrderByUser($data)
    {
        return Order::orderBy('updated_at', 'desc')
        ->where('user_id', $data->user_id)
        ->where('address_id','!=',null)
        ->with('products', 'delivery')
        ->get();
    }

}
?>
