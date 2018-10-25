<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testUserLogin()
    {
        $user = factory(\App\User::class)->create([
            'email' => $this->faker->unique()->safeEmail(),
            'role' => 'master',
        ]);

        $login = [
            'email' => $user['email'],
            'password' => 'secret',
        ];

        $encoded = base64_encode($login['email'] . ':' . $login['password']);

        $response = $this->withHeaders([
            'Authorization' => "Basic $encoded",
        ])->json('POST', '/api/user/login');

        $response->assertStatus(200);

        $token = $response->getData()->token;

        $this->assertDatabaseHas('auth_tokens', [
            'user_id' => $user['id'],
            'token' => $token,
        ]);
    }
}
