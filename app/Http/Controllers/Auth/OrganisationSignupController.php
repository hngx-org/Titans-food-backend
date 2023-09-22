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
     * Display a listing of the resource.
     *
     * @return JsonResponse
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
