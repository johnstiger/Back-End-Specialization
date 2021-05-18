<?php

namespace App\Http\Managers\Item;

use App\Http\Validation\Item\ProductValidation;
use App\Models\Product;

class ProductManager
{

    protected $check;

    public function __construct(ProductValidation $check)
    {
        $this->check = $check;
    }
 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('status',1)->get();
        $response = [];
        try {
            if(!$products){
                $response["message"] = "No data yet!";
                $response["error"] = false;
            }else{
                $response["message"] = "Success";
                $response["data"] = $products;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
<<<<<<< HEAD:dagom-test/app/Http/Managers/Item/ProductManager.php
    public function store($request)
=======

    //Add sizes
    public function store(Request $request)
>>>>>>> 2894c596921e75bd9f64b0a1d8cbeb2c1cb35e0e:dagom-test/app/Http/Controllers/User/Admin/Item/ProductController.php
    {
        $response = [];

        $rules = $this->check->validation($request->all());

        try {
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                $product = $request->only(['name', 'price','status','description','image']);
                $size = $request->only(
                    [
                        'size',
                        'unit_measure',
                    ]
                );
                if($request->hasFile('image')){
                   $product["image"] = $this->uploadImage($request->file('image'));
                }
                $data = Product::create($product);
                $data->sizes()->create($size);
                $response["message"] = "Successfully Added ".$data->name." in Product!";
                $response["data"] = $data;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($data)
    {
        $response = [];
        try {
            if(!$data){
                $response["message"] = "No data found!";
            }else{
                $response["message"] = "Success";
                $response["data"] = $data;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($request, $product)
    {
        $response = [];

        $rules = $this->check->validation($request->all());

        try {
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                $item = $request->only(
                    [
                        'name',
                        'price',
                        'status',
                        'description',
                        'image'
                    ]
                );
                if($request->hasFile('image')){
                    $item["image"] = $this->uploadImage($request->file('image'));
                }
                $size = $request->only(
                    [
                        'size'
                        ,'unit_measure'
                    ]
                );
                $product->update($item);
                $product->sizes()->update($size);
                $response["message"] = "Successfully Updated ".$product->name;
                $response["data"] = $product;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($data)
    {
        $response = [];
        try {
            $data->delete();
            $response["message"] = "Successfully Deleted";
            $response["error"] = false;
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }
        return $response;
    }

     /**
     * Validate the request provided.
     *
     * @param  int  $data
     * @return \Illuminate\Http\Response
     */
    public function uploadImage($request)
    {
        $path = public_path().'upload/images/store';
        $request->move($path,$request->getClientOriginalName());
        return $path;
    }



}







?>
