<?php

namespace Tests\Unit\Shared\Infrastructure\Service;

use App\Shared\Infrastructure\Service\UuidV4EntityIdGenerator;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidInterface;

beforeEach(function () {
    $this->uuidFactoryMock = mock(UuidFactory::class);
    $this->sut = new UuidV4EntityIdGenerator($this->uuidFactoryMock);
});

test('generate returns a valid uuid v4 based entity id', function () {
    // Given
    $expectedUuid = fake()->uuid();
    $dummyUuidV4 = mock(UuidInterface::class);
    $dummyUuidV4
        ->allows('__toString')
        ->andReturn($expectedUuid);
    $this->uuidFactoryMock
        ->allows('uuid4')
        ->andReturn($dummyUuidV4);

    // When
    $entityId = $this->sut->generate();

    // Then
    expect((string) $entityId)->toEqual($expectedUuid);
    $this->uuidFactoryMock
        ->shouldHaveReceived('uuid4')
        ->once();
    $dummyUuidV4
        ->shouldHaveReceived('__toString')
        ->once();
});