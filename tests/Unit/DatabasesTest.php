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

        $db = factory(\App\Dbs::class)->create([
            'label' => 'abc',
            'database' => 'abc',
        ]);

        $dbToken = $db->token;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
            'Database-Token' => $dbToken,
        ])
        ->json('GET', '/api/databases/abc');

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

        $response->assertStatus(403);
    }

    public function testGetDatabaseInfoWithoutMiddleware()
    {
        $this->withoutMiddleware();

        $user = factory(\App\User::class)->create();

        $db = factory(\App\Dbs::class)->create([
            'label' => 'abc',
            'database' => 'abc',
        ]);

        factory(\App\DBToken::class)->create([
            'user_id' => $user->id,
            'dbs_id' => $db->id,
            'http_permission' => 'get',
        ]);

        $dbToken = $db->token;

        $response = $this->withHeaders([
            'Database-Token' => $dbToken,
        ])->actingAs($user)
          ->json('GET', '/api/databases/abc');

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

        $user = factory(\App\User::class)->create(['role' => 'master']);

        $db = factory(\App\Dbs::class)->make([
            'label' => 'abc',
            'database' => 'abc',
        ]);

        $response = $this
            ->actingAs($user)
            ->json('POST', '/api/databases', $db->toArray());

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'label',
                'driver',
                'host',
                'port',
                'database',
                'charset',
                'token',
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

        $user = factory(\App\User::class)->create(['role' => 'master']);

        $db = factory(\App\Dbs::class)->make($inputData);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])
        ->actingAs($user)
        ->json('POST', '/api/databases', $db->toArray());

        $response->assertStatus(422);
    }

    public function testUpdateDatabaseWithoutMiddleware()
    {
        $this->withoutMiddleware();

        $user = factory(\App\User::class)->create(['role' => 'master']);

        $db = factory(\App\Dbs::class)->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Database-Token' => $db->token,
        ])
        ->actingAs($user)
        ->json('PATCH', '/api/databases/' . $db->label, ['database' => 'kkk']);

        $response->assertStatus(202);

        $this->assertEquals('kkk', $response->getData()->data->database);

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

    /**
     * @dataProvider invalidInsertDatabaseData
     */
    public function testValidationUpdateDatabaseWithoutMiddleware($inputData)
    {
        $this->withoutMiddleware();

        $user = factory(\App\User::class)->create(['role' => 'master']);

        $db = factory(\App\Dbs::class)->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])
        ->actingAs($user)
        ->json('PATCH', '/api/databases/' . $db->label, $inputData);

        $response->assertStatus(422);
    }

    public function testDeleteDatabaseWithoutMiddleware()
    {
        $this->withoutMiddleware();

        $user = factory(\App\User::class)->create(['role' => 'master']);

        $db = factory(\App\Dbs::class)->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Database-Token' => $db->token,
        ])
        ->actingAs($user)
        ->json('DELETE', '/api/databases/' . $db->label);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('dbs', $db->toArray());
    }

    public function testTryDeleteNotFound()
    {
        $this->withoutMiddleware();

        $user = factory(\App\User::class)->create(['role' => 'master']);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])
        ->actingAs($user)
        ->json('DELETE', '/api/databases/' . 'abc');

        $response->assertStatus(404);
    }

    public function testNotGetInvalidPermissionWithoutMiddleware()
    {
        $this->withoutMiddleware();

        $db = factory(\App\Dbs::class)->create();
        $user = factory(\App\User::class)->create(['role' => 'db']);

        $dbToken = factory(\App\DBToken::class)->create([
            'user_id' => $user->id,
            'dbs_id' => $db->id,
            'http_permission' => 'post',
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Database-Token' => $db->token,
        ])
        ->actingAs($user)
        ->json('GET', '/api/databases/' . $db->label);

        $response->assertStatus(403);
    }
}
