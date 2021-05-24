<?php

namespace App\Managers\Users\Customers;

use App\Managers\Template\Template;
use App\Validations\Users\Customer\CustomerValidation;
use Illuminate\Support\Facades\Auth;

class CustomerManager
{
    protected $template;
    protected $check;

    public function __construct(Template $template, CustomerValidation $check)
    {
        $this->template = $template;
        $this->check = $check;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($customer)
    {
        return $this->template->show($customer);
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
        $customer = Auth::user();
        $response = [];
        $rules = $this->check->validation($request);

        try {
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                $data = $request->all();
                if($request->hasFile('image')){
                    $data["image"] = $this->url->uploadImage($request->file('image'));
                }
                $response["message"] = "Successfully Updated Information";
                $response["data"] = $customer->update($data);
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }

    /**
     * Add Address the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function address($request)
    {
        $customer = Auth::user();
        $response = [];
        $rules = $this->check->addressValidation($request);
        try {
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                $response["message"] = "Successfully Added Address";
                $response["data"] = $customer->addresses()->create($request->all());
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error;
            $response["error"] = true;
        }

        return $response;
    }

}


?>
