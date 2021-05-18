<?php

namespace App\Http\Managers\Guest;

use App\Http\Validation\Guest\AuthValidation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthManager
{
    protected $check;

    public function __construct(AuthValidation $check)
    {
        $this->check = $check;
    }

    public function login($request)
    {
        $rules = $this->check->validation($request->all());
        try {
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                $user = User::where('email',$request->email)->first();
                if(!$user || !Hash::check($request->password, $user->password)){
                    $response["message"] = "Email or Password is incorrect!";
                    $response["error"] = true;
                }else{
                    $token = $user->createToken('token');
                    if(!$user->cart){
                        $user->cart()->create();
                    }
                    $response["message"] = "Successfully login";
                    $response["data"] = $user;
                    $response["access_token"] = $token->plainTextToken;
                    $response["error"] = false;
                }
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }


    public function register($request)
    {
        $rules = $this->check->registerValidation($request);
        try {
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                $customer = $request->all();
                $customer["password"] = Hash::make($request->password);
                $data = User::create($customer);
                $token = $data->createToken('token');
                $data->sendEmailVerificationNotification();
                $data->cart()->create();
                $response["message"] = "Successfully Send Link For To Your Email";
                $response["data"] = $data;
                $response["access_token"] = $token->plainTextToken;
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
