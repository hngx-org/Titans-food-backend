<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLunchRequest;
use App\Http\Requests\UpdateLunchRequest;
use App\Models\Lunch;

class LunchController extends Controller
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
    public function store(StoreLunchRequest $request)
    {
        #Ensure user cannot appraise self
        if(in_array(auth()->user()->id, $request->input('receivers'))){
            return $this->error('You cannot appraise your self', 422);
        }
        
        #Process-Sender End of the transaction
        if(auth()->user()->is_admin){
            $model = OrganizationLunchWallet::query();
            $key = 'org_id';
            $val = auth()->user()->org_id;
            $balance_key = 'balance';
        } else{
            $model = User::query();
            $key = 'id';
            $val = auth()->user()->id;
            $balance_key = 'lunch_credit_balance';
        }

        $lunch_price = Organization::query()->where('id', auth()->user()->org_id)->lunch_price;
        $total_debit = count($request->input('receivers')) * $request->quantity * $lunch_price;

        $lunch_wallet = $model->where($key, $val);
        $wallet_balance = $lunch_wallet->value($balance_key);
        $remainder = $wallet_balance - $total_debit;

        if($remainder < 0) return $this->error('Insufficient fund!', 422);
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

private function success($message, $code = 200, $data = [])
{
    return response()->json([
        'status' => 'success',
        'message' => $message,
        'data' => $data
    ], $code);
}

private function error($message, $code)
{
    return response()->json([
        'status' => 'failed',
        'message' => $message
    ], $code);
}

    /**
     * Display the specified resource.
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

//        {
//            "receiver_id": "",
//		      "sender_id": "",
//	          "quantity": 5,
//		      "redeemed": false,
//	          "note": "Special instructions for the lunch",
//		      "created_at": "",
//		      "id": ""
//	      }

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
