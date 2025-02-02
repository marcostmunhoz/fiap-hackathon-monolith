<?php

namespace App\Video\Application\UseCase;

use App\Shared\Domain\Service\EntityIdGeneratorInterface;
use App\Shared\Domain\Service\MessageProducerInterface;
use App\Video\Application\DTO\UploadUserVideoInput;
use App\Video\Application\DTO\UploadUserVideoOutput;
use App\Video\Domain\Data\VideoUploadedMessage;
use App\Video\Domain\Entity\VideoEntity;
use App\Video\Domain\Entity\VideoUserEntity;
use App\Video\Domain\Repository\VideoRepositoryInterface;
use Illuminate\Contracts\Filesystem\Filesystem;

readonly class UploadUserVideoUseCase
{
    public function __construct(
        private EntityIdGeneratorInterface $entityIdGenerator,
        private Filesystem $filesystem,
        private VideoRepositoryInterface $videoRepository,
        private MessageProducerInterface $messageProducer,
    ) {
    }

    public function execute(VideoUserEntity $user, UploadUserVideoInput $input): UploadUserVideoOutput
    {
        $id = $this->entityIdGenerator->generate();
        $extension = $input->file->getExtension() ?: 'mp4';
        $filename = "{$id}.{$extension}";
        $video = new VideoEntity(
            id: $id,
            filename: $filename,
            userId: $user->id
        );

        $this->filesystem->putFileAs('/', $input->file, $filename);

        $this->videoRepository->save($video);

        $this->messageProducer->send(
            new VideoUploadedMessage(
                $video->filename,
                $user->name->firstName,
                $user->email->value
            )
        );

        return new UploadUserVideoOutput($video->id);
    }
}