<?php

namespace App\Validations\Users\Customer;

use Illuminate\Support\Facades\Validator;

class CustomerValidation
{
    public function validation($request)
    {
        $rules = Validator::make($request->all(),[
            'firstname' => 'required|regex:/^[\pL\s\-]+$/u',
            'lastname' => 'required|regex:/^[\pL\s\-]+$/u',
            'contact_number' => 'required|regex:/(09)[0-9]{9}/|max:11',
        ]);

        return $rules;
    }

    public function resetPassword($request)
    {
        $rules = Validator::make($request->all(),[
            'password' => 'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/|confirmed',
            'password_confirmation' => 'required'
        ]);

        return $rules;
    }

    public function checkCurrentPasswordField($request)
    {
        $rules = Validator::make($request->all(),[
            'current_password' => 'required',
        ]);

        return $rules;
    }


    public function addressValidation($request)
    {
        $rules = Validator::make($request->all(),[
            'contact_number' => 'required|regex:/(09)[0-9]{9}/|max:11',
            'postal_code' => 'required',
            'region' => 'required',
            'province' => 'required',
            'city' => 'required',
            'municipality' => 'required',
        ]);

        return $rules;
    }
}


?>
