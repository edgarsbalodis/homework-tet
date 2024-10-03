<?php

use App\Jobs\ClearCache;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    
    ClearCache::dispatch();

    return view('welcome');
});
