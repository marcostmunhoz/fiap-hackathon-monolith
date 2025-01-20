<?php

namespace App\Video\Interface\Controller;

use App\Shared\Interface\Response\JsonResponse;
use App\Video\Application\DTO\ListUserVideosInput;
use App\Video\Application\UseCase\ListUserVideosUseCase;
use App\Video\Domain\Entity\VideoUserEntity;

class ListUserVideosController
{
    public function __invoke(
        VideoUserEntity $user,
        ListUserVideosInput $input,
        ListUserVideosUseCase $useCase
    ): JsonResponse {
        $output = $useCase->execute($user, $input);

        return JsonResponse::ok($output);
    }
}