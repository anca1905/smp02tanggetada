<?php

namespace App\Actions\Event;

use App\Models\Event;

class UpdateEventAction
{
    /**
     * Memperbarui data event/agenda.
     */
    public function execute(Event $event, array $data): Event
    {
        $event->update($data);

        return $event;
    }
}
