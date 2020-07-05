<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use Log;
use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\SessionLoginRequest;

class SessionController extends Controller
{

    public function login(SessionLoginRequest $request){

        $token = auth()->attempt($request->validated());
        if (!$token) {
            Log::info("Attempt to login ".$request->email." logged");
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        Log::info("User ".Auth::user()->email." logged");
        return $this->createNewToken($token);

    }

    public function profile() {
        return response()->json(auth()->user());
    }

    protected function createNewToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


}
