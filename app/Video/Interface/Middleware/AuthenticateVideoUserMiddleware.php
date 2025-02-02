<?php

namespace App\Video\Interface\Middleware;

use App\Video\Infrastructure\Contracts\VideoUserAuthGuardInterface;
use Illuminate\Http\Request;

readonly class AuthenticateVideoUserMiddleware
{
    public function __construct(
        private VideoUserAuthGuardInterface $authGuard
    ) {
    }

    /**
     * @param callable(Request): mixed $next
     */
    public function handle(Request $request, callable $next): mixed
    {
        $this->authGuard->authenticate($request->bearerToken());

        return $next($request);
    }
}