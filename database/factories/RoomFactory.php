<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'room_name' => 'Ruang '.$this->faker->unique()->numberBetween(1, 100),
            'location' => 'Lantai 1',
            'description' => 'Ruang Teori',
        ];
    }
}
