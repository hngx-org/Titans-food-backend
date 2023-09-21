<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\Organanization;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
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
    public function store(StoreOrganizationRequest $request)
    {
        // {
        //     "receivers": ["user_id"], // this could contain multiple users
        //     "quantity": 5,
        //     "note": "Special instructions for the lunch"
        //   }

        $sender_id = auth()->user()->id;
        $receiver_ids = $request->input('receivers');
        $quantity = $request->quantity;
        $note = $request->note;
        $lunch_price = Organization::query()->where('id', auth()->user()->org_id)->lunch_price;
        $total_amount = count($receiver_ids) * $quantity * $lunch_price;

        if(auth()->user()->isAdmin){
            $lunch_wallet = OrganizationLunchWallet::query()->where('org_id', auth()->user()->org_id);
            $balance = $lunch_wallet->first()->balance;
        } else{
            $lunch_wallet = User::query()->where('id', auth()->user()->id);
            $balance = $lunch_wallet->first()->lunch_credit_balance;
        }

        $remainder = $balance - $total_amount;

        if($remainder < 0){
            return response()->json(['status' => false, 'message' => 'Insufficient balance'], 200);
        }



        


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
     * Update the specified resource in storage.
     */
    public function update(UpdateOrganizationRequest $request, Organanization $organization)
    {
        if(Auth::user()->isAdmin === true){
        
            $validated = $request->validated();
    
            $organization->update($validated);

            return response()->json([
                'organization_name' => $organization->name,
                'lunch_price'  => $organization->lunch_price
            ], 200);
                
            }else{
                return response()->json([
                    'message' => 'You are not authorized to perform this action!'
                ], 403);
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        //
    }
}
