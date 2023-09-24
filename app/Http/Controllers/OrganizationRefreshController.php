<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizationRefreshController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout a user",
     *     @OA\Response(
     *         response=200,
     *         description="User logout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User logout successfully"),
     *             @OA\Property(property="statusCode", type="integer", example=200),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string")),
     *         )
     *     )
     * )
     */
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
