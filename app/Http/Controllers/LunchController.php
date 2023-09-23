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

class LunchController extends Controller
{
    use MessageTrait;
    /**
     * Display a listing of the resource.
     */
       /**
     * @OA\Get(
     *     path="/api/v1/lunch",
     *     summary="Get All Lunch for User",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": 200, "lunch":{}}, summary="Get All Lunch for User"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="NOT_FOUND",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": 404, "lunch":"no record found"}, summary="Get All Lunch for User"),
     *         )
     *     )
     * )
    */
    public function index()
    {
        $lunch = lunch::all();
        if($lunch->count() >0){
            return response()->json([
                'status' => 200,
                'lunch' => $lunch
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
     *     path="/api/v1/user/bank",
     *     summary="Send lunch credits",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="receivers",
     *                     type="array"
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
     *             @OA\Examples(example="result", value={"message":"Insufficient fund!"}, summary="Send lunch credits"),
     * *             @OA\Examples(example="result", value={"message":"You cannot appraise yourself"}, summary="Send lunch credits"),
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
