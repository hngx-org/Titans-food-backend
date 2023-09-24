<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizationRefreshController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     summary="Logout a user",
     *     tags={"User"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
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

    /**
     * @OA\Post(
     *     path="/api/v1/refresh-token",
     *     summary="Refresh user's access token",
     *     tags={"User"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response=200,
     *         description="User token refreshed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User token refreshed successfully"),
     *             @OA\Property(property="statusCode", type="integer", example=200),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="access_token", type="string", example="your_access_token_here")
     *             ),
     *         )
     *     )
     * )
     */
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
