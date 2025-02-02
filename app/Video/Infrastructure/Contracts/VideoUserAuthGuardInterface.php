<?php

namespace App\Video\Infrastructure\Contracts;

use App\Shared\Infrastructure\Contracts\JwtAuthGuardInterface;
use App\Video\Domain\Entity\VideoUserEntity;

/**
 * @template-extends JwtAuthGuardInterface<VideoUserEntity>
 */
interface VideoUserAuthGuardInterface extends JwtAuthGuardInterface
{
    public function resolve(): VideoUserEntity;
}