<?php

namespace Tests\Feature\Video\Infrastructure\Repository;

use App\Video\Infrastructure\Repository\QueryBuilderVideoUserRepository;
use function Tests\Helpers\Video\createVideoUserEntity;

beforeEach(function () {
    $this->sut = new QueryBuilderVideoUserRepository(getConnection());
});

test('find returns the video user entity', function () {
    // Given
    $entity = createVideoUserEntity();

    // When
    $result = $this->sut->find($entity->id);

    // Then
    expect($result)->not()->toBeNull()
        ->id->equals($entity->id)->toBeTrue()
        ->name->equals($entity->name)->toBeTrue()
        ->email->equals($entity->email)->toBeTrue()
        ->createdAt->equals($entity->createdAt)->toBeTrue()
        ->updatedAt->equals($entity->updatedAt)->toBeTrue();
});
