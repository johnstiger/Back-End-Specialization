<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verifyEmail(User $id)
    {
        $id->email_verified_at = now();
        $id->save();
        return response()->json("Verified");
    }

    public function notVerifyEmail()
    {
        return response()->json('Please Verify Your Email');
    }
}
