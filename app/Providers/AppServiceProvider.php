<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\FileSystemRepositoryServices;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
         // file system service
         $this->app->singleton('FileSystemRepositoryServices', function ($app) {
            return new FileSystemRepositoryServices;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
