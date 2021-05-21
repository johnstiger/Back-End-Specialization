<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Managers\Guest\AuthManager;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $manager;

    public function __construct(AuthManager $manager)
    {
        $this->manager = $manager;
    }

    /*
    * Login Users
    * return json type result
    */
    public function login(Request $request)
    {
        $response = $this->manager->Attempt($request);
        return response()->json($response);
    }

    /*
    * Registering Guest to become Customer
    * return json type result
    */
    public function register(Request $request)
    {
        $response = $this->manager->newCustomer($request);
        return response()->json($response);
    }

    /*
    * If there is no access token
    * return abort to 401
    */
    public function Unauthorized()
    {
        return view('NoAccess.Unauthorized');
    }

    /*
    * Logout the Authired user
    * return json type result
    */
    public function logout()
    {
        $response = $this->manager->goOut();
        return response()->json($response);
    }

    /*
    * Sending Code in Email
    * return json type result
    */
    public function forgotPassword(Request $request)
    {
        $response = $this->manager->SendCode($request);
        return response()->json($response);
    }

    /*
    * Checking if the code is match
    * return json type result
    */
    public function verificationCodeCheck(Request $request, User $user)
    {
        $response = $this->manager->VerifyCode($request, $user);
        return response()->json($response);
    }


    /*
    * Changing new Password
    * return json type result
    */
    public function resetPassword(Request $request, User $user)
    {
        $response = $this->manager->newPassword($request, $user);
        return response()->json($response);
    }

}
