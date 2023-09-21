<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    public function updateBank(Request $request)
    {
        //--Get authenticated user
        //$user = auth()->user();        
        $user = User::find(1);
        
        //--validate the request parameters------
        $validated= $request->validate([
            'bank_name'=>'required|string',
            'bank_number'=>'required|string|unique:users,bank_number',
            'bank_code' => 'required|string',
            'bank_region' => 'required|string',
            'currency' => 'required|string',
            'currency_code' => 'required|string',
        ]);

        //------If successful validation update the bank details
        $updated = $user->update([
            'bank_name' => $validated['bank_name'],
            'bank_number' => $validated['bank_number'],
            'bank_code' => $validated['bank_code'],
            'bank_region' => $validated['bank_region'],
            'currency' => $validated['currency'],
            'currency_code' => $validated['currency_code']
        ]);

        //----Check for successful update-----------
        if ($updated) {
            return response()->json([
                "message"=> "successfully added bank account details",
                 "statusCode"=> 200,
                "data" => $user
            ]);
        }

        return response()->json([
            "message"=> "Error, please try again",
             "statusCode"=> 400            
        ]);
 
       
    }
}
