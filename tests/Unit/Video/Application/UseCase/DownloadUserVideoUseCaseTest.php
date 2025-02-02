<?php

namespace Tests\Unit\Video\Application\UseCase;

use App\Shared\Domain\Exception\UnauthorizedException;
use App\Shared\Domain\ValueObject\EntityId;
use App\Video\Application\DTO\DownloadUserVideoInput;
use App\Video\Application\UseCase\DownloadUserVideoUseCase;
use App\Video\Domain\Repository\VideoRepositoryInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Mockery;
use function Tests\Helpers\Video\getVideoEntity;
use function Tests\Helpers\Video\getVideoUserEntity;

beforeEach(function () {
    $this->videoRepositoryMock = mock(VideoRepositoryInterface::class);
    $this->filesystemMock = mock(Filesystem::class);
    $this->sut = new DownloadUserVideoUseCase(
        $this->videoRepositoryMock,
        $this->filesystemMock
    );

    $this->user = getVideoUserEntity();
});

it('throws when the video does not belong to the given user', function () {
    // Given
    $video = getVideoEntity();
    $input = new DownloadUserVideoInput($video->id);
    $this->videoRepositoryMock
        ->expects()
        ->find(
            Mockery::on(static fn (EntityId $id) => $id->equals($video->id))
        )
        ->andReturn($video);

    // When
    $this->sut->execute($this->user, $input);
})->throws(UnauthorizedException::class);

it('returns an output DTO containing the video file stream', function () {
    // Given
    $file = UploadedFile::fake()->create('output.zip', 1024 * 1024 * 100, 'application/zip');
    $stream = fopen($file->getRealPath(), 'rb');
    $video = getVideoEntity(outputFilename: 'output.zip', userId: $this->user->id);
    $input = new DownloadUserVideoInput($video->id);
    $this->videoRepositoryMock
        ->expects()
        ->find(
            Mockery::on(static fn (EntityId $id) => $id->equals($video->id))
        )
        ->andReturn($video);
    $this->filesystemMock
        ->expects()
        ->readStream($video->outputFilename)
        ->andReturn($stream);
    $this->filesystemMock
        ->expects()
        ->mimeType($video->outputFilename)
        ->andReturn('application/zip');

    // When
    $output = $this->sut->execute($this->user, $input);

    // Then
    expect($output->stream)->stream->toBe($stream)
        ->mimeType->toBe('application/zip');
});