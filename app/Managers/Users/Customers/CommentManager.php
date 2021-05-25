<?php

namespace App\Managers\Users\Customers;

use App\Managers\Template\Template;
use App\Validations\Users\Customer\CommentValidation;
use Illuminate\Support\Facades\Auth;

class CommentManager
{
    protected $template;
    protected $check;

    public function __construct(Template $template, CommentValidation $check)
    {
        $this->template = $template;
        $this->check = $check;
    }

      /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request,$product)
    {
        $validation = $this->check->validation($request);
        $response = [];
        $customer = Auth::user();
        try {
            if($validation->fails()){
                $response["message"] = $validation->errors();
                $response["error"] = true;
            }else{
                $comment = $customer->comments()->create([
                    "product_id"=>$product->id,
                    "message"=>$request->message
                    ]);
                $response["message"] = "Successfully Added Comment";
                $response["data"] = $comment;
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
    public function destroy($product)
    {
        $response = [];
        $customer = Auth::user();
        try {
            if($product->isEmpty()){
                $response["message"] = "No Product Found!";
            }else{
                $customer->comments->where('product_id',$product->id)->first()->delete();
                $response["message"] = "Successfully Deleted Comment";
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }

}


?>
