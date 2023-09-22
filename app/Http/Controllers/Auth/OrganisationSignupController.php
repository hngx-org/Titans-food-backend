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
     * Register a new user.
     *
     * Registers a new user with the provided information and returns a success response upon successful registration.
     *
     * @group Authentication
     * @param \App\Http\Requests\OrganizationSignUpRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @bodyParam first_name required User's first name. Example: John
     * @bodyParam last_name required User's last name. Example: Doe
     * @bodyParam email string required User's email address. Example: user@example.com
     * @bodyParam password string required User's password. Example: mypassword
     *
     * @response {
     *     "status_code": 201,
     *     "status": "success",
     *     "message": "User signed up successfully",
     *     "data": {
     *         "id": 1,
     *         "first_name": "John",
     *         "last_name": "Doe",
     *         "email": "user@example.com",
     *         "is_admin": true,
     *         "password_hash": "$2y$10$..."
     *     }
     * }
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
