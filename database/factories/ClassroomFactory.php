<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\AcademicYear;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassroomFactory extends Factory
{
    protected $model = Classroom::class;

    public function definition(): array
    {
        return [
            'name' => '10A',
            'level' => '10',
            'academic_year_id' => AcademicYear::factory(),
            'teacher_id' => Teacher::factory(),
        ];
    }
}
