<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Daftarkan middleware aliases
        $middleware->alias([
            'admin' => App\Http\Middleware\AdminMiddleware::class,
            'santri' => App\Http\Middleware\SantriMiddleware::class,
            'auth' => Illuminate\Auth\Middleware\Authenticate::class,
        ]);

        // Optional: Jika perlu middleware global, tambahkan di sini
        $middleware->web(append: [
            // App\Http\Middleware\YourGlobalMiddleware::class,
        ]);

        $middleware->api(append: [
            // App\Http\Middleware\YourApiMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Custom exception handling bisa ditambahkan di sini
    })->create();
