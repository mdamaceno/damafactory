<?php

use Faker\Generator as Faker;

$factory->define(App\Dbs::class, function (Faker $faker) {
    $name = $faker->domainWord;

    return [
        'label' => $name,
        'driver' => 'firebird',
        'host' => '127.0.0.1',
        'port' => 8000,
        'database' => $name,
        'username' => $faker->userName,
        'password' => bcrypt($faker->password),
        'charset' => 'utf8',
        'token' => bin2hex(openssl_random_pseudo_bytes(32)),
    ];
});
