<?php

namespace App\Actions\Inbox;

use App\Models\Message;

class DeleteMessageAction
{
    /**
     * Menghapus data pesan (inbox)
     */
    public function execute(Message $message): ?bool
    {
        return $message->delete();
    }
}
