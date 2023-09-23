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
     * @authenticated
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

    /**
     * @OA\Post(
     *     path="/api/v1/user/bank",
     *     summary="Add bank account details",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="bank_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="bank_number",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="bank_code",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="bank_region",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="currency",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="currency_code",
     *                     type="string"
     *                 ),
     *                 example={"bank_name":"", "bank_number":"", "bank_code":"", "bank_region":"", "currency":"", "currency_code":""}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"message":"successfully added bank account details", "statusCode": 200}, summary="Add bank account details for the authenticated user"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="BAD_REQUEST",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"message":"Error, please try again", "statusCode": 200}, summary="Add bank account details for the authenticated user"),
     *         )
     *     ),
     * )
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
     * @authenticated
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
