<?php

namespace App\Validations\Orders;

use Illuminate\Support\Facades\Validator;

class OrderValidation
{
    public function validation($request)
    {
        $rules = Validator::make($request->all(),[
            'data' => 'required'
        ],[
            'data.required' => 'There is no Product Selected!'
        ]);

        return $rules;
    }

    public function trackingValidation($request)
    {
        $rules = Validator::make($request->all(),[
            'tracking_code' => 'required',
            'name_of_deliver_company' => 'required'
        ],[
            'name_of_deliver_company.required' => 'There is no Delivery Company Selected'
        ]);

        return $rules;
    }
}


?>
