<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{

    public function login(Request $request){

        $fields = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]); // request body validation rules

        if($fields->fails()){
            return response()->json([
                'message' => $fields->messages(),
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
        } // request body validation failed, so lets return

        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'message' => 'Incorrect Email',
                'statusCode' => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED); // 401
        } // Attempt to authorize request using request email and password failed, so lets return

        $user = Auth::user(); // Grabbing the user details
        $token = $user->createToken(env('APP_NAME'))->plainTextToken; // Creating access_token

        return response()->json([
            "message" => "User authenticated successfully",
            "statusCode" => Response::HTTP_OK, // 200
            "data" => [
                "access_token" => $token,
                "email" => $user->email,
                "id" => $user->id,
                "isAdmin" => $user->isAdmin
            ]
        ], Response::HTTP_OK); // returning response
    }
}
