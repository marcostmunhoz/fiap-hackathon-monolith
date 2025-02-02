<?php

namespace App\Video\Application\UseCase;

use App\Shared\Domain\Data\FileStream;
use App\Shared\Domain\Exception\UnauthorizedException;
use App\Shared\Domain\ValueObject\EntityId;
use App\Video\Application\DTO\DownloadUserVideoInput;
use App\Video\Application\DTO\DownloadUserVideoOutput;
use App\Video\Domain\Entity\VideoUserEntity;
use App\Video\Domain\Repository\VideoRepositoryInterface;
use Illuminate\Contracts\Filesystem\Filesystem;

readonly class DownloadUserVideoUseCase
{
    public function __construct(
        private VideoRepositoryInterface $videoRepository,
        private Filesystem $filesystem
    ) {
    }

    public function execute(VideoUserEntity $user, DownloadUserVideoInput $input): DownloadUserVideoOutput
    {
        $video = $this->videoRepository->find(new EntityId($input->id));

        if (!$video->userId->equals($user->id)) {
            throw new UnauthorizedException();
        }

        $stream = $this->filesystem->readStream($video->outputFilename);
        // @phpstan-ignore method.notFound
        $mimeType = $this->filesystem->mimeType($video->outputFilename);

        return new DownloadUserVideoOutput(
            new FileStream(
                $stream,
                $video->outputFilename,
                $mimeType
            )
        );
    }
}