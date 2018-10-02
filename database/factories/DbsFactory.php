<?php

use Faker\Generator as Faker;

$factory->define(App\Dbs::class, function (Faker $faker) {
    $name = $faker->domainWord;

    return [
        'label' => $name,
        'driver' => 'firebird',
        'host' => 'localhost',
        'port' => 8000,
        'database' => $name,
        'username' => $faker->userName,
        'password' => bcrypt($faker->password),
        'charset' => 'utf8',
    ];
});
