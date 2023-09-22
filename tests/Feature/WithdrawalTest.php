<?php

namespace Tests\Feature;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response;

class WithdrawalTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_store_withdrawal_request_returns_user_details_successful()
    {     

        $user = User::factory()->create();
        $response = $this->actingAs($user) 
            ->postJson('api/withdrawal/request', [
                'amount' => 10000,
            ]);

        $response->assertStatus(Response::HTTP_OK)
             ->assertJsonStructure(
                 [
                         'message',
                         'statusCode',
                         'data' => [
                             'withdrawal_id',
                             'user_id',
                             'status',
                             'amount',
                             'created_at'
                         ]
            ]);
    }


    public function test_withdrawal_request_returns_user_details_successful()
    {     
        $user = User::factory()->create();
        $withdrawal = Withdrawal::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user) 
            ->getJson('api/withdrawal/request');
         $response->assertStatus(Response::HTTP_OK)
             ->assertJsonStructure(
                 [                 
                         'message',
                         'status',
                         'statusCode',
                         'data' => [
                             'withdrawals'
                         ]
            ]);
    }



}
