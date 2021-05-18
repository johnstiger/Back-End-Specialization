<?php

namespace App\Managers\Guest;

use App\Validations\Guest\AuthValidation as GuestAuthValidation;
use App\Models\User;
use App\Notifications\EmailVerfication;
use App\Notifications\ResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthManager
{

    protected $check;

    public function __construct(GuestAuthValidation $check)
    {
        $this->check = $check;
    }

    /**
     * Login
     *
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function Attempt($request)
    {
        $response = [];

        $rules = $this->check->loginValidation($request);

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

    /**
     * Register
     *
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function newCustomer($request)
    {
        $response = [];

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
                $data->notify(new EmailVerfication($data, $token->plainTextToken));
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

        return $response;
    }

    /**
     * Logout the specified access token from storage.
     *
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function goOut()
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

        return $response;
    }

    /**
     * Send Email Password Verification to
     * the specified access token from storage.
     *
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function SendCode($request)
    {
        $response = [];

        $rules = $this->check->sendEmail($request);

        if($rules->fails){
            $response["message"] = $rules->errors();
            $response["error"] = true;
        }else{
            try {
                $user = User::where('email',$request->email)->first();
                if(!$user){
                    $response["message"] = "Email is not recognize!";
                    $response["error"] = true;
                }elseif($user->email_verified_at == null){
                    $response["message"] = "Your Email is not verified yet";
                    $response["error"] = true;
                    $response["need"] = "Need Verification";
                }else{
                    $this->code =(string) random_int(1000,90000);
                    $user->notify(new ResetPassword($this->code, $user));
                    if($user->verificationCode == null){
                        $user->verificationCode()->create([
                            'code' => $this->code
                        ]);
                    }else{
                        $user->verificationCode()->update([
                            'code' => $this->code
                        ]);
                    }
                    $response["message"] = "We sent reset password link in your Email";
                    $response["error"] = false;
                }
            } catch (\Exception $error) {
                $response["message"] = "Error ".$error->getMessage();
                $response["error"] = true;
            }
        }

        return $response;
    }


    public function VerifyCode($request, $user)
    {
        $response = [];
        $time = $user->verificationCode->created_at->diff(now())->format('%i');
        $code = $user->verificationCode->code;
        if($time > 5){
            $response["message"] = "The code is already expired!";
            $response["error"] = true;
        }else{
            if($request->code == $code){
                $response["message"] = "Authorized!";
                $response["error"] = false;
                $user->verificationCode()->delete();
            }else{
                $response["message"] = "Code is mismatch!";
                $response["error"] = true;
            }
        }
        return $response;
    }


    public function newPassword($request, $user)
    {
        $response = [];
        $validation = $this->check->resetPassword($request);

        if($validation->fails()){
            $response["message"] = $validation->errors();
            $response["error"] = true;
        }else{
            $user->update($request->all());
            $response["message"] = "Successfully Reseting Your Password";
            $response["error"] = false;
        }

        return $response;
    }
}


?>
