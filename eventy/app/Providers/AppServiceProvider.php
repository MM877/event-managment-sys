<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Vite;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Check if the Vite manifest exists, if not use fallback layout
        if (!File::exists(public_path('build/manifest.json'))) {
            Blade::component('layouts.dev', 'layouts.app');
        }
    }
}
