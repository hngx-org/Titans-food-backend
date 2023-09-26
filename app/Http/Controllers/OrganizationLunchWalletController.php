<?php

namespace App\Http\Controllers;

use App\Traits\MessageTrait;
use Illuminate\Http\Response;
use App\Models\OrganizationLunchWallet;
use App\Http\Requests\StoreOrganizationLunchWalletRequest;
use App\Http\Requests\UpdateOrganizationLunchWalletRequest;

class OrganizationLunchWalletController extends Controller
{
    use MessageTrait;
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
    public function store(StoreOrganizationLunchWalletRequest $request)
    {


    }

    /**
     * Display the specified resource.
     */
    public function show(OrganizationLunchWallet $organizationLunchWallet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrganizationLunchWallet $organizationLunchWallet)
    {
        //
    }

            /**
     * @OA\Patch(
     *     path="/api/v1/wallet",
     *     tags={"Wallet"},
     *     summary="Update organization wallet",
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
     *                 example={"amount":4000}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status":"success", "message":"wallet funded successfully", "data":{}}, summary="Update organization wallet balance"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="UNAUTHORIZED",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status":"failed", "message":"You are not authorized to perform this action", "data":null}, summary="Update organization wallet balance"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="UNPROCESSABLE_ENTITY",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status":"failed", "message":"Error funding wallet", "data":null}, summary="Update organization wallet balance"),
     *         )
     *     ),
     * )
     */
    public function update(UpdateOrganizationLunchWalletRequest $request)
    {
        if(!auth()->user()->is_admin){
            return $this->error('You are not authorized to perform this action', 401);
        }
        $wallet = OrganizationLunchWallet::where('org_id', auth()->user()->org_id);
        $total_balance = $wallet->value('balance') + $request->amount;

        $wallet->update([
            'balance' => $total_balance
        ]);
        if (!$wallet){
            return $this->error('error funding wallet', 422);
        }
        return $this->success('wallet funded successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrganizationLunchWallet $organizationLunchWallet)
    {
        //
    }
}
