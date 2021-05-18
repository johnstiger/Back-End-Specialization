<?php

namespace App\Validations\Users\Customer;

use Illuminate\Support\Facades\Validator;

class CommentValidation
{
    public function validation($request)
    {
        $validation = Validator::make($request->all(),[
            'message' => 'required'
        ]);

        return $validation;
    }


}


?>
