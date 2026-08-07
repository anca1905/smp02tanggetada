<?php

namespace Database\Factories;

use App\Models\RoomBorrowing;
use App\Models\Room;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class RoomBorrowingFactory extends Factory
{
    protected $model = RoomBorrowing::class;

    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'nis' => $this->faker->numerify('##########'),
            'class' => '10A',
            'phone_number' => $this->faker->phoneNumber(),
            'room_type' => 'Auditorium',
            'borrow_date' => Carbon::today(),
            'activity_description' => $this->faker->sentence(),
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'responsible_person' => $this->faker->name(),
            'status' => 'upcoming',
        ];
    }
}
