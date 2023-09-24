<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Random;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/v1/auth/user/forgot-password",
     *     summary="Send a password reset OTP to the user's email",
     *     tags={"Authentication"},git re
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string", example="user@example.com"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="OTP sent successfully"),
     *             @OA\Property(property="statusCode", type="integer", example=200),
     *             @OA\Property(property="data", type="null"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found"),
     *             @OA\Property(property="statusCode", type="integer", example=404),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="object"),
     *             @OA\Property(property="statusCode", type="integer", example=422),
     *         )
     *     )
     * )
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email'
        ]);
        $fields = Validator::make($request->all(), [
            'email' => 'required|string|email'
        ]);

        if ($fields->fails()) {
            return response()->json([
                'message' => $fields->messages(),
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'statusCode' => Response::HTTP_NOT_FOUND,
            ], Response::HTTP_NOT_FOUND); // 404
        }

        $otp = mt_rand(100000, 999999);

        // no column in table, storing in cache for 30 minutes
        Cache::put('password_reset_email', $request->input('email'), now()->addMinutes(30));

        Cache::put('password_reset_otp', $otp, now()->addMinutes(30));

        // $user->otp = $otp;
        // $user->save();

        Mail::to($request->input('email'))->send(new ResetPasswordMail($otp));


        return response()->json([
            "message" => "OTP sent successfully",
            "statusCode" => Response::HTTP_OK,
            "data" => null
        ], Response::HTTP_OK); // 200
    }
}
