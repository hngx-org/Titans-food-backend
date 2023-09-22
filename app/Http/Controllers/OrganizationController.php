<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\Organization;
use App\Models\OrganizationInvite;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

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
         
        $user_id = Auth::user()->id;

        $getUser = User::find($user_id);
        
        if ($getUser->org_id === null) {

            $validated = $request->validate();
        if(Auth::user()->isAdmin === true){

            $validated = $request->validated();

            $organization->update($validated);
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
        
        } else {
            return response()->json([
                'message' => 'You are not authorized to perform this action!'
            ], 403);
        }
    }

    public function createOrganizationUser(StoreUserRequest $request)
    {

        $invite = OrganizationInvite::where('email', $request->email)->where('token', $request->otp_token)->first();
        if (!$invite) {
            return response()->json([
                'status_code' => Response::HTTP_UNAUTHORIZED,
                'status' => 'error',
                'message' => 'Authentication failed',
            ], Response::HTTP_UNAUTHORIZED);
        }
        $filename = '';
        
        if($request->hasFile('profile_pic')) {
            $newFile = $request->file('profile_pic');
            $filename = $newFile->getClientOriginalName();
            $newFile->move('images', $filename);
        }
       

        $password = Hash::make($request->password);
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'otp_token' => $request->otp_token,
            'is_admin' => false,
            'org_id' => $invite->org_id,
            'phone' => $request->phone,
            'password_hash' => $password,
            'profile_pic' => $filename
        ]);
        return response()->json(
            [
                'status_code' => Response::HTTP_CREATED,
                'status' => 'success',
                'message' => 'User signed up successfully',
                'data' => $user
            ],
            Response::HTTP_CREATED
        );
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
