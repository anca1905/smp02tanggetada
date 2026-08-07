<?php

namespace Tests\Unit\AdminTu\Actions\Room;

use App\Actions\Room\CreateRoomAction;
use App\Actions\Room\DeleteRoomAction;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_room()
    {
        $action = new CreateRoomAction;
        $room = $action->execute([
            'room_name' => 'Lab Komputer',
            'location' => 'Lantai 2',
            'description' => 'Lab Jaringan',
        ]);

        $this->assertEquals('Lab Komputer', $room->room_name);
        $this->assertDatabaseHas('rooms', ['room_name' => 'Lab Komputer']);
    }

    public function test_it_deletes_room()
    {
        $room = Room::factory()->create();

        $action = new DeleteRoomAction;
        $action->execute($room);

        $this->assertDatabaseMissing('rooms', ['room_id' => $room->room_id]);
    }
}
