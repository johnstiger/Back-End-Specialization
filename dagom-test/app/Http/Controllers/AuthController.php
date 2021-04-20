<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $credentials = request(['email', 'password']);
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                if(Auth::attempt($credentials)){
                    $user = Auth::user();
                    $token = $user->createToken("token");
                    $response["message"] = "Successfully login";
                    $response["data"] = Auth::user();
                    $response["access_token"] = $token->plainTextToken;
                    $response["error"] = false;
                }else{
                    $response["message"] = "Email or Password is incorrect!";
                    $response["error"] = true;
                }
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error;
            $response["error"] = true;
        }
        return response()->json($response);
    }


    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();
    }
}
