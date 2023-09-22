<?php

namespace App\Http\Controllers;


use App\Models\Organization;
use App\Traits\MessageTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\OrganizationInvite;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

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
    public function update(StoreOrganizationRequest $request)
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
