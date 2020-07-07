<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\ShowUserRequest;
use App\Util\Pattern;
use Log;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $paginate = $request->header('paginate') ?? 5;
        $users = User::paginate($paginate);
        Log::info("User requested all users  with paginate".$paginate);
        return response()->json($users);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create(array_merge(
                    $request->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        Log::info("Created a new user ID".$user->id);
        return response()->json([
            'message' => 'Successfully registered',
            'id' => $user->id
        ], 201);
    }

    public function show(Request $request)
    {
        if(Pattern::verifyValidUUID($request->header('x-user-id'))){
            $user = User::find($request->header('x-user-id'));
            Log::info("Displayed a user ID".$user->id);
            return response()->json($user);
        }else{
            Log::info("User requested a user with invalid UUID, bad request");
            return response()->json(['message' => 'UUID not valid'], 400);
        }


    }

}
