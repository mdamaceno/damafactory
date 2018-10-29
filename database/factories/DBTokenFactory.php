<?php

use Faker\Generator as Faker;

$factory->define(App\DBToken::class, function (Faker $faker) {
    return [
        'user_id' => factory(\App\User::class)->create()->id,
        'dbs_id' => factory(\App\Dbs::class)->create()->id,
        'http_permission' => $faker->randomElement(['get', 'post', 'patch', 'delete']),
    ];
});
