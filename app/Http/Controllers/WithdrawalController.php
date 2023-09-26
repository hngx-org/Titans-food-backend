<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWithdrawalRequest;
use App\Http\Requests\UpdateWithdrawalRequest;
use App\Models\User;
use App\Models\Withdrawal;
use App\Traits\MessageTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WithdrawalController extends Controller
{
    use MessageTrait;

    /**
     * @OA\Get(
     *     path="/api/v1/withdrawal/request",
     *     tags={"Withdrawal"},
     *     summary="Get Withdrawal Requests from User",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"message":"User details fetched", "statusCode": 200, "data":{}}, summary="Get Withdrawal Requests"),
     *         )
     *     )
     * )
    */
    public function index(Withdrawal $withdrawal)
    {
        $withdrawal = Withdrawal::where('user_id',Auth::id())->get();
        if ($withdrawal->isEmpty()) :
            return response()->json([
                "status"=>"Invalid",
                "status_code"=>404,
                "message" => "user not found",
                "error" => "user not found"

            ]);
        endif;
        return response()->json([
            "message" => "User details fetched",
            "status" => "success",
            "statusCode" => 200,
            "data" => [
                "withdrawals"=>$withdrawal
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/v1/withdrawal/request",
     *     tags={"Withdrawal"},
     *     summary="Withdrawal Request",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="amount",
     *                     type="integer"
     *                 ),
     *                 example={"amount":"1000"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"message":"Withdrawal request created ", "statusCode": 201, "data":{}}, summary="User Signup response"),
     *         )
     *     )
     * )
     */
    public function store(StoreWithdrawalRequest $request, Withdrawal $withdrawal)
    {
        $user = auth()->user();

        if(empty($user->bank_number) && empty($user->bank_code) && empty($user->bank_name)):
            return response()->json([
                "status" => "Invalid",
                "status_code" => 422,
                "message" => "Please add your bank details"
            ], 422);
        endif;

        if($user->lunch_credit_balance < $request->amount):
            return $this->error('Insufficient balance', 422);
        endif;

        $user->lunch_credit_balance = $user->lunch_credit_balance - $request->amount;
        $withdrawal->user_id = $user->id; //Getting Authenticated user Id not Request
        $withdrawal->amount = $request->amount;

        if($user->save()):
            $checking = $withdrawal->save();

            if (!$checking) :
                return response()->json([
                    "status"=>"Invalid",
                    "status_code"=>422,
                    "message" => "Withdrawal request not created"
                ]);
            endif;
            
        endif;

        return response()->json([
            "message" => "Withdrawal request created ",
            "statusCode" => 201,
            "data" => [
                "withdrawal_id" => Str::uuid(),
                "user_id" => $withdrawal->user->id,
                "status" => "success",
                "amount" => $request->amount,
                "created_at" => Carbon::now()
            ]
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show()
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Withdrawal $withdrawal)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWithdrawalRequest $request, Withdrawal $withdrawal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Withdrawal $withdrawal)
    {
        //
    }
}
