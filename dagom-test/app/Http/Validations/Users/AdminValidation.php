<?php

namespace App\Http\Validation\Users;

use Illuminate\Support\Facades\Validator;

class AdminValidation
{
    /**
     * Validate the specified access token from storage.
     *
     * @param  int  $data
     * @return \Illuminate\Http\Response
     */
    public function validation($data)
    {
       $rules = Validator::make($data,[
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
