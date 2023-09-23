<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test for UserController.
     */
    public function testAUserSearchRecordFound()
    {

        $user= User::factory()->create();
        $response = $this->postJson(route('user.signin'),[
            'email' => $user->email,
            'password' => 'password'
        ]);

        $token = $response['data']['access_token'];
        

        $this->getJson(route('search.search', ['nameOrEmail' => $user->email]), ['Authorization' => "Bearer $token"])
        ->assertOk()
        ->assertExactJson([
            'message' => 'User found',
            'statusCode' => 200,
            'data' => [ [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email
             ] ]
        ]);
    }

    public function testAUserSearchRecordNotFound()
    {

        $user= User::factory()->create();
        $response = $this->postJson(route('user.signin'),[
            'email' => $user->email,
            'password' => 'password'
        ]);

        $token = $response['data']['access_token'];
        

        $this->getJson(route('search.search', ['nameOrEmail' => 'hellotesting@example.info']), ['Authorization' => "Bearer $token"])
        ->assertStatus(404)
        ->assertExactJson([
            'message' => 'No users found for the given name or email.',
        ]);
    }


    public function testAllUsersRetrievedSuccessfully()
    {
        $user= User::factory()->create();
        $response = $this->postJson(route('user.signin'),[
            'email' => $user->email,
            'password' => 'password'
        ]);

        $token = $response['data']['access_token'];
        

        $response = $this->getJson(route('user.index'), ['Authorization' => "Bearer $token"])
        ->assertOk()
        ->assertJsonStructure([
            "message",
            "statusCode",
            "data" => [
                "*" => [
                    "name",
                    "email",
                    "profile_picture",
                    "user_id"
                ]
            ]
        ]);
    }

}
