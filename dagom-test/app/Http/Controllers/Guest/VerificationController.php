<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return response()->json("Verified");
    }
}
