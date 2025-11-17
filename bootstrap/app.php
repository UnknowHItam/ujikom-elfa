<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'profile.complete' => \App\Http\Middleware\EnsureProfileComplete::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, $request) {
            // Return JSON for API routes that expect it
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'error' => env('APP_DEBUG') ? [
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => explode("\n", $e->getTraceAsString())
                    ] : null
                ], 500);
            }
        });
    })->create();
