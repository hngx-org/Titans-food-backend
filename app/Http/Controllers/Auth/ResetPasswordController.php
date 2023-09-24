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
     *     summary="Reset user password",
     *     tags={"Authentication"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="note",
     *                     type="string"
     *                 ),
     *                 example={"email":"user@example.com", "note":"Thank you for the good work"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"message":"OTP sent successfully", "statusCode": 200, "data":{}}, summary="Reset user password"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="UNPROCESSABLE_ENTITY",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"message":{"[Field] is required", "[Field] is required"}, "statusCode":422}, summary="Reset user password"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="NOT_FOUND",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"message":"User not found", "statusCode":404}, summary="Reset user password"),
     *         )
     *     ),
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