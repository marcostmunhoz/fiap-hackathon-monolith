<?php

namespace Tests\Unit\Video\Application\UseCase;

use App\Video\Application\DTO\ListUserVideosInput;
use App\Video\Application\UseCase\ListUserVideosUseCase;
use App\Video\Domain\Enum\VideoStatus;
use App\Video\Domain\Repository\VideoRepositoryInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use function Tests\Helpers\Video\getVideoEntity;
use function Tests\Helpers\Video\getVideoUserEntity;

beforeEach(function () {
    $this->videoRepositoryMock = mock(VideoRepositoryInterface::class);
    $this->filesystemMock = mock(Filesystem::class);
    $this->sut = new ListUserVideosUseCase(
        $this->videoRepositoryMock,
        $this->filesystemMock
    );

    $this->user = getVideoUserEntity();
});

it('returns an output DTO containing all of the fetched videos', function () {
    // Given
    $input = new ListUserVideosInput();
    $pendingVideo = getVideoEntity(
        status: VideoStatus::PENDING,
        userId: $this->user->id
    );
    $previouslyPendingVideo = getVideoEntity(
        status: VideoStatus::PENDING,
        userId: $this->user->id,
    );
    $previouslyProcessedVideo = getVideoEntity(
        status: VideoStatus::PROCESSED,
        userId: $this->user->id
    );
    $this->videoRepositoryMock
        ->expects()
        ->list($this->user->id, $input->page, $input->perPage)
        ->andReturn([$pendingVideo, $previouslyPendingVideo, $previouslyProcessedVideo]);
    $this->filesystemMock
        ->expects()
        ->exists("{$pendingVideo->id}-frames.zip")
        ->andReturnFalse();
    $this->filesystemMock
        ->expects()
        ->exists("{$previouslyPendingVideo->id}-frames.zip")
        ->andReturnTrue();
    $this->videoRepositoryMock
        ->expects()
        ->save($previouslyPendingVideo);

    // When
    $output = $this->sut->execute($this->user, $input);

    // Then
    expect($output->videos)->toHaveCount(3)
        ->toEqual([$pendingVideo, $previouslyPendingVideo, $previouslyProcessedVideo]);
});