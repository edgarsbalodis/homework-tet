<?php

use Illuminate\Support\Facades\Route;
use Backscreen\Movies\Http\Controllers\Api\MoviesController;

Route::resource('movies', MoviesController::class);
