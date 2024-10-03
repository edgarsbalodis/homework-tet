<?php

namespace Backscreen\Clients;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ClientServiceProvider extends ServiceProvider {

    // bootstrap web services
    // listen for events
    // publish configuration files or database migrations!!
    /**
     * 1. 
     * 2. add to laravel project repositories
     * 3. composer update
     * 4. composer dump-autoload
     */
    public function boot()
    {
        
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        
        // Route option 1
        Route::prefix('api')->middleware('api')->group(__DIR__.'/routes/api.php');

        // Route option 2 
        // $this->loadRoutesFrom(__DIR__.'/routes/api.php');
        


        
    }

    public function register()
    {

    }
}