<?php

namespace App\Managers\Users\Customers;

use App\Validations\Users\Customer\CartValidation;
use Illuminate\Support\Facades\Auth;

class CartManager
{
    protected $check;

    public function __construct(CartValidation $check)
    {
        $this->check = $check;
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request, $product)
    {
        $validation = $this->check->validation($request);
        $response = [];
        $customer = Auth::user();

        try {
            if($validation->fails()){
                $response["message"] = $validation->errors();
                $response["error"] = true;
            }else{
                $item = $request->all();
                $price = $product->is_sale ? $product->sale_price : $product->price;
                // $item['quantity']= $item['quantity']['unit_measure'];
                $customer->cart->products()->syncWithoutDetaching([
                    $product->id=>[
                        'quantity'=>$item["unit_measure"],
                        'sizeId'=>$item["sizeId"],
                        'total'=>$price * $item["unit_measure"],
                        'status'=>1
                        ]
                    ]);
                $size = $product->sizes->where('id',$item["sizeId"])->first();
                $product->sizes()->syncWithoutDetaching([
                    $item["sizeId"] => [
                        'avail_unit_measure' => $size->pivot->avail_unit_measure - $item["unit_measure"],
                    ]
                ]);
                $response["message"] = "Successfully Added New Product in Cart";
                $response["data"] = $customer->cart->products;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["error"] = true;
            $response["message"] = "Error ".$error->getMessage();
        }
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $customer = Auth::user();
        $response = [];
        try {
            if(!$customer->cart){
                $customer->cart()->create();
                $response["message"] = "There is no product yet!";
            }elseif ($customer->cart->products->isEmpty()) {
                $response["message"] = "There is no product yet!";
            }
            else{
                $response["message"] = "Success";
                $response["data"] = $customer->cart->products()->with('sizes')->get();
                $response["error"] = false;
            }
        } catch (\Exception $e) {
            $response["message"] = "Error ".$e->getMessage();
            $response["error"] = true;
        }

        return $response;
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
        $customer = Auth::user();
        $response = [];
        try {
            $item = $request->all();
            $price = $product->is_sale ? $product->sale_price : $product->price;

            $size = $product->sizes->where('id',$item["pivot"]["sizeId"])->first();
            $avail = 0;
            if($item["pivot"]["newQuantity"] >= $item["pivot"]["quantity"]){
                // Minus in product display
                $avail = $size->pivot->avail_unit_measure - ($item["pivot"]["newQuantity"] - $item["pivot"]["quantity"]);

            }else{
                // Add in product display
                $test =  $item["pivot"]["quantity"] - $item["pivot"]["newQuantity"] ;
                $avail = $size->pivot->avail_unit_measure + $test;
            }

            $product->sizes()->syncWithoutDetaching([
                $size->id => [
                    'avail_unit_measure' =>$avail,
                ]
            ]);
            $customer->cart->products()->syncWithoutDetaching([
                $product->id=>[
                    'quantity'=>$item["pivot"]["newQuantity"],
                    'total'=>$price * $item["pivot"]["newQuantity"],
                    'status'=>1
                    ]
                ]);
            $response["message"] = "Successfully Updated the Product";
            $response["data"] = $customer->cart->products;
            $response["error"] = false;
        } catch (\Exception $e) {
            $response["message"] = "Error ".$e->getMessage();
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
    public function destroy( $request, $product)
    {
        $customer = Auth::user();
        $response = [];
        try {
            $size = $product->sizes->where('id',$request["pivot"]["sizeId"])->first();
            $product->sizes()->syncWithoutDetaching([
                $size->id => [
                    'avail_unit_measure' => $request["pivot"]["quantity"] + $size->pivot->avail_unit_measure
                ]
            ]);

            $customer->cart->products->where('id',$product->id)
            ->first()->pivot->delete();

            $response["message"] = "Successfully Remove Product";
            $response["error"] = false;
        } catch (\Exception $e) {
            $response["message"] = "Error ".$e->getMessage();
            $response["error"] = true;
        }
        return $response;
    }


    public function countProducts()
    {
        $user = Auth::user();
        return $user->cart->products()->count();
    }


}


?>
