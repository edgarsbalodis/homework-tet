<?php

namespace Backscreen\Movies\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model {

    use HasFactory;

    protected $table = 'movies';

    protected $fillable = ['title', 'description', 'director', 'release_date', 'genre'];

}