<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GraduationFactory extends Factory
{
    public function definition(): array
    {
        $student = Student::factory()->create();

        return [
            'student_id' => $student->id,
            'nisn' => $this->faker->numerify('00########'),
            'nis' => $student->nis,
            'student_name' => $student->student_name,
            'birth_date' => '2010-05-12',
            'birth_place' => 'Kolaka',
            'classroom_name' => 'IX A',
            'letter_number' => '421.3/'.$this->faker->numberBetween(100, 999).'/SMP-02/2026',
            'status' => 'lulus',
            'average_score' => 84.50,
            'scores_json' => [
                'Bahasa Indonesia' => 85.0,
                'Matematika' => 82.0,
                'IPA' => 86.5,
            ],
            'graduation_date' => now()->format('Y-m-d'),
            'verification_code' => strtoupper(Str::random(12)),
            'notes' => null,
        ];
    }
}
