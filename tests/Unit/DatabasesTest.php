<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatabasesTest extends TestCase
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
