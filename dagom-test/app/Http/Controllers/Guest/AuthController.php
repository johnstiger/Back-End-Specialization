<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Managers\Guest\AuthManager;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $manager;

    public function __construct(AuthManager $manager)
    {
        $this->manager = $manager;
    }

    public function login(Request $request)
    {
        $response = $this->manager->login($request);
        return response()->json($response);
    }

    public function register(Request $request)
    {
        $response = $this->manager->register($request);
        return response()->json($response);
    }

    public function Unauthorized()
    {
        return response()->json('Unauthorized',401);
    }
}
