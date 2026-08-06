<?php

namespace App\Actions\Event;

use App\Models\Event;

class DeleteEventAction
{
    /**
     * Menghapus event/agenda
     */
    public function execute(Event $event): ?bool
    {
        return $event->delete();
    }
}
