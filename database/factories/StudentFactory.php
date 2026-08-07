<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'nis' => $this->faker->unique()->numerify('12####'),
            'student_name' => $this->faker->name(),
            'gender' => $this->faker->randomElement(['M', 'F']),
            'classroom_id' => Classroom::factory(),
            'student_status' => 'Active',
            'password' => Hash::make('password'),
        ];
    }
}
