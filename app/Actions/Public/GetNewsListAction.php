<?php

namespace App\Actions\Public;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetNewsListAction
{
    /**
     * Get paginated published news.
     */
    public function execute(): LengthAwarePaginator
    {
        return Post::where('is_published', true)->latest()->paginate(9);
    }
}
