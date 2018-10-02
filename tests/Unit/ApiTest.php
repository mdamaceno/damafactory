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

        factory(\App\Dbs::class)->create([
            'label' => 'abc',
            'database' => 'abc',
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

    public function testGet404WithoutToken()
    {
        $response = $this->json('GET', '/api/v1/abc');
        $response->assertStatus(400);
    }
}
