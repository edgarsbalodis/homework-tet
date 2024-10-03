<?php

namespace Backscreen\Helpers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Backscreen\Helpers\Logger;

class HelperServiceProvider extends ServiceProvider {

    // bootstrap web services
    // listen for events
    // publish configuration files or database migrations
    /**
     * 1. 
     * 2. add to laravel project repositories
     * 3. composer update
     * 4. composer dump-autoload
     */
    public function boot()
    {
        $this->app->singleton(Logger::class, function ($app) {
            return new Logger();
        });
    }

    public function register()
    {

    }
}