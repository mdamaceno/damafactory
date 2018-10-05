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
        ])->json('GET', '/api/databases/abc');

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
        ])->json('GET', '/api/databases/abc');

        $response->assertStatus(404);
    }

    public function testGetDatabaseInfoWithoutMiddleware()
    {
        $this->withoutMiddleware();

        factory(\App\Dbs::class)->create([
            'label' => 'abc',
            'database' => 'abc',
        ]);

        $response = $this->json('GET', '/api/databases/abc');
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
        $response = $this->json('GET', '/api/databases/abc');
        $response->assertStatus(400);
    }

    public function testInsertDatabaseWithoutMiddleware()
    {
        $this->withoutMiddleware();

        $db = factory(\App\Dbs::class)->make([
            'label' => 'abc',
            'database' => 'abc',
        ]);

        $response = $this->json('POST', '/api/databases', $db->toArray());
        $response->assertStatus(202);

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

    public function invalidInsertDatabaseData()
    {
        $faker = \Faker\Factory::create('pt_BR');
        $faker->addProvider(new \App\Support\CustomFaker($faker));

        return [
            [['label' => null]],
            [['label' => $faker->string(256)]],
            [['label' => $faker->string(2)]],
            [['driver' => null]],
            [['driver' => $faker->string(10)]],
            [['host' => null]],
            [['host' => $faker->string(10)]],
            [['port' => null]],
            [['port' => $faker->string(10)]],
            [['database' => null]],
            [['database' => $faker->string(256)]],
            [['username' => null]],
            [['username' => $faker->string(256)]],
            [['password' => null]],
            [['charset' => null]],
        ];
    }

    /**
     * @dataProvider invalidInsertDatabaseData
     */
    public function testValidationInsertDatabaseWithoutMiddleware($inputData)
    {
        $this->withoutMiddleware();

        $db = factory(\App\Dbs::class)->make($inputData);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', '/api/databases', $db->toArray());

        $response->assertStatus(422);
    }
}
