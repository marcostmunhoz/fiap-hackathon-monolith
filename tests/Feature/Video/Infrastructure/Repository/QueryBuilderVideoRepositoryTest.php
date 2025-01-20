<?php

namespace Tests\Feature\Video\Infrastructure\Repository;

use App\Video\Infrastructure\Repository\QueryBuilderVideoRepository;
use function Tests\Helpers\Video\createVideoEntity;
use function Tests\Helpers\Video\findVideoEntity;
use function Tests\Helpers\Video\getVideoEntity;

beforeEach(function () {
    $this->sut = new QueryBuilderVideoRepository(getConnection());
});

test('save creates new video', function () {
    // Given
    $entity = getVideoEntity();

    // When
    $this->sut->save($entity);

    // Then
    $databaseEntity = findVideoEntity($entity->id);
    expect($databaseEntity)->not()->toBeNull()
        ->id->equals($entity->id)->toBeTrue()
        ->filename->toEqual($entity->filename)
        ->outputFilename->toEqual($entity->outputFilename)
        ->userId->equals($entity->userId)->toBeTrue()
        ->status->toEqual($entity->status)
        ->createdAt->equals($entity->createdAt)->toBeTrue()
        ->updatedAt->not()->equals($entity->updatedAt)->toBeTrue();
});

test('save updates existing video', function () {
    // Given
    $entity = createVideoEntity();
    $updatedEntity = getVideoEntity(id: $entity->id);

    // When
    $this->sut->save($updatedEntity);

    // Then
    $databaseEntity = findVideoEntity($entity->id);
    expect($databaseEntity)->filename->toEqual($updatedEntity->filename)
        ->outputFilename->toEqual($updatedEntity->outputFilename)
        ->userId->equals($updatedEntity->userId)->toBeTrue()
        ->status->toEqual($updatedEntity->status)
        ->createdAt->equals($updatedEntity->createdAt)->toBeTrue()
        ->updatedAt->not()->equals($updatedEntity->updatedAt)->toBeTrue();
});