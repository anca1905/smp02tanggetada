<?php

namespace Database\Factories;

use App\Models\CounselingCategory;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentCounselingRecordFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'counseling_category_id' => CounselingCategory::factory(),
            'title' => $this->faker->sentence(4),
            'type' => 'violation',
            'point' => 10,
            'incident_date' => now()->format('Y-m-d'),
            'description' => $this->faker->paragraph(),
            'action_taken' => 'Pembinaan wali kelas',
            'status' => 'resolved',
            'reported_by' => 'Guru Piket',
        ];
    }
}
