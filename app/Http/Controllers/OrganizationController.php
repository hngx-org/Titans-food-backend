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
     * @OA\Post(
     *     path="/api/v1/organization/create",
     *     tags={"Organization"},
     *     summary="Create Organization",
     *     tags={"Organization"},
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
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="currency_code",
     *                     type="string"
     *                 ),
     *                 example={"organization_name":"Example Organization", "lunch_price":1000, "currency_code":"NGN"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status":"success", "message":"Organization Created Successfully", "data":{}}, summary="Organization create"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="UNPROCESSABLE_ENTITY",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status":"failed", "message":"You already belong to a company", "data":null}, summary="Organization create"),
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
     * @OA\Post(
     *     path="/api/v1/organization/staff/signup",
     *     tags={"Organization"},
     *     summary="Create a user within an organization",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="otp_token",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="profile_pic",
     *                     type="string",
     *                     format="binary",
     *                     description="Optional: Profile picture (if provided)"
     *                 ),
     *                 example={
     *                     "first_name": "John",
     *                     "last_name": "Doe",
     *                     "email": "john.doe@example.com",
     *                     "otp_token": "123456",
     *                     "password": "your_password",
     *                     "phone": "1234567890",
     *                     "profile_pic": "example.jpg",
     *                 }
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *      response=201,
     *      description="User signed up successfully",
     *      @OA\JsonContent(
     *          @OA\Property(property="status_code", type="integer", example=201),
     *          @OA\Property(property="status", type="string", example="success"),
     *          @OA\Property(property="message", type="string", example="User signed up successfully"),
     *          @OA\Property(
     *              property="data",
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="first_name", type="string", example="John"),
     *              @OA\Property(property="last_name", type="string", example="Doe"),
     *              @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *              @OA\Property(property="is_admin", type="boolean", example=false),
     *              @OA\Property(property="org_id", type="integer", example=1),
     *              @OA\Property(property="phone", type="string", example="1234567890"),
     *              @OA\Property(property="profile_pic", type="string", example="example.jpg"),
     *          )
     *      )
     *  ),
     *      @OA\Response(
     *          response=401,
     *          description="Authentication failed",
     *          @OA\JsonContent(
     *              @OA\Property(property="status_code", type="integer", example=401),
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Authentication failed")
     *          )
     *      )
     * )
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
     * @OA\Patch(
     *     path="/api/v1/organization/lunch-price",
     *     tags={"Organization"},
     *     summary="Update lunch price",
     *     tags={"Organization"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="lunch_price",
     *                     type="integer"
     *                 ),
     *                 example={"lunch_price":1000}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status":"success", "message":"Lunch price updated successfully", "data":{}}, summary="Update lunch price"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="UNAUTHORIZED",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status":"failed", "message":"You are not authorized to perform this action", "data":null}, summary="Update lunch price"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="UNPROCESSABLE_ENTITY",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status":"failed", "message":"Error updating lunch price", "data":null}, summary="Update lunch price"),
     *         )
     *     ),
     * )
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
     * @OA\Get(
     *     path="/api/v1/organization",
     *     tags={"Organization"},
     *     summary="Get a list of organizations not deleted",
     *     tags={"Organization"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"data": {}}, summary="Get list of organizations not deleted"),
     *         )
     *     ),
     * )
    */

    public function getOrganization() {
        // Retrieve all organizations that are not deleted
        $organizations = Organization::where('is_deleted', false)->get();

        return response()->json(['data' => $organizations]);
    }
}
