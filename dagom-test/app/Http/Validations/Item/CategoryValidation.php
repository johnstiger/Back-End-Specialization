<?php

namespace App\Http\Validation\Item;

use Illuminate\Support\Facades\Validator;

class CategoryValidation
{
    /**
     * Validate the request provided.
     *
     * @param  int  $data
     * @return \Illuminate\Http\Response
     */
    public function validation($data)
    {
        $rules = Validator::make($data,[
            'name' => 'required|regex:/^[\pL\s\-]+$/u',
        ]);
        return $rules;
    }


    public function productValidation($data)
    {
        $rules = Validator::make($data,[
            'name' => 'required|regex:/^[\pL\s\-]+$/u',
            'unit_measure' => 'required|numeric',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);
        return $rules;
    }
}






?>
