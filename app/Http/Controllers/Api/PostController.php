<?php

namespace App\Http\Controllers\Api;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->sort_field ?: 'created_at';

        if (!in_array($sortField, ['title', 'post_text', 'created_at'])) {
            $sortField = 'created_at';
        }

        $sortDirection = $request->sort_direction ?: 'desc';

        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $categoryId = $request->category_id ?: '';

        $posts = Post::when($categoryId !== '', function($query, $categoryId) {
            $query->where('category_id', $categoryId);
        })->orderBy($sortField, $sortDirection)->paginate(3);

        return PostResource::collection($posts);
    }
}
