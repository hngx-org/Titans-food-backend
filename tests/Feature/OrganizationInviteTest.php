<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Organization;
use App\Models\OrganizationInvite;
use App\Mail\OrganizationInviteMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Response;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrganizationInviteTest extends TestCase
{

    use RefreshDatabase;

    public function test_store_method_creates_organization_invite(): void
    {
        $adminToken = $this->getAdminToken();
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $requestData = [
            'email' => 'invitee@titan.com',
        ];

        Mail::fake(); // Prevent actual email sending
        $response = $this->postJson(route('organization_invite.store'), $requestData, ['Authorization' => 'Bearer '. $adminToken]);



        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'success',
                'statusCode' => 200,
                'data' => null,
            ]);

        // $this->assertDatabaseHas('organization_invites', [
        //     'email' => 'invitee@titan.com',
        //     'org_id' => $organization->id,
        // ]);
    }

}
