<?php

namespace App\Video\Application\UseCase;

use App\Video\Application\DTO\ListUserVideosInput;
use App\Video\Application\DTO\ListUserVideosOutput;
use App\Video\Domain\Entity\VideoUserEntity;
use App\Video\Domain\Repository\VideoRepositoryInterface;

readonly class ListUserVideosUseCase
{
    public function __construct(
        private VideoRepositoryInterface $videoRepository
    ) {
    }

    public function execute(VideoUserEntity $user, ListUserVideosInput $input): ListUserVideosOutput
    {
        $videos = $this->videoRepository->list($user->id, $input->page, $input->perPage);

        return new ListUserVideosOutput($videos);
    }
}