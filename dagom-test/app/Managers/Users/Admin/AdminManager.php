<?php

namespace App\Managers\Users\Admin;

use App\Managers\Template\Template;
use App\Validations\Users\Admin\AdminValidation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminManager
{
    protected $template;
    protected $check;

    public function __construct(Template $template, AdminValidation $check)
    {
        $this->template = $template;
        $this->check = $check;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customers()
    {
        $customer = User::where('is_admin',0)->get();
        return $this->template->index($customer);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admins()
    {
        $admins = User::where('is_admin',1)->get();
        return $this->template->index($admins);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {
        $response = [];
        $rules = $this->check->validation($request->all());

        try {
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                $customer = $request->all();
                $customer["password"] = Hash::make($request->password);
                $customer["is_admin"] = true;
                $data = User::create($customer);
                $response["message"] = "Successfully Added ".$data->firstname." ".$data->lastname."in Admin.";
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
    public function show($admin)
    {
        return $this->template->show($admin);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function update($request, $admin)
    {
        $response = [];

        $rules = [
            'firstname' => 'required|regex:/^[\pL\s\-]+$/u',
            'lastname' => 'required|regex:/^[\pL\s\-]+$/u',
            'contact_number' => 'required|regex:/(09)[0-9]{9}/|max:11',
            'password' => 'required|min:8',
        ];

        if($admin->email != $request->email){
            $rules["email"] = 'required|email|unique:users';
        }

        $validation = Validator::make($request->all(),$rules);

        try {
            if($validation->fails()){
                $response["message"] = $validation->errors();
                $response["error"] = true;
            }else{
                $admin->update($request->all());
                $response["message"] = "Successfully Updated ".$admin->firstname." ".$admin->lastname;
                $response["data"] = $admin;
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
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($admin)
    {
        return $this->template->destroy($admin);
    }

}


?>