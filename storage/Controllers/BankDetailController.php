<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BankDetailController extends Controller
{
    //
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
}
