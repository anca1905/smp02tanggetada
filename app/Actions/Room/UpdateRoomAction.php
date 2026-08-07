<?php

namespace App\Actions\Room;

use App\Models\Room;

class UpdateRoomAction
{
    /**
     * Mengupdate data ruangan berdasarkan ID.
     */
    public function execute(array $data, Room $room): Room
    {
        $room->update($data);

        return $room;
    }
}
