<?php

namespace App\Validations\Guest;

use Illuminate\Support\Facades\Validator;

class AuthValidation
{
    public function loginValidation($request)
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
            'password' => 'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/|confirmed',
            'password_confirmation' => 'required'
        ]);

        return $rules;
    }

    public function sendEmail($request)
    {
        $rules = Validator::make($request->all(),[
            'email' => 'required|email',
        ]);

        return $rules;
    }

    public function resetPassword($request)
    {
        $validation = Validator::make($request->all(),[
            'new_password' => 'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'confirm_password' => 'required'
        ]);

        return $validation;
    }

}


?>
