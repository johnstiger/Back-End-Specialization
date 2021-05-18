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

    /**
     * Login the registered customer
     *
     * @param  int  $user
     * return abort to not authorized
     */
    public function login(Request $request)
    {
        $response = $this->manager->Attempt($request);
        return response()->json($response);
    }

    /**
     * Register new Customer
     *
     * @param  int  $user
     * return abort to not authorized
     */
    public function register(Request $request)
    {
        $response = $this->manager->newCustomer($request);
        return response()->json($response);
    }

    /**
     * If no access token
     *
     * @param  int  $user
     * return abort to not authorized
     */
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
        $response = $this->manager->goOut();
        return response()->json($response);
    }

    /**
     * Send Email Password Verification to
     * the specified access token from storage.
     *
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(Request $request)
    {
        $response = $this->manager->SendCode($request);
        return response()->json($response);
    }

    /**
     * Verify the code
     * the specified access token from storage.
     *
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function verificationCodeCheck(Request $request, User $user)
    {
        $response = $this->manager->VerifyCode($request, $user);
        return response()->json($response);
    }


    /**
     * Change new Password
     * the specified access token from storage.
     *
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request, User $user)
    {
        $response = $this->manager->newPassword($request, $user);
        return response()->json($response);
    }

}
