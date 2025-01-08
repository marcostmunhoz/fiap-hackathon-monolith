<?php

namespace Tests;

use Faker\Generator;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use WithFaker;

    public function getFaker(): Generator
    {
        return $this->faker;
    }

    public function getConnection($connection = null, $table = null): ConnectionInterface
    {
        return parent::getConnection($connection, $table);
    }
}
