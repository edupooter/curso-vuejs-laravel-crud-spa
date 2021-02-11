<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    /**
     * Retona uma lista de Posts, paginada e ordenada dinamicamente
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
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

        $posts = Post::when($categoryId !== '', function ($query, $categoryId) {
            $query->where('category_id', $categoryId);
        })->orderBy($sortField, $sortDirection)->paginate(3);

        return PostResource::collection($posts);
    }

    /**
     * Armazena novo registro de Post
     *
     * @param StorePostRequest $request
     * @return PostResource
     */
    public function store(StorePostRequest $request): PostResource
    {
        $post = Post::create($request->validated());

        return new PostResource($post);
    }
}
