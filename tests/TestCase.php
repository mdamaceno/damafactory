<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Faker\Factory as Faker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $faker;

    public function setUp()
    {
        parent::setUp();

        $this->faker = Faker::create('pt_BR');
        //    $this->faker->addProvider(new CustomFaker($this->faker));
    }
}
