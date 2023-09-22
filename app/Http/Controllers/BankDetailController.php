<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class BankDetailController extends Controller
{
    /**
     * Add bank account details for the authenticated user.
     *
     * Adds bank account details, such as bank name, bank number, bank code, and currency, for the authenticated user.
     *
     * @group User
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @bodyParam bank_name string required The name of the bank.
     * @bodyParam bank_number string required The bank account number.
     * @bodyParam bank_code string required The bank code.
     * @bodyParam bank_region string required The bank region.
     * @bodyParam currency string required The currency used in the bank account.
     * @bodyParam currency_code string required The currency code.
     *
     * @response {
     *     "message": "successfully added bank account details",
     *     "statusCode": 200
     * }
     * @response {
     *     "message": "Error, please try again",
     *     "statusCode": 400
     * }
     */
    public function addBankDetails(Request $request)
    {
        //--Get authenticated user
        $user = auth()->user();

        //--validate the request parameters------
        $validated= $request->validate([
            'bank_name'=>'required|string',
            'bank_number'=>'required|string',
            'bank_code' => 'required|string',
            'bank_region' => 'required|string',
            'currency' => 'required|string',
            'currency_code' => 'required|string',
        ]);

        //------If successful validation update the bank account details
        $updated = $user->update([
            'bank_name' => $validated['bank_name'],
            'bank_number' => $validated['bank_number'],
            'bank_code' => $validated['bank_code'],
            'bank_region' => $validated['bank_region'],
            'currency' => $validated['currency'],
            'currency_code' => $validated['currency_code']
        ]);

        //----Check for successful update of the users table-----------
        if ($updated) {
            return response()->json([
                "message"=> "successfully added bank account details",
                 "statusCode"=> 200,
            ]);
        }
        //------------Error message if bank details are not updated
        return response()->json([
            "message"=> "Error, please try again",
             "statusCode"=> 400
        ]);


    }

    /**
     * View bank account details for the authenticated user.
     *
     * Retrieves and displays the bank account details, such as bank name, bank number, bank code, and currency, for the authenticated user.
     *
     * @group User
     * @return \Illuminate\Http\JsonResponse
     *
     * @response {
     *     "message": "Bank details found",
     *     "data": {
     *         "bank_name": "Bank Name",
     *         "bank_number": "Bank Account Number",
     *         "bank_code": "Bank Code",
     *         "bank_region": "Bank Region",
     *         "currency": "Currency",
     *         "currency_code": "Currency Code"
     *     },
     *     "statusCode": 200
     * }
     * @response 404 {
     *     "message": "No bank details found for the given user."
     * }
     */
    public function viewBankDetails()
    {
        $user = auth()->user();
        $bankDetails = $user->bank_number;

        if ($bankDetails->isEmpty()) {
            $message = 'No bank details found for the given user.';
            return response()->json(['message' => $message], 404);
        }

        $message = 'Bank details found';

        return response()->json(['message' => $message, 'data' => $bankDetails], 200);
    }
}
