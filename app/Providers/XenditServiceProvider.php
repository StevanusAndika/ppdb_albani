<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Xendit\Xendit; // Comment sementara

class XenditServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/xendit.php', 'xendit'
        );
    }

    public function boot(): void
    {
        // Comment sementara untuk bisa publish
        // Xendit::setApiKey(config('xendit.secret_key'));

        $this->publishes([
            __DIR__.'/../../config/xendit.php' => config_path('xendit.php'),
        ], 'xendit-config');
    }
}
