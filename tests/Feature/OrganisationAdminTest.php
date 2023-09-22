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



     public function test_admin_can_create_organisation()
     {
         $user = $this->authAdmin();
         $user->org_id= null;
         $response = $this->putJson(route('organization.store'),[
            'organization_name' => 'jane@example.com',
            'lunch_price' => 3000.00,
            'currency_code' => 'NGN'

         ])->assertOk();
         //$this->assertDatabaseHas('organizations',['name' => 'jane@example.com']);

     }
     public function test_admin_can_invite()
     {
         $user = $this->authAdmin();
         $response = $this->postJson(route('organization_invite.store'),[
             'email' => 'jane@example.com'
         ])->assertOk();
         $this->assertDatabaseHas('organization_invites',['email' => 'jane@example.com']);

     }

    //  public function test_admin_can_update_walet_balance()
    //  {
    //      $user = $this->authAdmin();
    //      $response = $this->putJson(route('organization.store'),[
    //         'organization_name' => 'jane@example.com',
    //         'lunch_price' => 3000.00,
    //         'currency_code' => 'NGN',
    //      ])->assertOk();
    //      $this->assertDatabaseHas('organization_invites',['organization_name' => 'jane@example.com']);

    //  }
}
