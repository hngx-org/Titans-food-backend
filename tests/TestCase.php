<?php

namespace Tests;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    public function setUp(): void
    {
        parent::setUp();
        //$this->withoutExceptionHandling();
    }

    public function authUser()
    {
        $user = $this->createUser();
        Sanctum::actingAs($user);
        return $user;
    }

    public function authAdmin()
    {
        $admin = $this->createAdmin();
        Sanctum::actingAs($admin);
        return $admin;
    }

    public function createUser($args = [])
    {
        return User::factory()->create($args);
    }

    public function createAdmin($args= ['is_admin' => true])
    {
        return User::factory()->create($args);
    }

    public function getAdminToken()
    {
        $user = $this->authAdmin();
        $response = $this->postJson(route('user.signin'),[
            'email' => $user->email,
            'password' => 'password'
        ]);
        $adminToken = $response->json('data')['access_token'];

        return $adminToken;
    }

    public function getUserToken()
    {
        $user = $this->authUser();
        $response = $this->postJson(route('user.signin'),[
            'email' => $user->email,
            'password' => 'password'
        ]);
        $userToken = $response->json('data')['access_token'];

        return $userToken;
    }
}
