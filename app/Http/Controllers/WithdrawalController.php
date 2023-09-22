<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWithdrawalRequest;
use App\Http\Requests\UpdateWithdrawalRequest;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WithdrawalController extends Controller
{
    /**
     * Retrieve a user's withdrawal history.
     *
     * Retrieves the withdrawal history for the authenticated user.
     *
     * @group Withdrawal
     * @return \Illuminate\Http\JsonResponse
     *
     * @response {
     *     "message": "User details fetched",
     *     "status": "success",
     *     "statusCode": 200,
     *     "data": {
     *         "withdrawals": [
     *             {
     *                 "withdrawal_id": "xxxx",
     *                 "user_id": 1,
     *                 "amount": 100.00,
     *                 "created_at": "2023-09-22T12:34:56Z"
     *             },
     *              {
     *                  "withdrawal_id": "xxxx",
     *                  "user_id": 1,
     *                  "amount": 200.00,
     *                  "created_at": "2023-09-22T12:34:56Z"
     *              },
     *         ]
     *     }
     * }
     * @response {
     *     "error": "user not found"
     * }
     */
    public function index()
    {
        $withdrawal = Withdrawal::where('user_id',Auth::id())->get();
        if ($withdrawal->isEmpty()) :
            return response()->json([
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
     * Create a new withdrawal request.
     *
     * Creates a new withdrawal request for the authenticated user.
     *
     * @group Withdrawal
     * @param \App\Http\Requests\StoreWithdrawalRequest $request
     * @param \App\Models\Withdrawal $withdrawal
     * @return \Illuminate\Http\JsonResponse
     *
     * @bodyParam amount numeric required The withdrawal amount.
     *
     * @response {
     *     "message": "Withdrawal request created",
     *     "statusCode": 201,
     *     "data": {
     *         "withdrawal_id": "xxxx",
     *         "user_id": 1,
     *         "status": "success",
     *         "amount": 100.00,
     *         "created_at": "2023-09-22T12:34:56Z"
     *     }
     * }
     * @response {
     *     "error": "Withdrawal request not Created"
     * }
     */
    public function store(StoreWithdrawalRequest $request, Withdrawal $withdrawal)
    {
        $withdrawal->user_id =Auth::id(); //Getting Authenticated user Id not Request
        $withdrawal->amount = $request->amount;
        $checking=$withdrawal->save();
        if (!$checking) :
            return response()->json([
                "error" => "Withdrawal request not Created"
            ]);
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
        ]);
    }

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
