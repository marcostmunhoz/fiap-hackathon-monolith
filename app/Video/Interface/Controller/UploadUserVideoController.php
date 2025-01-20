<?php

namespace App\Video\Interface\Controller;

use App\Shared\Interface\Response\JsonResponse;
use App\Video\Application\DTO\UploadUserVideoInput;
use App\Video\Application\UseCase\UploadUserVideoUseCase;
use App\Video\Domain\Entity\VideoUserEntity;

class UploadUserVideoController
{
    public function __invoke(
        VideoUserEntity $user,
        UploadUserVideoInput $input,
        UploadUserVideoUseCase $useCase
    ): JsonResponse {
        $useCase->execute($user, $input);

        return JsonResponse::created();
    }
}