<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizationRefreshController extends Controller
{
    public function logout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $token = $user->revokeToken();

        return response()->json([
            "message" => "User logout successfully",
            "statusCode" => Response::HTTP_OK, // 200
            "data" => []
        ], Response::HTTP_OK); // returning response
    }

    public function refreshToken(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $token = $user->createToken();

        return response()->json([
            "message" => "User token refreshed successfully",
            "statusCode" => Response::HTTP_OK, // 200
            "data" => [
                "access_token" => $token
            ]
        ], Response::HTTP_OK); // returning response

    }
}
