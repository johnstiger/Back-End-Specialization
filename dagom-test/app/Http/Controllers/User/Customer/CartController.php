<?php

namespace App\Http\Controllers\User\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request, User $customer, Product $product)
    {
        $validation = Validator::make($request->all(),[
            'quantity' => 'required',
        ]);
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
                        'status'=>1
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
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $customer)
    {
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
                $response["data"] = $customer->cart->products;
                $response["error"] = false;
            }
        } catch (\Exception $e) {
            $response["message"] = "Error ".$e->getMessage();
            $response["error"] = true;
        }

        return response()->json($response);
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
    public function update(Request $request, User $customer, Product $product)
    {
        $response = [];
        try {
            $item = $request->all();
            $customer->cart->products()->syncWithoutDetaching([
                $product->id=>[
                    'quantity'=>$item["quantity"],
                    'total'=>$product->price * $item["quantity"],
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

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $customer, Product $product)
    {
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
        return response()->json($response);
    }
}
