<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Users\User;

class VerificationController extends Controller
{
    /*
    * Check if email is verified
    * return json type result
    */
    public function verifyEmail(User $id, $token)
    {
        $id->email_verified_at = now();
        $id->save();
        return response()->json("Verified");
    }

    /*
    * If email is not verified
    * return json type result
    */
    public function notVerifyEmail()
    {
        return response()->json('Please Verify Your Email');
    }

}
