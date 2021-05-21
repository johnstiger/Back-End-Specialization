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
}


?>
