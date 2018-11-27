<?php

use Faker\Generator as Faker;

$factory->define(App\DBRole::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'active' => true,
        'http_permission' => $faker->randomElement(['get', 'post', 'patch', 'delete']),
    ];
});
