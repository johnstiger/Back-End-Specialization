<?php

namespace App\Managers\Orders;

use App\Managers\Template\Template;
use App\Services\Status\OrderStatus;
use App\Validations\Orders\OrderValidation;
use Illuminate\Support\Facades\Auth;

class OrderManager
{

    protected $check;
    protected $template;
    public function __construct(OrderValidation $check, Template $template)
    {
        $this->check = $check;
        $this->template = $template;
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
                $user->orders->last()->update(
                    [
                        'total'=>$total,
                        'payment_method'=>$request->payment_method,
                        'status' => OrderStatus::PENDING
                    ]
                );
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

    public function confirmedOrder()
    {
        $response = [];
        $user = Auth::user();
        try {
            if($user){
                $user->orders->last()->update(['status'=>OrderStatus::CONFIRMED]);
                $response["message"] = "Successfully Confirmed Order";
                $response["error"] = false;
            }else{
                $response["message"] = "Sorry Confirmation has a problem";
                $response["error"] = true;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }
        return $response;
    }

    public function declinedOrder()
    {
        $user = Auth::user();
        try {
            if($user){
                $user->orders->last()->update(['status'=>OrderStatus::DECLINED]);
                $response["message"] = "Successfully Declined Order";
                $response["error"] = false;
            }else{
                $response["message"] = "Sorry Declining Order has a Problem";
                $response["error"] = true;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }

    public function cancelledOrder()
    {
        $user = Auth::user();
        $order = $user->orders->last();
        try {
            if($user){
                if($order->status != OrderStatus::CONFIRMED || $order->status != OrderStatus::DECLINED){
                    $order->update(['status'=>OrderStatus::CANCEL]);
                    $response["message"] = "Successfully Cancel Your Order";
                    $response["error"] = false;
                }else{
                    $response["message"] = "Order is already confirmed, can't Cancel!";
                    $response["error"] = true;
                }
            }else{
                $response["message"] = "No User Found!";
                $response["error"] = true;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }

    public function showPending()
    {

    }

    public function showConfirmed()
    {

    }

    public function showCancelled()
    {

    }
}


?>
