<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

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

        $filled = array_filter(request()->only([
            'title',
            'post_text',
            'created_at'
        ]));

        $categoryId = $request->category_id ?: '';

        $posts = Post::when(count($filled) > 0, function($query) use ($filled) {
            foreach ($filled as $column => $value) {
                $query->where($column, 'LIKE', '%' . $value . '%');
            }
        })->when(request('search', '') != '', function($query) {
            $query->where(function ($q) {
                $q->where('title', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('post_text', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('created_at', 'LIKE', '%' . request('search') . '%');
            });
        })->when($categoryId !== '', function ($query, $categoryId) {
            $query->where('category_id', $categoryId);
        })->orderBy($sortField, $sortDirection)->paginate(5);

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
        if ($request->hasFile('thumbnail')) {
            $filename = $request->thumbnail->getClientOriginalName();

            info($filename);
        }

        $post = Post::create($request->validated());

        sleep(1);

        return new PostResource($post);
    }

    /**
     * Retorna um Post
     *
     * @param Post $post
     * @return PostResource
     */
    public function show(Post $post): PostResource
    {
        return new PostResource($post);
    }

    /**
     * Altera um Post
     *
     * @param Post $post
     * @return PostResource
     */
    public function update(Post $post, StorePostRequest $request): PostResource
    {
        $post->update($request->validated());

        return new PostResource($post);
    }

    /**
     * Exclui um Post
     *
     * @param Post $post
     * @return Response
     */
    public function destroy(Post $post): Response
    {
        $post->delete();

        return response()->noContent();
    }
}
