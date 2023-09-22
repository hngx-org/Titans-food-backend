<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Organization;
use App\Traits\MessageTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;

class OrganizationController extends Controller
{
    use MessageTrait;
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
    public function update(StoreOrganizationRequest $request)
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
    public function store(StoreOrganizationRequest $request)
    {
        if(!Auth::user()->is_admin){
            $data = Organization::create([
                'name' => $request->organization_name,
                'currency_code' => $request->currency_code,
                'lunch_price' => $request->lunch_price
            ]);
            
            if($data){
                User::query()->where('id', Auth::user()->id)->update([
                    'is_admin' => true,
                    'org_id' => $data->id,
                ]);
            }
            return $this->success('Organization Created Successfully', 200, $data);                
        }
        return $this->error('Unable to setup multiple organizations', 422);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        //
    }

    public function getOrganization() {
        // Retrieve all organizations that are not deleted
        $organizations = Organization::where('is_deleted', false)->get();

        return response()->json(['data' => $organizations]);
    }
}