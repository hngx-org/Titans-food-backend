<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLunchRequest;
use App\Http\Requests\UpdateLunchRequest;
use App\Models\Lunch;

class LunchController extends Controller
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
    public function store(StoreLunchRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $Id)
    {

        //get the lunch from the database using the id, if it doesn't exist, return a 404
        if (!Lunch::find($Id)) {
            return response()->json([
                'message' => 'Lunch request not found',
                'statusCode' => 404,
            ], 404);
        }

        //get the lunch from the database using the id

        $lunchData = Lunch::where('id', $Id)->first();

//        {
//            "receiver_id": "",
//		      "sender_id": "",
//	          "quantity": 5,
//		      "redeemed": false,
//	          "note": "Special instructions for the lunch",
//		      "created_at": "",
//		      "id": ""
//	      }

        // return the response with only the above fields

        $lunchResponse = $lunchData->only([
            'receiver_id',
            'sender_id',
            'quantity',
            'redeemed',
            'note',
            'created_at',
            'id'
        ]);

        return response()->json([
            'message' => 'Lunch request created successfully',
            'statusCode' => 200,
            'data' => $lunchResponse
        ], 200);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lunch $lunch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLunchRequest $request, Lunch $lunch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lunch $lunch)
    {
        //
    }
}
