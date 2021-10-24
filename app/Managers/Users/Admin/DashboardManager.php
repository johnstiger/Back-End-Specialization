<?php

namespace App\Managers\Users\Admin;

use App\Models\Order;
use App\Services\Data\DataServices;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardManager
{
    protected $services;

    public function __construct(DataServices $services)
    {
        $this->services = $services;
    }

    public function index()
    {
        $response = [];
        try {
            $response["customers"] = $this->services->countCostumers();
            $response["pendingOrders"] = $this->services->pendingOrders();
            $response["categories"] = $this->services->Categories();
            $response["annuallyOrders"] = $this->ordersAnnually();
            $response["weeklyOrders"] = $this->weeklyOrders();
            $response["products"] = $this->services->countProducts();
            $response["sales"] = $this->services->countSales();
            $response["orders"] = $this->services->countOrders();
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
