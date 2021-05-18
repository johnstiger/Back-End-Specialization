<?php

namespace App\Http\Validation\Guest;

use Illuminate\Support\Facades\Validator;

class AuthValidation
{
    public function validation($request)
    {
        $rules = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required'
        ]);
        return $rules;
    }

    public function registerValidation($request)
    {
        $rules = Validator::make($request->all(),[
            'firstname' => 'required|regex:/^[\pL\s\-]+$/u',
            'lastname' => 'required|regex:/^[\pL\s\-]+$/u',
            'contact_number' => 'required|regex:/(09)[0-9]{9}/|max:11',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8',
        ]);

        return $rules;
    }
}





?>
