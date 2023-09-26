<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\OrganizationSignUpRequest;

class OrganisationSignupController extends Controller
{
       /**
     * @OA\Post(
     *     path="/api/auth/user/signup",
     *     summary="User Signup (Organization only)",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"first_name":"John", "last_name":"Mark", "email":"user@example.com", "password":"1Password"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"message":"User signed up successfully", "statusCode": 201, "data":{}}, summary="User Signup response"),
     *         )
     *     )
     * )
     */
    public function register(OrganizationSignUpRequest $request)
    { 
        $password= Hash::make($request->password);
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'is_admin' => true,
            'password_hash' => $password
        ]);

        return response()->json(['status_code' => Response::HTTP_CREATED, 'status' => 'success', 'message' => 'User signed up successfully', 'data'=> $user], Response::HTTP_CREATED);
    }

}
