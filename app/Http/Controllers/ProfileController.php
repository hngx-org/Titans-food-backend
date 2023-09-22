<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    
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
                'data' => [
                    'id' => $user->id,
                    'full_name' => $fullName,
                    'email' => $user->email,
                    'profile_pic' => $user->profile_pic,
                    'isAdmin' => $user->is_admin, 
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching user data',
                'statusCode' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
}
