<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_a_user_can_register(): void
    {
        $this->postJson(route('user.signup'),[
            'first_name' => "Kayode",
            'last_name' => "Kayode",
            'email' => 'kayode@kayodeadegbuyi.com.ng',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertCreated();

        $this->assertDatabaseHas('users',['first_name' => 'Kayode']);
    }
}
