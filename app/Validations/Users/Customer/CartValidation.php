<?php

namespace App\Validations\Users\Customer;

use Illuminate\Support\Facades\Validator;

class CartValidation
{
    public function validation($request)
    {
        $validation = Validator::make($request->all(),[
            'unit_measure' => 'required|min:1',
            'sizeId' => 'required'
        ]);

        return $validation;
    }


}


?>
