<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
    
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
        ]);
    }
    
}
