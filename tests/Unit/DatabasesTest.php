<?php

namespace Tests\Unit;

use JWTAuth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatabasesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testGetDatabaseInfoWithMiddleware()
    {
        $user = factory(\App\User::class)->create(['role' => 'master']);
        $token = JWTAuth::fromUser($user);

        factory(\App\Dbs::class)->create([
            'label' => 'abc',
            'database' => 'abc',
        ]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('GET', '/api/abc');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'label',
                'driver',
                'host',
                'port',
                'database',
                'charset',
            ],
        ]);
    }

    public function testGetDatabaseInfoNotBeingMaster()
    {
        $user = factory(\App\User::class)->create();
        $token = JWTAuth::fromUser($user);

        factory(\App\Dbs::class)->create([
            'label' => 'abc',
            'database' => 'abc',
        ]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('GET', '/api/abc');

        $response->assertStatus(404);
    }

    public function testGetDatabaseInfoWithoutMiddleware()
    {
        $this->withoutMiddleware();

        factory(\App\Dbs::class)->create([
            'label' => 'abc',
            'database' => 'abc',
        ]);

        $response = $this->json('GET', '/api/abc');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'label',
                'driver',
                'host',
                'port',
                'database',
                'charset',
            ],
        ]);
    }

    public function testGet400WithoutToken()
    {
        $response = $this->json('GET', '/api/abc');
        $response->assertStatus(400);
    }
}
