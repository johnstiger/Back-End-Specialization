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
            $response["productSales"] = $this->services->salesItem();
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
    //    return Order::whereYear('created_at', Carbon::now()->year)
    //     ->select(DB::raw("MONTH(created_at) month"), DB::raw("count('month') as order_count"))
    //     ->groupby('month')->get();

        $months = [1,2,3,4,5,6,7,8,9,10,11,12];

          $orders = Order::whereYear('created_at',Carbon::now()->year)->where('status',config('const.order.confirmed'))
          ->get()->mapToGroups(function($items, $key){
              return [$items->created_at->format('m')=>$items];
          })->map(function($items, $key){
              return $items->count();
          });

          foreach ($months as $month) {
              $result[$month] = 0;
              foreach ($orders as $key => $order) {
                  if($month == $key ){
                      $result[$month] = $order;
                  }
              }
          }

          return $result;
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
