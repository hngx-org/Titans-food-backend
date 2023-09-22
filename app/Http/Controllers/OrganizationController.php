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
     * Create a new organization.
     *
     * Creates a new organization if the authenticated user is an admin and associates it with the user by updating the `org_id` field.
     *
     * @group Organizations
     * @param \App\Http\Requests\StoreOrganizationRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @bodyParam name string required The name of the organization.
     * @bodyParam description string The description of the organization (optional).
     *
     * @response {
     *     "data": {
     *         "id": 1,
     *         "name": "Example Organization",
     *         "description": "A sample organization",
     *         // Add other organization fields here
     *     },
     *     "message": "success",
     *     "statusCode": 200
     * }
     * @response 403 {
     *     "message": "You are not authorized to perform this action!"
     * }
     */
    public function store(StoreOrganizationRequest $request)
    {
        $user = Auth::user();
        if(is_null($user->org_id)){
            $organization = Organization::create($request->validated());

            $user->org_id = $organization->id;
            $user->save();

            return response()->json([
                'data' => $organization,
                "message"=> "success",
                "statusCode"=> 200,
            ], 200);

        }else{
            return response()->json([
                'message' => 'You are not authorized to perform this action!'
            ], 403);
        }
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
     * Update an organization's information.
     *
     * Updates an organization's information if the authenticated user is an admin.
     *
     * @group Organizations
     * @authenticated
     * @param \App\Http\Requests\UpdateOrganizationRequest $request
     * @param \App\Models\Organization $organization
     * @return \Illuminate\Http\JsonResponse
     *
     * @urlParam organization required The ID of the organization to update. Example: 1
     * @bodyParam name string required The new name of the organization.
     * @bodyParam lunch_price numeric required The new lunch price for the organization.
     *
     * @response {
     *     "organization_name": "Updated Organization Name",
     *     "lunch_price": 15.99
     * }
     * @response 403 {
     *     "message": "You are not authorized to perform this action!"
     * }
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

    /**
     * Retrieve a list of organizations.
     *
     * Retrieves a list of organizations that are not marked as deleted.
     *
     * @group Organizations
     * @authenticated
     * @return \Illuminate\Http\JsonResponse
     *
     * @response {
     *     "data": [
     *         {
     *             "id": 1,
     *             "name": "Organization 1",
     *             "lunch_price": 2000
     *         },
     *          {
     *              "id": 2,
     *              "name": "Organization 2",
     *              "lunch_price": 1000
     *          },
     *
     *     ]
     * }
     */
    public function getOrganization() {
        // Retrieve all organizations that are not deleted
        $organizations = Organization::where('is_deleted', false)->get();

        return response()->json(['data' => $organizations]);
    }
}
