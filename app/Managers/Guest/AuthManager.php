<?php

namespace App\Managers\Guest;

use App\Validations\Guest\AuthValidation as GuestAuthValidation;
use App\Models\User;
use App\Services\Data\DataServices;
use App\Services\Mail\SendEmailServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthManager
{

    protected $check;
    protected $send;
    protected $data;

    public function __construct(GuestAuthValidation $check, SendEmailServices $send, DataServices $data)
    {
        $this->check = $check;
        $this->send = $send;
        $this->data = $data;
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
                $user = $this->data->getUser($request);
                if(!$user || !Hash::check($request->password, $user->password)){
                    $response["message"] = "Email or Password is incorrect!";
                    $response["error"] = true;
                }else{
                    if(!$user->is_admin && $user->email_verified_at == null){
                        $response["message"] = "Please Verify Your Email First";
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
        \DB::beginTransaction();
        try {
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                $customer = $request->all();
                $customer["password"] = Hash::make($request->password);
                $customer["image"] = "data:image/png;base64,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";
                $data = User::create($customer);
                $token = $data->createToken('token');
                $this->send->sendEmailVerification($data, $token->plainTextToken);
                $data->cart()->create();
                \DB::commit();
                $response["message"] = "We sent an Email Verification to your email, please verify your email";
                $response["data"] = $data;
                $response["access_token"] = $token->plainTextToken;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            \DB::rollBack();
            $response["message"] = "Error ".$error;
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
            $user = Auth::user();
            if($user){
                Auth::user()->currentAccessToken()->delete();
                $response["message"] = "Logout Successfully";
                $response["error"] = false;
            }else{
                $response["error"] = true;
            }
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

        if($rules->fails()){
            $response["message"] = $rules->errors();
            $response["error"] = true;
        }else{
            try {
                $user = $this->data->getUser($request);
                if(!$user){
                    $response["message"] = "Email is not recognize!";
                    $response["error"] = true;
                }elseif($user->email_verified_at == null){
                    $response["message"] = "Your Email is not verified yet";
                    $response["error"] = true;
                    $response["need"] = "Need Verification";
                }else{
                    $code =(string) random_int(1000,90000);
                    $this->send->sendCode($user, $code);
                    if($user->verificationCode == null){
                        $user->verificationCode()->create([
                            'code' => $code
                        ]);
                    }else{
                        $user->verificationCode()->update([
                            'code' => $code
                        ]);
                    }
                    $response["message"] = "We sent code in your Email";
                    $response["error"] = false;
                    $response["data"] = $user;
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
        $time = $user->verificationCode->updated_at->diff(now())->format('%i');
        $code = $user->verificationCode->code;

        if($time > 10){
            $response["message"] = "This code is already expired!";
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
            $newPassword = Hash::make($request->password);
            $user->update(['password'=>$newPassword]);
            $response["message"] = "Successfully Reseting Your Password";
            $response["error"] = false;
        }

        return $response;
    }
}


?>
