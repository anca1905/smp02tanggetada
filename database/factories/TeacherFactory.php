<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'employee_id' => $this->faker->unique()->numerify('EMP-####'),
            'phone' => $this->faker->phoneNumber(),
            'subject' => 'Matematika',
            'status' => 'Active',
            'username' => $this->faker->unique()->userName(),
            'password' => Hash::make('password'),
            'photo_url' => null,
        ];
    }
}
