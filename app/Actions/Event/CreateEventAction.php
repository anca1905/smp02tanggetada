<?php

namespace App\Actions\Event;

use App\Models\Event;

class CreateEventAction
{
    /**
     * Membuat event/agenda baru
     */
    public function execute(array $data): Event
    {
        return Event::create($data);
    }
}
