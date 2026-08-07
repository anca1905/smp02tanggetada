<?php

namespace App\Actions\Public;

use App\Models\Post;

class GetNewsDetailAction
{
    /**
     * Get single news detail by slug and recent posts.
     *
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function execute(string $slug): array
    {
        $post = Post::where('slug', $slug)->where('is_published', true)->firstOrFail();

        $recent_posts = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(5)
            ->get();

        return compact('post', 'recent_posts');
    }
}
