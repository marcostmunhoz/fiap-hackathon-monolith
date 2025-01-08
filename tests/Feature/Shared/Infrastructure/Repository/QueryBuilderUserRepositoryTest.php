<?php

namespace Tests\Feature\Shared\Infrastructure\Repository;

use App\User\Infrastructure\Repository\QueryBuilderUserRepository;
use function Tests\Helpers\User\createUserEntity;
use function Tests\Helpers\User\findUserEntity;
use function Tests\Helpers\User\getUserEntity;

beforeEach(function () {
    $this->sut = new QueryBuilderUserRepository(getConnection());
});

test('save creates new user', function () {
    // Given
    $entity = getUserEntity();

    // When
    $this->sut->save($entity);

    // Then
    $databaseEntity = findUserEntity($entity->id);
    expect($databaseEntity)->not()->toBeNull()
        ->id->equals($entity->id)->toBeTrue()
        ->name->equals($entity->name)->toBeTrue()
        ->email->equals($entity->email)->toBeTrue()
        ->hashedPassword->toEqual($entity->hashedPassword)
        ->createdAt->equals($entity->createdAt)->toBeTrue()
        ->updatedAt->not()->equals($entity->updatedAt)->toBeTrue();
});

test('save updates existing user', function () {
    // Given
    $entity = createUserEntity();
    $updatedEntity = getUserEntity(id: $entity->id);

    // When
    $this->sut->save($updatedEntity);

    // Then
    $databaseEntity = findUserEntity($entity->id);
    expect($databaseEntity)->name->equals($updatedEntity->name)->toBeTrue()
        ->email->equals($updatedEntity->email)->toBeTrue()
        ->hashedPassword->toEqual($updatedEntity->hashedPassword)
        ->createdAt->equals($updatedEntity->createdAt)->toBeTrue()
        ->updatedAt->not()->equals($updatedEntity->updatedAt)->toBeTrue();
});

test('findByEmail returns null when user does not exist', function () {
    // Given
    $email = getUserEntity()->email;

    // When
    $entity = $this->sut->findByEmail($email);

    // Then
    expect($entity)->toBeNull();
});

test('findByEmail returns user when it exists', function () {
    // Given
    $entity = createUserEntity();
    $email = $entity->email;

    // When
    $result = $this->sut->findByEmail($email);

    // Then
    expect($result)->not()->toBeNull()
        ->id->equals($entity->id)->toBeTrue()
        ->name->equals($entity->name)->toBeTrue()
        ->email->equals($entity->email)->toBeTrue()
        ->hashedPassword->toEqual($entity->hashedPassword)
        ->createdAt->equals($entity->createdAt)->toBeTrue()
        ->updatedAt->equals($entity->updatedAt)->toBeTrue();
});