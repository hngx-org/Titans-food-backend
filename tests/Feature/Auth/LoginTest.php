<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    
    public function testSuccessfulLogin()
    {
        $user= User::factory()->create();

        $response = $this->postJson(route('user.signin'),[
            'email' => $user->email,
            'password' => 'password'
        ])
        ->assertOk()
        ->assertJsonStructure([
            "message",
            "statusCode",
            "data" => [
                "access_token",
                "email",
                "id",
                "isAdmin",
                "org_id"
            ]
        ]);

        $this->assertArrayHasKey('access_token',$response->json('data'));
    }

    public function testRequiredFieldsForLogin()
    {
        $this->postJson(route('user.signin'), [])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonStructure([
            "message",
            "statusCode"
        ]);
    }
    public function testIncorrectLoginCredentials()
    {
        $user= User::factory()->create();

        $this->postJson(route('user.signin'), [
            'email' => $user->email,
            'password' => 'passwordtest'
        ])
        ->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJson([
            "status_code" => Response::HTTP_UNAUTHORIZED,
            "status" => 'error',
            "message" => 'Authentication failed',
        ]);
    }

}
