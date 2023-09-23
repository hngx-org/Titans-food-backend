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
    //
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