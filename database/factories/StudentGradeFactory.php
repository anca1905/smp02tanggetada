<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Subject;
use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentGrade>
 */
class StudentGradeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'subject_id' => Subject::factory(),
            'score' => $this->faker->numberBetween(50, 100),
            'type' => $this->faker->randomElement(['Tugas', 'UTS', 'UAS']),
            'description' => $this->faker->sentence(),
        ];
    }
}
