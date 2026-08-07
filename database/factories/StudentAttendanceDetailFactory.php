<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentAttendanceDetail>
 */
class StudentAttendanceDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'attendance_id' => Attendance::factory(),
            'student_id' => Student::factory(),
            'status' => $this->faker->randomElement(['present', 'late', 'absent', 'permission', 'sick']),
        ];
    }
}
