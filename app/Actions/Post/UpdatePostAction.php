<?php

namespace App\Actions\Post;

use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdatePostAction
{
    /**
     * Memperbarui data berita dan menangani pergantian thumbnail jika ada.
     */
    public function execute(Post $post, array $data, ?UploadedFile $image = null): Post
    {
        if ($image) {
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $image->store('posts', 'public');
        }

        if (isset($data['title']) && $data['title'] !== $post->title) {
            $slug = Str::slug($data['title']);
            $originalSlug = $slug;
            $count = 1;
            while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }
            $data['slug'] = $slug;
        }

        $post->update($data);

        return $post;
    }
}
