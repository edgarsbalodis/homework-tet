<?php

namespace Backscreen\Movies\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'director' => 'nullable|string',
            'release_date' => 'nullable|date',
            'genre' => 'nullable|string',
        ];
    }
}