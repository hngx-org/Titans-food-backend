<?php

namespace Tests\Feature;
use App\Models\User;
use App\Models\Lunch;
use App\Models\Organization;
use App\Models\OrganizationLunchWallet;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LunchTest extends TestCase
{  
    use RefreshDatabase;

    public function test_show_all_or_returns_lunch_records()
    {
        $lunchRecords = Lunch::factory(4)->create();
        $response = $this->getJson(route('lunch.index'))
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure(
            [
                'status',
                'lunch'
            ]);
    }

    public function test_index_method_returns_no_records_found()
    {
        $response = $this->getJson(route('lunch.index'));
        $response->assertStatus(Response::HTTP_NOT_FOUND) // HTTP status code 404
            ->assertJson([
                'status',
                'lunch'
            ]);
    }


    public function test_show_method_returns_lunch_record_by_id()
    {
        $data = Lunch::factory()->create();
        $response = $this->getJson(route('lunch.show', ['Id' => $data->id]));

        $response->assertStatus(Response::HTTP_OK) 
            ->assertJson([
                'message' => 'Lunch request created successfully',
                'statusCode' => 200,
                'data' => $data->only([
                    'receiver_id',
                    'sender_id',
                    'quantity',
                    'redeemed',
                    'note',
                    'created_at',
                    'id',
                ]),
            ]);
    }

    public function test_show_method_returns_by_id_not_found(): void
    {
         $response = $this->getJson(route('lunch.show', ['Id' => 123456789])); 
        $response->assertStatus(404) // HTTP status code 404
            ->assertJson([
                'message' => 'Lunch request not found',
                'statusCode' => 404,
            ]);
    }

 

    public function test_store_method_creates_lunch_request(): void
    {
        $lunchSender = User::factory()->create();
       $organization = Organization::factory()->create();

        if ($lunchSender->is_admin) {
            OrganizationLunchWallet::factory()->create([
                'org_id' => $organization->id,
                'balance' => 1000,
            ]);
        } else {
            $lunchSender->update(['lunch_credit_balance' => 1000]); 
        }

        $lunchRequestData = [
            'receivers' => [2, 3],
            'quantity' => 1, 
            'note' => 'For your dedication we say thank you', 
        ];

        $response = $this->actingAs($lunchSender)
            ->postJson(route('lunch.store'), $lunchRequestData);

        $response->assertStatus(Response::HTTP_CREATED) 
            ->assertJson([
                'message' => 'Lunch request created successfully',
                'statusCode' => 201,
            ]);

        $this->assertDatabaseHas('lunches', [
            'org_id' => $organization->id, 
            'sender_id' => $lunchSender->id,
        ]);
    }


}
