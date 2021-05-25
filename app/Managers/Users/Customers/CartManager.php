<?php

namespace App\Managers\Users\Customers;

use App\Services\Status\CartStatus;
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
        $customer = Auth::user();
        $validation = $this->check->validation($request);
        $response = [];
        try {
            if($validation->fails()){
                $response["message"] = $validation->errors();
                $response["error"] = true;
            }else{
                $item = $request->all();
                $customer->cart->products()->syncWithoutDetaching([
                    $product->id=>[
                        'quantity'=>$item["quantity"],
                        'total'=>$product->price * $item["quantity"],
                        'status'=> CartStatus::ACTIVE,
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
        $response = [];
        $customer = Auth::user();
        try {
            if(!$customer->cart){
                $customer->cart()->create();
                $response["message"] = "There is no product yet!";
            }elseif ($customer->cart->products->isEmpty()) {
                $response["message"] = "There is no product yet!";
            }
            else{
                $response["message"] = "Success";
                $response["data"] = $customer->cart->products;
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
    public function update($request)
    {
        $response = [];
        $customer = Auth::user();
        try {
            foreach ($request->products as $product) {
                $customer->cart->products()->syncWithoutDetaching([
                    $product->id=>[
                        'quantity'=>$product["quantity"],
                        'total'=>$product->price * $product["quantity"],
                        'status'=> CartStatus::ACTIVE
                        ]
                    ]);
            }
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
    public function destroy($product)
    {
        $customer = Auth::user();
        $response = [];
        try {
            $customer->cart->products->where('product_code',$product->product_code)
            ->first()->pivot->delete();
            $response["message"] = "Successfully Remove Product";
            $response["error"] = false;
        } catch (\Exception $e) {
            $response["message"] = "Error ".$e->getMessage();
            $response["error"] = true;
        }
        return $response;
    }

}


?>
