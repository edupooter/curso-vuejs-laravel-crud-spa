<?php

namespace App\Http\Controllers\Api;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::when(request('category_id', '') !== '', function($query) {
            $query->where('category_id', request('category_id'));
        })->paginate(3);

        return PostResource::collection($posts);
    }
}
