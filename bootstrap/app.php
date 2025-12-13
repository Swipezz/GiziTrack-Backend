<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        // kalau nanti pakai API, tinggal aktifkan
        // api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        /**
         * ✅ GLOBAL CORS (Laravel 11 way)
         * Berlaku untuk SEMUA route (web + api + ngrok)
         */
        $middleware->append(\App\Http\Middleware\ForceCorsSingleOrigin::class);


        /**
         * Web middleware
         */
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        /**
         * Trust ngrok / reverse proxy
         */
        $middleware->trustProxies(
            at: '*',
            headers:
                Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO
        );

        /**
         * CSRF exception (jika frontend beda origin)
         */
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);

        /**
         * Middleware aliases
         */
        $middleware->alias([
            'auth.check' => \App\Http\Middleware\AuthCheck::class,
            'api.token'  => \App\Http\Middleware\ApiTokenAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
