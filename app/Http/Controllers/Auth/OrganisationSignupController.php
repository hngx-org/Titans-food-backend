<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\OrganizationSignUpRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class OrganisationSignupController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function register(OrganizationSignUpRequest $request)
    {
        //$user = User::create($request->validated());
       // dd());
        $password= Hash::make($request->password);
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'is_admin' => true,
            'password' => $password
        ]);


        if (!$token = auth()->attempt($request->only('email', 'password'))) {
            //return ['message' => 'Unable to sign user in', 'status' => 401];
        }
        $token = auth()->attempt($request->only('email', 'password'));


        return response()->json(['status_code' => Response::HTTP_CREATED, 'status' => 'success', 'message' => 'User signed up successfully', 'data'=> $user, 'token' => $token], Response::HTTP_CREATED);
    }

}
