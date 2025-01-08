<?php

use App\Shared\Infrastructure\Service\GlobalExceptionRenderer;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: '',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('web', [SubstituteBindings::class]);
        $middleware->group('api', [
            SubstituteBindings::class,
            'throttle:default',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $renderer = resolve(GlobalExceptionRenderer::class);

        $exceptions->dontReport($renderer->renders());
        $exceptions->render($renderer);
    })->create();
