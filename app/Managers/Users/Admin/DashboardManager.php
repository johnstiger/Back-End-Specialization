<?php

namespace App\Managers\Users\Admin;

use App\Managers\Template\Template;
use App\Models\Order;
use App\Models\Product;
use App\Validations\Users\Admin\AdminValidation;
use App\Models\User;
use App\Services\Data\DataServices;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DashboardManager
{
    protected $services;
    protected $check;

    public function __construct(DataServices $services)
    {
        $this->services = $services;
    }

    public function index()
    {
        $response = [];
        try {
            $customers = $this->services->allCustomers();
            $pendingOrders = $this->services->pendingOrdes();
            $categories = $this->services->Categories();
            $response["customers"] = !$customers ? "No Customers Yet" : $customers;
            $response["pendingOrders"] = !$pendingOrders ? "No Pending Orders Yet" : $pendingOrders;
            $response["categories"] = !$categories ? "No Categories Yet" : $categories;
            $response["annuallyOrders"] = $this->ordersAnnually();
            $response["weeklyOrders"] = $this->weeklyOrders();
            $response["products"] = $this->products();
            $response["sales"] = $this->sales();
            $response["orders"] = $this->orders();
            $response["error"] = false;

        } catch (\Exception $error) {

            $response["message"] = $error->getMessage();
            $response["error"] = true;
        }

        return $response;

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ordersAnnually()
    {
       return Order::whereYear('created_at', Carbon::now()->year)
        ->select(DB::raw("MONTH(created_at) month"), DB::raw("count('month') as order_count"))
        ->groupby('month')->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function products()
    {
        return Product::where('status',1)->count();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sales()
    {
        return Order::where('status',1)->count();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function weeklyOrders()
    {
        return Order::where('status',1)
        ->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->count();
    }


    public function orders()
    {
        return Order::where('status',1)->count();
    }
}


?>
