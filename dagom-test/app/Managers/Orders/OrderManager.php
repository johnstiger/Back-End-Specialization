<?php

namespace App\Managers\Orders;

use App\Validations\Orders\OrderValidation;

class OrderManager
{

    protected $check;
    public function __construct(OrderValidation $check)
    {
        $this->check = $check;
    }

    public function create($user)
    {
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

    public function store($request, $user)
    {
        $response = [];
        $total = 0;
        $rules = $this->check->validation($request);

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
}


?>
