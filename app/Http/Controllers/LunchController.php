<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lunch;
use App\Models\Organization;
use App\Traits\MessageTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\OrganizationLunchWallet;
use App\Http\Requests\StoreLunchRequest;
use App\Http\Requests\UpdateLunchRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class LunchController extends Controller
{
    use MessageTrait;
/**
 * @OA\Get(
 *     path="/api/v1/lunch",
 *     summary="Retrieve a user's lunch data",
 *     tags={"Lunch"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Lunch data retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="lunch", type="object",
 *                 @OA\Property(property="received", type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="id", type="integer", example=37671158),
 *                         @OA\Property(property="org_id", type="integer", example=84487725),
 *                         @OA\Property(property="sender_id", type="integer", example=99993291),
 *                         @OA\Property(property="receiver_id", type="integer", example=99993287),
 *                         @OA\Property(property="quantity", type="integer", example=1),
 *                         @OA\Property(property="redeemed", type="integer", example=0),
 *                         @OA\Property(property="note", type="string", example="Thank you rahman"),
 *                         @OA\Property(property="created_at", type="string", example="2023-09-24T12:34:43.000000Z"),
 *                         @OA\Property(property="updated_at", type="string", example="2023-09-24T12:34:43.000000Z"),
 *                         @OA\Property(property="is_deleted", type="integer", example=0),
 *                     )
 *                 ),
 *                 @OA\Property(property="sent", type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="id", type="integer", example=37671155),
 *                         @OA\Property(property="org_id", type="integer", example=84487725),
 *                         @OA\Property(property="sender_id", type="integer", example=99993287),
 *                         @OA\Property(property="receiver_id", type="integer", example=99993291),
 *                         @OA\Property(property="quantity", type="integer", example=1),
 *                         @OA\Property(property="redeemed", type="integer", example=0),
 *                         @OA\Property(property="note", type="string", example="f"),
 *                         @OA\Property(property="created_at", type="string", example="2023-09-24T12:20:59.000000Z"),
 *                         @OA\Property(property="updated_at", type="string", example="2023-09-24T12:20:59.000000Z"),
 *                         @OA\Property(property="is_deleted", type="integer", example=0),
 *                     )
 *                 ),
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No lunch data found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="lunch", type="string", example="no record found"),
 *         )
 *     )
 * )
 */

    public function index()
    {
        $user = auth()->user();
        
        if(!empty($user->sentLunches) || !empty($user->receivedLunches)){
            return response()->json([
                'status' => 200,
                'lunch' => [
                    'received' => JsonResource::collection($user->receivedLunches),
                    'sent' => JsonResource::collection($user->sentLunches)
                ]
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'lunch' => 'no record found'
            ], 404);
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
     * Send lunch credits.
     *
     * Creates a lunch request, deducts lunch credits from the sender, and credits lunch credits to the receivers.
     *
     * @group Lunch
     * @authenticated
     * @param \App\Http\Requests\StoreLunchRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @bodyParam receivers array required An array of user IDs who will receive the lunch.
     * @bodyParam quantity integer required The quantity of lunches to send.
     * @bodyParam note string Additional note for the lunch request.
     *
     * @response {
     *     "message": "Lunch request created successfully",
     *     "statusCode": 201,
     *     "data": {
     *         "lunch_id": 1,
     *         "org_id" => 1,
     *         "sender_id" => 3,
     *         "receiver_id" => 2,
     *         "quantity" => 2,
     *         "note" => "Thank you for the good work",
     *     }
     * }
     * @response 422 {
     *     "error": "You cannot appraise yourself" // Or "Insufficient fund!"
     * }
     */
    /**
     * @OA\Post(
     *     path="/api/v1/lunch",
     *     summary="Send lunch credits",
     *     tags={"Lunch"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="receivers",
     *                     type="array",
     *                     @OA\Items(
     *                        type="array",
     *                        @OA\Items()
     *                     ),
     *                 ),
     *                 @OA\Property(
     *                     property="quantity",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="note",
     *                     type="string"
     *                 ),
     *                 example={"receivers":"[]", "quantity":5, "note":"Thank you for the good work"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"message":"Lunch request created successfully", "statusCode": 200, "data":{}}, summary="Send lunch credits"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="UNPROCESSABLE_ENTITY",
     *         @OA\JsonContent(
     *             @OA\Examples(example="insufficient_fund", value={"message":"Insufficient fund!"}, summary="Send lunch credits"),
     *             @OA\Examples(example="self_appraisal", value={"message":"You cannot appraise yourself"}, summary="Send lunch credits"),
     *         )
     *     ),
     * )
     */
    public function store(StoreLunchRequest $request)
    {
        #Ensure user cannot appraise self
        if(in_array(Auth::user()->id, $request->input('receivers'))){
            return $this->error('You cannot appraise your self', 422);
        }
        
        $lunch_price = Organization::query()->where('id', auth()->user()->org_id)->first()->lunch_price;
        $total_debit = count($request->input('receivers')) * $request->quantity * $lunch_price;

        #Process-Sender End of the transaction
        if(auth()->user()->is_admin){
            $lunch_wallet = OrganizationLunchWallet::where('org_id', auth()->user()->org_id);
            $wallet_balance = $lunch_wallet->value('balance');
            $balance_key = 'balance';
        } else{
            $lunch_wallet = User::where('id', auth()->user()->id);
            $wallet_balance = $lunch_wallet->value('lunch_credit_balance');
            $balance_key = 'lunch_credit_balance';
        }
        $remainder = $wallet_balance - $total_debit;

        if($wallet_balance < $total_debit) return $this->error('Insufficient fund!', 422);
        $lunch_wallet->update([$balance_key => $remainder]);

        
        #Process Receiver-End of the transaction
        foreach($request->input('receivers') as $receiver_id){
            $receiver = User::query()->where('id', $receiver_id);
            $credit_amount = $request->quantity * $lunch_price;

            $receiver_balance = $receiver->value('lunch_credit_balance');
            $total_balance = $credit_amount + $receiver_balance;

            $receiver->update(['lunch_credit_balance' => $total_balance]);

            $lunch = Lunch::create([
                'org_id' => auth()->user()->org_id,
                'sender_id' => auth()->user()->id,
                'receiver_id' => $receiver_id,
                'quantity' => $request->quantity,
                'note' => $request->note,
            ]);
        }    
        return $this->success('Lunch request created successfully', 201, $lunch);
    }

    /**
     * Retrieve a lunch by its ID.
     *
     * Retrieves a lunch request by its unique ID and returns its details.
     *
     * @group Lunch
     * @authenticated
     * @param int $Id The ID of the lunch request to retrieve.
     * @return \Illuminate\Http\JsonResponse
     *
     * @urlParam Id integer required The ID of the lunch request to retrieve.
     *
     * @response {
     *     "message": "Lunch request created successfully",
     *     "statusCode": 200,
     *     "data": {
     *         "receiver_id": 1,
     *         "sender_id": 2,
     *         "quantity": 3,
     *         "redeemed": false,
     *         "note": "Additional note",
     *         "created_at": "2023-09-22T12:34:56Z",
     *         "id": 1
     *     }
     * }
     * @response 404 {
     *     "message": "Lunch request not found",
     *     "statusCode": 404
     * }
     */
    /**
         * @OA\Get(
     *     path="/api/v1/lunch/{id}",
     *     tags={"Lunch"},
     *     summary="Retrieve a lunch by ID",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Get lunch by ID",
     *         required=true,
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"statusCode": 200, "message":"Lunch request created successfully", "data":{}}, summary="Retrieve a lunch by ID"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="NOT_FOUND",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"statusCode": 404, "message":"Lunch request not found"}, summary="Retrieve a lunch by ID"),
     *         )
     *     )
     * )
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
