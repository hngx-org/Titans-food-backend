<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
   /**
     * @OA\Get(
     *     path="/api/v1/user/profile",
     *     tags={"User"},
     *     summary="Get User Profile Details",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"message":"User data fetched successfully", "statusCode": 200, "data":{}}, summary="Get User Profile"),
     *         )
     *     )
     * )
    */

    public function index()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'message' => 'User not authenticated',
                    'statusCode' => 401,
                ], 401);
            }

            $fullName = $user->first_name . ' ' . $user->last_name;

            return response()->json([
                'message' => 'User data fetched successfully',
                'statusCode' => 200,
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching user data',
                'statusCode' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

        /**
     * @OA\Post(
     *     path="/api/v1/auth/user/change-password",
     *     tags={"Profile"},
     *     summary="Change Password",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="current_password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string"
     *                 ),
     *                 example={"current_password":"1Password", "password":"password123", "password_confirmation":"password123"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"message":"Password changed successfully", "statusCode": 200, "data":{}}, summary="Password change response"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="UNPROCESSABLE_ENTITY",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"message":{"[Field] is required", "[Field] is required",}, "statusCode": 422}, summary="Password change response"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="BAD_REQUEST",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"message":"Passwords do not match", "statusCode": 400, "status":"error"}, summary="Password change response"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="UNAUTHORIZED",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"message":"Current password is incorrect", "statusCode": 403, "status":"error"}, summary="Password change response"),
     *         )
     *     ),
     * )
     */
    public function changePassword(Request $request){
        $user = auth()->user();
        $fields = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($fields->fails()){
            return response()->json([
                'message' => $fields->messages(),
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
        }

        if (!Hash::check($request->current_password, $user->password_hash)) {
            return response()->json([
                'status_code' => Response::HTTP_UNAUTHORIZED,
                'status' => 'error',
                'message' => 'Current password is incorrect',
            ], Response::HTTP_UNAUTHORIZED); // 403
        }

        $password = Hash::make($request->password);
        $response = $user->update([
            'password_hash' => $password
        ]);

        return response()->json([
            "message" => "Password changed successfully",
            "statusCode" => Response::HTTP_OK, // 200
            "data" => $user
        ], Response::HTTP_OK);
    }

}
