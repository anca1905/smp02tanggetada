<?php

namespace App\Actions\Inbox;

use App\Models\Message;
use Illuminate\Pagination\LengthAwarePaginator;

class GetMessagesAction
{
    /**
     * Mengambil daftar pesan terbaru dengan pagination
     */
    public function execute(): LengthAwarePaginator
    {
        return Message::latest()->paginate(10);
    }
}
