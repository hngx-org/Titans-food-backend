<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrganisationAdminTest extends TestCase
{
    use RefreshDatabase;
    public function setup(): void
    {
        parent::setup();
        $this->user = $this->authUser();
    }
    /**
     * A basic feature test example.
     */

     public function test_admin_can_invite()
     {
         $user = $this->authAdmin();
         $response = $this->postJson('/api/organization/invite',[
             'email' => 'jane@example.com'
         ])->assertOk();
         $this->assertDatabaseHas('organization_invites',['email' => 'jane@example.com']);

     }

    //  public function test_admin_can_create_organisation()
    //  {
    //      $user = $this->authAdmin();
    //      $response = $this->putJson('/api/organization/create',[
    //         'organization_name' => 'jane@example.com',
    //         'lunch_price' => '3000.00',
    //      ])->assertOk();
    //      $this->assertDatabaseHas('organization_invites',['organization_name' => 'jane@example.com']);

    //  }
}
