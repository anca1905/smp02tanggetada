<?php

namespace App\Actions\Post;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetPostsAction
{
    /**
     * Mengambil daftar berita dengan pagination
     */
    public function execute(int $perPage = 10): LengthAwarePaginator
    {
        return Post::latest()->paginate($perPage);
    }
}
