<?php

namespace App\Actions\Room;

use App\Models\Room;

class DeleteRoomAction
{
    /**
     * Menghapus data ruangan berdasarkan ID.
     *
     * @param  Room  $room  ID ruangan yang akan dihapus.
     */
    public function execute(Room $room): void
    {
        $room->delete();
    }
}
