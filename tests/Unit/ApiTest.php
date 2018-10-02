<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testGetDatabaseInfoWithoutMiddleware()
    {
        $this->withoutMiddleware();

        \DB::table('dbs')->insert([
            'label' => 'abc',
            'driver' => 'firebird',
            'host' => 'localhost',
            'port' => 8000,
            'database' => 'abc',
            'username' => 'user',
            'password' => 'pass',
            'charset' => 'utf8',
        ]);

        $response = $this->json('GET', '/api/v1/abc');
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

    public function testGetUserInfo()
    {
    }
}
