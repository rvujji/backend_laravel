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

        $middleware->api(prepend: [
            \App\Http\Middleware\AddRequestLogContext::class,
        ]);
        
         $middleware->alias([

            'role' =>
            \Spatie\Permission\Middleware\RoleMiddleware::class,

            'permission' =>
            \Spatie\Permission\Middleware\PermissionMiddleware::class,

            'role_or_permission' =>
            \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (AuthenticationException $e, $request) {
            Log::warning('Unauthenticated request', [
                'guard' => $e->guards(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'payload' => $request->except(['password', 'password_confirmation']),
                'user_id' => optional($request->user())->id,
            ]);
        });

        $exceptions->report(function (AuthorizationException $e, $request) {
            Log::warning('Authorization denied', [
                'message' => $e->getMessage(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'payload' => $request->except(['password', 'password_confirmation']),
                'user_id' => optional($request->user())->id,
            ]);
        });

        $exceptions->report(function (UnauthorizedException $e, $request) {
            Log::warning('Spatie role/permission denied', [
                'message' => $e->getMessage(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'payload' => $request->except(['password', 'password_confirmation']),
                'user_id' => optional($request->user())->id,
            ]);
        });

        $exceptions->report(function (HttpException $e, $request) {
            if (in_array($e->getStatusCode(), [401, 403])) {
                Log::warning('HTTP auth-related exception', [
                    'status' => $e->getStatusCode(),
                    'message' => $e->getMessage(),
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'user_id' => optional($request->user())->id,
                ]);
            }
        });
    })->create();
