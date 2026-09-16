<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'start_time' => $this->faker->time(),
            'class' => Classroom::factory(),
            'subject_id' => null,
            'teacher_id' => Teacher::factory(),
            'qr_token' => $this->faker->uuid(),
            'qr_expires_at' => now()->addMinutes(30),
        ];
    }
}
