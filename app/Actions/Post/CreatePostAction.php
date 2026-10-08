<?php

namespace App\Actions\Post;

use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CreatePostAction
{
    /**
     * Membuat berita baru dan menangani upload gambar.
     */
    public function execute(array $data, ?UploadedFile $image = null): Post
    {
        $imagePath = null;
        if ($image) {
            $imagePath = $image->store('posts', 'public');
        }

        $slug = Str::slug($data['title']);
        $originalSlug = $slug;
        $count = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        $data['slug'] = $slug;
        $data['image'] = $imagePath;
        $data['is_published'] = true;

        return Post::create($data);
    }
}
