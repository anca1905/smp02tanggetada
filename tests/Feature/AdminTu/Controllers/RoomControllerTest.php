<?php

namespace Tests\Feature\AdminTu\Controllers;

use Tests\TestCase;
use App\Models\Operator;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoomControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_displays_rooms_list()
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        Room::factory()->count(3)->create();

        $response = $this->actingAs($operator, 'operator')
                         ->get(route('tu.room.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tu.room_data');
    }

    public function test_it_stores_new_room()
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);

        $response = $this->actingAs($operator, 'operator')
                         ->post(route('tu.room.store'), [
                             'room_name' => 'Lab IPA Baru',
                             'location' => 'Lantai 3',
                             'description' => 'Lab Fisika dan Biologi'
                         ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('rooms', ['room_name' => 'Lab IPA Baru']);
    }

    public function test_it_deletes_room()
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        $room = Room::factory()->create();

        $response = $this->actingAs($operator, 'operator')
                         ->delete(route('tu.room.destroy', $room->room_id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('rooms', ['room_id' => $room->room_id]);
    }
}
