<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testUserRegistration()
    {
        $user = [
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password,
            'name' => 'username',
            'role' => 'master',
        ];

        $response = $this->json('POST', '/api/user/register', $user);
        $response->assertStatus(201);

        $token = $response->getData()->data->token;

        $this->assertDatabaseHas('auth_tokens', [
            'token' => $token,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $user['email'],
            'name' => $user['name'],
        ]);
    }
}
