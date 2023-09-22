<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        // Attempt to find the user by email
        $user = User::where('email', $request->email)->first();

        // Check if the user exists and the provided password matches the hashed password
        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            return response()->json([
                'status_code' => Response::HTTP_UNAUTHORIZED,
                'status' => 'error',
                'message' => 'Authentication failed',
            ], Response::HTTP_UNAUTHORIZED);
        }
        
        $token = $user->createToken($request->email)->plainTextToken; // Creating access_token

        return response()->json([
            "message" => "User authenticated successfully",
            "statusCode" => Response::HTTP_OK, // 200
            "data" => [
                "access_token" => $token,
                "email" => $user->email,
                "id" => $user->id,
                "isAdmin" => $user->is_admin,
                "org_id" => $user->org_id 

            ]
        ], Response::HTTP_OK); // returning response
    }
}