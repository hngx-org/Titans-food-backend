<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * Retrieve a list of users.
     *
     * Retrieves a list of users with their basic information.
     *
     * @group Users
     * @authenticated
     * @return \Illuminate\Http\JsonResponse
     *
     * @response {
     *     "message": "All users list",
     *     "statusCode": 200,
     *     "data": [
     *          {
     *              "id": 1,
     *              "email": "john@example.com",
     *              "first_name": "John",
     *              "last_name": "Doe",
     *              "phonenumber": "1234567890",
     *              "profile_picture": "user-profile-picture-url",
     *              "bank_number": "1234-5678-9012-3456",
     *              "bank_code": "123456",
     *              "bank_name": "Bank Name",
     *              "isAdmin": true
     *          },
     *          {
     *               "id": 1,
     *               "email": "john@example.com",
     *               "first_name": "John",
     *               "last_name": "Doe",
     *               "phonenumber": "1234567890",
     *               "profile_picture": "user-profile-picture-url",
     *               "bank_number": "1234-5678-9012-3456",
     *               "bank_code": "123456",
     *               "bank_name": "Bank Name",
     *               "isAdmin": true
     *           }
     *
     *     ]
     * }
     * @response 404 {
     *     "status": 404,
     *     "status_message": "No records found"
     * }
     */
    /**
         * @OA\Get(
     *     path="/api/v1/user/all",
     *     summary="All all users list",
     *     tags={"User"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"statusCode": 200, "message":"All users list", "data":{}}, summary="All all users list"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="NOT_FOUND",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": 404, "status_message":"No records found."}, summary="Search profile by Name or Email"),
     *         )
     *     )
     * )
    */
    public function index()
    {
        $persons = User::select('first_name', 'last_name', 'email', 'profile_pic', 'org_id')->get();

        if($persons->count() > 0){

            $formattedPersons = $persons->map(function ($person) {
                return [
                    'name' => $person->first_name . ' ' . $person->last_name,
                    'email' => $person->email,
                    'profile_picture' => $person->profile_pic,
                    'user_id' => $person->org_id, // You can update this based on your requirements
                ];
            });

            $data = [
                "message" => "All users list",
                "statusCode" => 200,
                "data" => $formattedPersons,
            ];
            return response()->json($data, 200);

        }else{

            $data = [
                'status' => 404,
                'status_message' => "No records found",
            ];
            return response()->json($data, 200);

        }
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

    /**
     * Search for users by name or email.
     *
     * Searches for users based on the provided name or email and returns a list of matching users.
     *
     * @group Users
     * @authenticated
     * @param string $nameOrEmail The name or email to search for.
     * @return \Illuminate\Http\JsonResponse
     *
     * @urlParam nameOrEmail required The name or email to search for. Example: john@example.com
     *
     * @response {
     *     "message": "User found",
     *     "data": [
     *         {
     *             "id": 1,
     *             "email": "john@example.com",
     *             "first_name": "John",
     *             "last_name": "Doe",
     *             "phonenumber": "1234567890",
     *             "profile_picture": "user-profile-picture-url",
     *             "bank_number": "1234-5678-9012-3456",
     *             "bank_code": "123456",
     *             "bank_name": "Bank Name",
     *             "isAdmin": true
     *         },
     *         {
     *              "id": 1,
     *              "email": "john@example.com",
     *              "first_name": "John",
     *              "last_name": "Doe",
     *              "phonenumber": "1234567890",
     *              "profile_picture": "user-profile-picture-url",
     *              "bank_number": "1234-5678-9012-3456",
     *              "bank_code": "123456",
     *              "bank_name": "Bank Name",
     *              "isAdmin": true
     *          }
     *
     *     ]
     * }
     * @response 404 {
     *     "message": "No users found for the given name or email."
     * }
     */
        /**
         * @OA\Get(
     *     path="/api/v1/user/profile/{nameOrEmail}",
     *     tags={"User"},
     *     summary="Search profile by Name or Email",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="nameOrEmail",
     *         in="path",
     *         description="Search profile by Name or Email",
     *         required=true,
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"statusCode": 200, "message":"User found", "data":{}}, summary="Search profile by Name or Email"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="NOT_FOUND",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"statusCode": 404, "message":"No users found for the given name or email."}, summary="Search profile by Name or Email"),
     *         )
     *     )
     * )
    */
    public function search($nameOrEmail)
    {
        $users = User::where('first_name', 'like', '%' . $nameOrEmail . '%')
                ->orWhere('last_name', 'like', '%' . $nameOrEmail . '%')
                ->orWhere('email', 'like', '%' . $nameOrEmail . '%')
                ->get(['id', 'first_name', 'last_name', 'email']);

        if ($users->isEmpty()) {
            $message = 'No users found for the given name or email.';
            return response()->json(['message' => $message], 404);
        }
        $message = 'User found';

        return response()->json(['message' => $message, 'statusCode' => 200, 'data' => $users], 200);
    }
}
