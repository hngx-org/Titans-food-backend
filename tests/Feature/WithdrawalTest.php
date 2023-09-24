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
        $userToken = $this->getUserToken();
        $response = $this->postJson(route('withdrawal.store'), [
                'amount' => 10000,
            ], ['Authorization' => 'Bearer '. $userToken]);

        $response->assertStatus(Response::HTTP_OK)
             ->assertJsonStructure(
                 [
                         'message',
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
        $userToken = $this->getUserToken();
        $withdrawal = Withdrawal::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson(route('withdrawal.index'),['Authorization' => 'Bearer '. $userToken]);
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
