<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganizationInviteRequest;
use App\Http\Requests\UpdateOrganizationInviteRequest;
use App\Mail\OrganizationInviteMail;
use App\Models\Organization;
use App\Models\OrganizationInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


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
     * Store a newly created resource in storage.
     */
    public function store(StoreOrganizationInviteRequest $request)
    {
        //retrieve authenticated user
        $authUser = auth()->user();


    // Validate the request
    // $request->validate([
    //     'email' => [
    //         'required',
    //         'email',
    //         'unique:organization_invites,email,NULL,id,org_id,' . auth()->user()->org_id
    //     ],
    // ], [
    //     'email.required' => 'The email field is required.',
    //     'email.email' => 'Invalid email format.',
    //     'email.unique' => 'This email has already been invited.',
    // ]);

        //generate token
        $token = Str::random(8);

        //create organization invite
        OrganizationInvite::create([
            'email' => $request->input('email'),
            'token' => $token,
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
