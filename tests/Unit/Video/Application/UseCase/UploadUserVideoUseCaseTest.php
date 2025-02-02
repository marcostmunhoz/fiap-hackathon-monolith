<?php

namespace Tests\Unit\Video\Application\UseCase;

use App\Shared\Domain\Service\EntityIdGeneratorInterface;
use App\Shared\Domain\Service\MessageProducerInterface;
use App\Shared\Domain\ValueObject\EntityId;
use App\Video\Application\DTO\UploadUserVideoInput;
use App\Video\Application\UseCase\UploadUserVideoUseCase;
use App\Video\Domain\Repository\VideoRepositoryInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Mockery;
use function Tests\Helpers\Video\getVideoUserEntity;

beforeEach(function () {
    $this->entityIdGeneratorStub = $this->createStub(EntityIdGeneratorInterface::class);
    $this->videoRepositorySpy = spy(VideoRepositoryInterface::class);
    $this->filesystemSpy = spy(Filesystem::class);
    $this->messageProducerSpy = spy(MessageProducerInterface::class);
    $this->sut = new UploadUserVideoUseCase(
        $this->entityIdGeneratorStub,
        $this->filesystemSpy,
        $this->videoRepositorySpy,
        $this->messageProducerSpy
    );

    $this->user = getVideoUserEntity();
    $this->file = UploadedFile::fake()->create('video.mp4', 1024 * 1024 * 100, 'video/mp4');
});

it('correctly uploads video to filesystem and produces a message', function () {
    // Given
    $entityId = new EntityId(fake()->uuid());
    $this->entityIdGeneratorStub
        ->method('generate')
        ->willReturn($entityId);
    $input = new UploadUserVideoInput($this->file);

    // When
    $output = $this->sut->execute($this->user, $input);

    // Then
    expect($output->id)->equals($entityId)->toBeTrue();
    $this->filesystemSpy
        ->shouldHaveReceived('putFileAs')
        ->once()
        ->with('/', $this->file, "{$entityId}.mp4");
    $this->videoRepositorySpy
        ->shouldHaveReceived('save')
        ->once()
        ->with(
            Mockery::on(
                fn ($video) => $video->userId->equals($this->user->id) &&
                    $video->filename === "{$entityId}.mp4"
            )
        );
    $this->messageProducerSpy
        ->shouldHaveReceived('send')
        ->once()
        ->with(
            Mockery::on(
                fn ($message) => $message->filename === "{$entityId}.mp4" &&
                    $message->userName === $this->user->name->firstName &&
                    $message->userEmail === $this->user->email->value
            )
        );
});