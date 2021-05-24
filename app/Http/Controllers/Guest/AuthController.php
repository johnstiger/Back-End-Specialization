<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Managers\Guest\AuthManager;
use App\Models\Users\User;
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
        return response()->json($this->manager->Attempt($request));
    }

    /*
    * Registering Guest to become Customer
    * return json type result
    */
    public function register(Request $request)
    {
        return response()->json($this->manager->newCustomer($request));
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
        return response()->json($this->manager->goOut());
    }

    /*
    * Sending Code in Email
    * return json type result
    */
    public function forgotPassword(Request $request)
    {
        return response()->json($this->manager->SendCode($request));
    }

    /*
    * Checking if the code is match
    * return json type result
    */
    public function verificationCodeCheck(Request $request, User $user)
    {
        return response()->json($this->manager->VerifyCode($request, $user));
    }


    /*
    * Changing new Password
    * return json type result
    */
    public function resetPassword(Request $request, User $user)
    {
        return response()->json($this->manager->newPassword($request, $user));
    }

}
