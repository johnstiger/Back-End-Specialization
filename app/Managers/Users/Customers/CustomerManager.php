<?php

namespace App\Managers\Users\Customers;

use App\Managers\Template\Template;
use App\Validations\Users\Customer\CustomerValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\Data\DataServices;

class CustomerManager
{
    protected $template;
    protected $check;
    protected $dataServices;

    public function __construct(Template $template, CustomerValidation $check, DataServices $dataServices)
    {
        $this->template = $template;
        $this->check = $check;
        $this->dataServices = $dataServices;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($customer)
    {
        $response = [];
        try {
            if(!$customer){
                $response["message"] = "No data found!";
            }else{
                $response["message"] = "Success";
                $response["data"] = $customer;
                $response["address"] = $customer->addresses->where('active',1)->first();
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
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
        $rules = $this->check->validation($request);
        $customer = Auth::user();
        try {
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                $data = $request->all();
                // if($request->hasFile('image')){
                //     $data["image"] = $this->url->uploadImage($request->file('image'));
                // }
                $response["data"] = $customer->update($data);
                $response["message"] = "Successfully Updated Information";
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }

    public function resetPassword($request)
    {
       $response = [];
       $customer = Auth::user();
       try {
            $rules = $this->check->checkCurrentPasswordField($request);
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                if(Hash::check($request->current_password, $customer->password)){
                    $validation = $this->check->resetPassword($request);
                    if($validation->fails()){
                        $response["message"] = $validation->errors();
                        $response["error"] = true;
                    }else{
                        $newPassword = Hash::make($request->password);
                        $customer->update(['password'=>$newPassword]);
                        $response["message"] = "Successfully changed password";
                        $response["error"] = false;
                    }
                }else{
                    $response["message"] = "Current password doesn't match";
                    $response["error"] = true;
                }
            }
       } catch (\Exception $err) {
            $response["message"] = "Error ".$err->getMessage();
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
        $response = [];
        $customer = Auth::user();
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

    public function orders($request)
    {
        $orders  = $this->dataServices->getOrdersByUser($request);
        return $this->template->index($orders);
    }
}
?>
