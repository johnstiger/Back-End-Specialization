<?php

namespace App\Managers\Orders;

use App\Managers\Template\Template;
use App\Services\Data\DataServices;
use App\Validations\Orders\OrderValidation;
use Illuminate\Support\Facades\Auth;

class OrderManager
{

    protected $check;
    protected $template;
    protected $service;
    public function __construct(OrderValidation $check, Template $template, DataServices $service)
    {
        $this->check = $check;
        $this->template = $template;
        $this->service = $service;
    }

    public function index()
    {
        return $this->template->index($this->service->Orders());
    }

    public function pendingOrders()
    {
        return $this->template->index($this->service->pendingOrders());
    }

    public function orderConfirmed($id, $customer)
    {
        try {
            $response = [];
            $order = $customer->orders->where('id',$id)->first();
            if(!$order){
                $response["message"] = "There is no order to update";
                $response["error"] = true;
            }else{
                $order->update(['status'=>1]);
                $response["message"] = "Order Confirmed";
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }

    public function create()
    {
        $user = Auth::user();
        $response = [];
        try {
            $user->orders()->create();
            $response["message"] = "Order is now Ready";
            $response["data"] = $user->orders;
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }

    public function store($request)
    {
        $response = [];
        $total = 0;
        $rules = $this->check->validation($request);
        $user = Auth::user();
        try {
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                if($user->orders->isEmpty()){
                    $this->create($user);
                }
                foreach($request->data as $data){
                    $user->orders->last()->products()->syncWithoutDetaching([
                        $data["product_id"] => [
                            'quantity' => $data["quantity"],
                            'subtotal' => $data["subtotal"]
                        ]
                    ]);
                    $total += $data["subtotal"];
                }
                $user->orders->last()->update(['total'=>$total,'payment_method'=>$request->payment_method]);
                $response["message"] = "Success";
                $response["data"] = $user->orders->last()->products;
                $response["order"] = $user->orders->last();
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }
        return $response;
    }

    public function show()
    {
        $user = Auth::user();
        if($user->orders->isEmpty()){
            $response = $this->template->NoData();
        }else{
            $response = $this->template->show($user->orders()->first()->with('products')->get());
        }

        return $response;
    }
}


?>
