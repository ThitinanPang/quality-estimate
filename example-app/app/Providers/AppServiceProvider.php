<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;

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
        View::composer('layouts.header', function ($view) {
            $path = storage_path('app/report_access.json');
            $publishedRoles = [];

            if (File::exists($path)) {
                $publishedRoles = json_decode(File::get($path), true) ?: [];
            }

            $view->with('publishedRoles', $publishedRoles);
        });
    }
}
