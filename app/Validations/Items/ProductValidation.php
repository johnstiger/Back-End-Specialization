<?php

namespace App\Validations\Items;

use Illuminate\Support\Facades\Validator;

class ProductValidation
{
    /**
     * Validate the request provided.
     *
     * @param  int  $data
     * @return \Illuminate\Http\Response
     */
    public function validation($request)
    {
        $rules = Validator::make($request->all(),[
            'name' => 'required|regex:/^[\pL\s\-]+$/u',
            'unit_measure' => 'required|numeric',
            'price' => 'required|numeric',
            'category_id' => 'required',
            // 'part' => 'required',
            'sizes' => 'required',
            // 'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ],[
            'category_id.required' => 'Category name field is required',
            // 'part.required' => 'This field is required, please enter valid value'
        ]);

        return $rules;
    }
}



?>
