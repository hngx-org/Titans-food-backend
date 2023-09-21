<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $persons = User::select('first_name', 'last_name', 'email', 'profile_pic', 'org_id')->get();

        if($persons->count() > 0){

            $formattedPersons = $persons->map(function ($person) {
                return [
                    'name' => $person->first_name . ' ' . $person->last_name,
                    'email' => $person->email,
                    'profile_picture' => $person->profile_pic,
                    'user_id' => $person->org_id, // You can update this based on your requirements
                ];
            });

            $data = [
                "message" => "All users list",
                "statusCode" => 200,
                "data" => $formattedPersons,
            ];
            return response()->json($data, 200);

        }else{

            $data = [
                'status' => 404,
                'status_message' => "No records found",
            ];
            return response()->json($data, 200);

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Search for a user using name or email
     */

    public function search($nameOrEmail)
    {
        $users = User::where('name', 'like', '%' . $nameOrEmail . '%')
                ->orWhere('email', 'like', '%' . $nameOrEmail . '%')
                ->get();

        if ($users->isEmpty()) {
            $message = 'No users found for the given name or email.';
            return response()->json(['message' => $message], 404);
        }
        
        $message = 'User found';

        return response()->json(['message' => $message, 'data' => $users], 200);
    }
}
