<?php

namespace App\Actions\Post;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class DeletePostAction
{
    /**
     * Menghapus berita dan gambar terkait
     */
    public function execute(Post $post): ?bool
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        return $post->delete();
    }
}
