<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrganizationPasswordController extends Controller
{
    public function logout()
    {
        if (Auth::guard()->user() !== null) {
            /** @var \App\Models\User $user */
            $user = Auth::guard()->user();
            // $user->tokens()->revoke();
            $user->tokens()->delete();

            return response()->json([
                "message" => "User Logout successfully",
                "statusCode" => Response::HTTP_OK, // 200
                "data" => []
            ], Response::HTTP_OK); // returning response
        } else {
            return response()->json([
                "message" => "Not Logged In",
                "statusCode" => Response::HTTP_NO_CONTENT, // 204
                "data" => []
            ], Response::HTTP_NO_CONTENT); // returning response
        }
    }

    public function refreshToken(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard()->user();
        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            "message" => "User token refreshed successfully",
            "statusCode" => Response::HTTP_OK, // 200
            "data" => [
                "access_token" => $token,

            ]
        ], Response::HTTP_OK); // returning response
    }
}
