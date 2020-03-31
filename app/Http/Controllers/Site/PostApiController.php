<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostApiController extends Controller
{
    /**
     * Retorna o post em formato json
     */
    public function show($id)
    {
        try {
            return new \App\Http\Resources\Post(\App\Post::findOrFail($id));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response(['message' => 'Post Not Found'], 404);
        }
    }
}
