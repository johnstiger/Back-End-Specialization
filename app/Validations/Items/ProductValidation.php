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
            'name' => 'required',
            // 'unit_measure' => 'required|numeric',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'image' => 'required',
            // 'part' => 'required',
            'sizes' => 'required',
            // 'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ],[
            'category_id.required' => 'Category name field is required',
            // 'part.required' => 'This field is required, please enter valid value'
        ]);

        return $rules;
    }

    public function imageValidation($request)
    {
        $rules = Validator::make($request->all(),[
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ],[
            'image.image' => 'The file must be an image'
        ]
    );

        return $rules;
    }

    public function salesItemValidation($request)
    {
        $rules = Validator::make($request,[
            'percent_off' => 'required',
            'promo_type' => 'required',
            'unit_measure' => 'required',
            'description'  => 'required',
            'size'         => 'required',
        ]);

        return $rules;
    }

    public function specificValidation($request)
    {
        $rules = Validator::make($request,[
            'percent_off' => 'required',
            'promo_type' => 'required',
            'description'  => 'required',
            'size'         => 'required',
        ]);

        return $rules;
    }

    public function updateSaleValidation($request)
    {
        $rules = Validator::make($request,[
            'percent_off' => 'required',
            'promo_type' => 'required',
            'description' => 'required'
        ]);

        return $rules;
    }

}



?>
