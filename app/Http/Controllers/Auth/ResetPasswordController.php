<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\MessageTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Random;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordController extends Controller
{
    use MessageTrait;

    /**
     * @OA\Post(
     *     path="/api/v1/auth/user/forgot-password",
     *     summary="Reset user password",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 example={"email":"user@example.com"}
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
     *             @OA\Examples(example="result", value={"message":"[email] is required", "statusCode":422}, summary="Reset user password"),
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

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->error('User not found', Response::HTTP_NOT_FOUND);
        }

        $otp = mt_rand(100000, 999999);

        // no column in table, storing in cache for 30 minutes
        Cache::put('titans_password_reset', [
            'password_reset_email' => $request->input('email'),
            'password_reset_otp' => $otp
        ], now()->addMinutes(30));

        // Cache::put('password_reset_email', $request->input('email'), now()->addMinutes(30));
        // Cache::put('password_reset_otp', $otp, now()->addMinutes(30));

        Mail::to($request->input('email'))->send(new ResetPasswordMail($otp));

        return $this->success('OTP sent successfully', Response::HTTP_OK);
    }


/**
 * @OA\Post(
 *     path="/api/v1/auth/user/reset-password",
 *     summary="Reset user's password",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="otp_token", type="string", example="123456"),
 *             @OA\Property(property="password", type="string", example="new_password"),
 *             @OA\Property(property="password_confirmation", type="string", example="new_password"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password reset successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Password reset successfully"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error or invalid OTP",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Invalid OTP code"),
 *         )
 *     ),
 * )
 */

    public function resetPassword(Request $request)
    {
        $request->validate( [
            'otp_token' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

       $otp = ((object)Cache::get('titans_password_reset'));
        $user = User::where('email', $otp->password_reset_email);

        if($otp->password_reset_otp != $request->otp_token){
            return $this->error('Invalid OTP code', 422);
        }

        $password = Hash::make($request->password);
        $response = $user->update([
            'password_hash' => $password
        ]);
        return $this->success('Password reset successfully', Response::HTTP_OK);
    }

}