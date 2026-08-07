<?php

namespace App\Actions\Room;

use App\Models\Room;

class CreateRoomAction
{
    /**
     * Membuat room baru
     */
    public function execute(array $room): Room
    {
        return Room::create($room);
    }
}
