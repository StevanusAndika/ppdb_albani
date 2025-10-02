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
        ]);

        // Buat middleware group custom
        $middleware->group('admin', [
            'web', // Include web middleware group
            'auth', // User harus login
            'admin', // User harus role admin
        ]);

        $middleware->group('santri', [
            'web', // Include web middleware group
            'auth', // User harus login
            'santri', // User harus role santri
        ]);

        // Group untuk kombinasi admin dan santri (kedua role bisa akses)
        $middleware->group('admin_santri', [
            'web',
            'auth',
            // Bisa tambahkan middleware khusus untuk kedua role
        ]);

        // Atau tambahkan middleware ke group yang sudah ada
        $middleware->web(append: [
            // App\Http\Middleware\YourMiddleware::class,
        ]);

        $middleware->api(append: [
            // App\Http\Middleware\YourMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
