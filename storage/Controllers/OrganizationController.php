<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\Organization;
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
     * @OA\Put(
     *     path="/api/organization/create",
     *     summary="Create Organization",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="organization_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="lunch_price",
     *                     type="string"
     *                 ),
     *                 example={"first_name":"John", "last_name":"Mark", "email":"user@example.com", "password":"1Password"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"organization_name":"", "lunch_price": ""}, summary="Organization create"),
     *         )
     *     )
     * )
     */
    public function update(UpdateOrganizationRequest $request, Organization $organization)
    {
        if(Auth::user()->isAdmin === true){
        
            $validated = $request->validated();
    
            $organization->update($validated);

            return response()->json([
                'organization_name' => $organization->name,
                'lunch_price'  => $organization->lunch_price
            ], 200);
                
        }else{
            return response()->json([
                'message' => 'You are not authorized to perform this action!'
            ], 403);
        }
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