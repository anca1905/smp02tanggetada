<?php

namespace App\Actions\Event;

use App\Models\Event;
use Illuminate\Pagination\LengthAwarePaginator;

class GetEventsAction
{
    /**
     * Mengambil daftar event terbaru dengan pagination
     */
    public function execute(): LengthAwarePaginator
    {
        return Event::latest()->paginate(10);
    }
}
