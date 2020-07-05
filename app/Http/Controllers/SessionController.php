<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Log;
use App\Http\Requests\SessionLoginRequest;

class SessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' =>
            [
                'login',
                'register'
            ]
        ]);
    }


    public function login(SessionLoginRequest $request)
    {
        $token = auth()->attempt($request->validated());
        if (!$token) {
            Log::info("Attempt to login user " . $request->email);
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        Log::info("User " . Auth::user()->email . " logged");
        return $this->createNewToken($token);
    }

    public function profile()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        Log::info("User " . Auth::user()->email . " logged out");
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        Log::info("User " . Auth::user()->email . " refreshed session");
        return $this->createNewToken(auth()->refresh());
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
