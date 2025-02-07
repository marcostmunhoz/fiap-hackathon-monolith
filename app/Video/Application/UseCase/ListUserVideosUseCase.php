<?php

namespace App\Video\Application\UseCase;

use App\Video\Application\DTO\ListUserVideosInput;
use App\Video\Application\DTO\ListUserVideosOutput;
use App\Video\Domain\Entity\VideoEntity;
use App\Video\Domain\Entity\VideoUserEntity;
use App\Video\Domain\Repository\VideoRepositoryInterface;
use Illuminate\Contracts\Filesystem\Filesystem;

readonly class ListUserVideosUseCase
{
    public function __construct(
        private VideoRepositoryInterface $videoRepository,
        private Filesystem $filesystem
    ) {
    }

    public function execute(VideoUserEntity $user, ListUserVideosInput $input): ListUserVideosOutput
    {
        $videos = $this->videoRepository->list($user->id, $input->page, $input->perPage);

        $this->updateVideoStatus($videos);

        return new ListUserVideosOutput($videos);
    }

    /**
     * @param VideoEntity[] $videos
     */
    private function updateVideoStatus(array $videos): void
    {
        foreach ($videos as $video) {
            if ($video->isProcessed()) {
                continue;
            }

            $outputFilename = "{$video->id}-frames.zip";

            if (!$this->filesystem->exists($outputFilename)) {
                continue;
            }

            $video->markAsProcessed($outputFilename);
            $this->videoRepository->save($video);
        }
    }
}
