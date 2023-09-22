<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\OrganizationInvite;
use App\Mail\OrganizationInviteMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreOrganizationInviteRequest;
use App\Http\Requests\UpdateOrganizationInviteRequest;


class OrganizationInviteController extends Controller
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
     * Store an organization invitation.
     *
     * Creates an organization invitation with a generated token and sends an email invitation to the specified email address.
     *
     * @group Organizations
     * @authenticated
     * @param \App\Http\Requests\StoreOrganizationInviteRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @bodyParam email string required The email address to which the invitation will be sent.
     *
     * @response {
     *     "message": "success",
     *     "statusCode": 200,
     *     "data": null
     * }
     */
    public function store(StoreOrganizationInviteRequest $request)
    {
        //retrieve authenticated user
        $authUser = auth()->user();

        //generate token
        $token = Str::random(8);
        if(Auth::user()->is_admin === true){


                    OrganizationInvite::create([
            'email' => $request->input('email'),
            'token' => $token,
            'ttl' => Carbon::now()->addYear(),
            'org_id' => $authUser->org_id
        ]);

            //retrieve organization name
        $organization = Organization::where('id', $authUser->org_id)->first();
        $organizationName = $organization ? $organization->name : '';

        Mail::to($request->input('email'))->send(new OrganizationInviteMail($token, $organizationName));

        return response()->json([
            'message' => 'success',
            'statusCode' => 200,
            'data' => null,
        ], 200);

        } else {
            return response()->json([
                'message' => 'You are not authorized to perform this action!'
            ], 403);
        }

        //create organization invite




    }

    /**
     * Display the specified resource.
     */
    public function show(OrganizationInvite $organizationInvite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrganizationInvite $organizationInvite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrganizationInviteRequest $request, OrganizationInvite $organizationInvite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrganizationInvite $organizationInvite)
    {
        //
    }
}
