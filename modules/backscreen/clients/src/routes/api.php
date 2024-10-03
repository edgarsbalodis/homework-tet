<?php

use Illuminate\Support\Facades\Route;
use Backscreen\Clients\Http\Controllers\Api\ClientsController;

Route::resource('clients', ClientsController::class);
