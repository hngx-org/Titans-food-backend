<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\Organanization;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
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
    public function store(StoreOrganizationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrganizationRequest $request, Organanization $organization)
    {
         
        $user_id = Auth::user()->id;

        $getUser = User::find($user_id);
        
        if ($getUser->org_id === null) {

            $validated = $request->validate();

            $organization = Organanization::create($validated);
     
            $org_id = $organization->id;

            $getUser->update([
                'org_id' => $org_id,
                'is_admin' => true
            ]);
        
            return response()->json([
                'organization_name' => $organization->name,
                'lunch_price' => $organization->lunch_price
            ], 200);
        } else {
            return response()->json([
                'message' => 'You are admin of an Organization already!'
            ], 201);
        }
        
    }
        

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        //
    }
}
