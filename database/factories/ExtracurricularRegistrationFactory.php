<?php

namespace Database\Factories;

use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExtracurricularRegistrationFactory extends Factory
{
    protected $model = ExtracurricularRegistration::class;

    public function definition(): array
    {
        return [
            'extracurricular_id' => Extracurricular::factory(),
            'student_id' => Student::factory(),
            'student_name' => fake()->name(),
            'classroom_name' => 'Kelas VII-A',
            'phone' => '0812'.fake()->numerify('########'),
            'reason' => fake()->sentence(),
            'status' => 'pending',
            'notes' => null,
        ];
    }
}
