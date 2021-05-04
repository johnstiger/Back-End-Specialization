<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\EmailVerfication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $response = [];
        $rules = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required'
        ]);
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
        return response()->json($response);
    }

    public function register(Request $request)
    {
        $response = [];
        $rules = Validator::make($request->all(),[
            'firstname' => 'required|regex:/^[\pL\s\-]+$/u',
            'lastname' => 'required|regex:/^[\pL\s\-]+$/u',
            'contact_number' => 'required|regex:/(09)[0-9]{9}/|max:11',
            'email' => 'required|unique:users|email',
            // 'password' => 'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            // 'confirm_password' => 'confirm_password|required'
        ]);

        try {
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                $customer = $request->all();
                $customer["password"] = Hash::make($request->password);
                $data = User::create($customer);
                $token = $data->createToken('token');
                // $data->sendEmailVerificationNotification();
                $data->notify(new EmailVerfication($data));
                $data->cart()->create();
                $response["message"] = "Please Verify Your Email Account";
                $response["data"] = $data;
                $response["access_token"] = $token->plainTextToken;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return response()->json($response);
    }


    public function Unauthorized()
    {
        return response()->json('Unauthorized',401);
    }

    /**
     * Logout the specified access token from storage.
     *
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        $response = [];
        try {
            Auth::user()->currentAccessToken()->delete();
            $response["message"] = "Logout Successfully";
            $response["error"] = false;
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return response()->json($response);
    }

}
