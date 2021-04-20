<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
                    $response["message"] = "Successfully login";
                    $response["data"] = $user;
                    $response["access_token"] = $token->plainTextToken;
                    $response["error"] = false;
                }
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error;
            $response["error"] = true;
        }
        return response()->json($response);
    }

    public function logout(User $user)
    {
        $response = [];
        try {
            $user->tokens()->where('tokenable_id',$user->id)->delete();
            $response["message"] = "Logout Successfully";
            $response["error"] = false;
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error;
            $response["error"] = true;
        }

        return response()->json($response);
    }
}
