<?php

namespace App\Video\Infrastructure\ServiceProvider;

use App\Video\Application\UseCase\DownloadUserVideoUseCase;
use App\Video\Application\UseCase\ListUserVideosUseCase;
use App\Video\Application\UseCase\UploadUserVideoUseCase;
use App\Video\Domain\Repository\VideoRepositoryInterface;
use App\Video\Domain\Repository\VideoUserRepositoryInterface;
use App\Video\Infrastructure\Contracts\VideoUserAuthGuardInterface;
use App\Video\Infrastructure\Guard\VideoUserAuthGuard;
use App\Video\Infrastructure\Repository\QueryBuilderVideoRepository;
use App\Video\Infrastructure\Repository\QueryBuilderVideoUserRepository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class VideoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            VideoRepositoryInterface::class,
            QueryBuilderVideoRepository::class,
        );

        $this->app->bind(
            VideoUserRepositoryInterface::class,
            QueryBuilderVideoUserRepository::class,
        );

        $this->app->singleton(
            VideoUserAuthGuardInterface::class,
            VideoUserAuthGuard::class,
        );

        $this->app->when(UploadUserVideoUseCase::class)
            ->needs(Filesystem::class)
            ->give(static fn () => Storage::disk('videos'));

        $this->app->when(ListUserVideosUseCase::class)
            ->needs(Filesystem::class)
            ->give(static fn () => Storage::disk('videos'));

        $this->app->when(DownloadUserVideoUseCase::class)
            ->needs(Filesystem::class)
            ->give(static fn () => Storage::disk('videos'));
    }
}
