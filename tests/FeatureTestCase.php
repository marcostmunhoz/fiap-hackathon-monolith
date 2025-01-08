<?php

namespace Tests;

use App\Shared\Domain\Service\JwtGeneratorInterface;
use App\User\Domain\Service\PasswordHasherInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Dummies\Shared\DummyJwtGenerator;
use Tests\Dummies\User\DummyPasswordHasher;

class FeatureTestCase extends TestCase
{
    use DatabaseMigrations;

    public string $dummyJwtGeneratorUuid;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dummyJwtGeneratorUuid = fake()->uuid();
        $this->instance(
            JwtGeneratorInterface::class,
            new DummyJwtGenerator($this->dummyJwtGeneratorUuid)
        );
        $this->instance(
            PasswordHasherInterface::class,
            new DummyPasswordHasher()
        );
    }
}
