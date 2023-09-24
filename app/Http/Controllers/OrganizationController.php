<?php

namespace App\Http\Controllers;


use Exception;
use App\Models\User;
use App\Models\Organization;
use App\Traits\MessageTrait;
use App\Models\OrganizationInvite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Models\OrganizationLunchWallet;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        if(is_null(Auth::user()->org_id)){
                $org = Organization::create([
                    'name' => $request->organization_name,
                    'currency_code' => $request->currency_code,
                    'lunch_price' => $request->lunch_price
                ]);

                $org_wallet = OrganizationLunchWallet::create([
                    'org_id' => $org->id,
                    'balance' => 40000
                ]);

                if($org && $org_wallet){
                    User::query()->where('id', Auth::user()->id)->update([
                        'is_admin' => true,
                        'org_id' => $org->id,
                    ]);
                }
            return $this->success('Organization Created Successfully', 200, $org);
        }
        return $this->error('You already belong to a company', 422);
     }

    /**
     * Create a user within an organization using an invitation token.
     *
     * Creates a user within an organization based on the provided invitation token and user details.
     *
     * @group Users
     * @param \App\Http\Requests\StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @bodyParam first_name string required The first name of the user.
     * @bodyParam last_name string required The last name of the user.
     * @bodyParam email string required The email address of the user.
     * @bodyParam otp_token string required The OTP token for authentication.
     * @bodyParam phone string The phone number of the user.
     * @bodyParam profile_pic file The user's profile picture (optional).
     * @bodyParam password string required The user's password.
     *
     * @response {
     *     "status_code": 201,
     *     "status": "success",
     *     "message": "User signed up successfully",
     *     "data": {
     *         "id": 1,
     *         "first_name": "John",
     *         "last_name": "Doe",
     *         "email": "john.doe@example.com",
     *         "otp_token": "123456",
     *         "is_admin": false,
     *         "org_id": 1,
     *         "phone": "1234567890",
     *         "profile_pic": "example.jpg" // URL or file path
     *     }
     * }
     * @response 401 {
     *     "status_code": 401,
     *     "status": "error",
     *     "message": "Authentication failed"
     * }
     */
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
     * Update lunch price for the organization.
     *
     * Updates the lunch price for the organization if the authenticated user is an admin.
     *
     * @group Organizations
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @bodyParam lunch_price float required The new lunch price for the organization.
     *
     * @response {
     *     "message": "Lunch price updated successfully",
     *     "statusCode": 200
     * }
     * @response 401 {
     *     "message": "You are not authorized to perform this action",
     *     "statusCode": 401
     * }
     * @response 422 {
     *     "message": "Error updating lunch price",
     *     "statusCode": 422
     * }
     */
    public function update_lunch_price(Request $request)
    {
        $request->validate([
            'lunch_price' => ['required', 'numeric']
        ]);

        if(!auth()->user()->is_admin){
            return $this->error('You are not authorized to perform this action', 401);
        }
        $org = Organization::where('id', auth()->user()->org_id)->update([
            'lunch_price' => $request->lunch_price
        ]);
        if (!$org){
            return $this->error('error updating lunch price', 422);
        }
        return $this->success('lunch price updated successfully', 200);
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
