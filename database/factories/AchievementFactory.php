<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AchievementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => 'Juara 1 '.$this->faker->words(3, true),
            'student_name' => $this->faker->name(),
            'student_id' => null,
            'category' => 'akademik',
            'level' => 'kabupaten',
            'rank' => 'Juara 1',
            'event_name' => $this->faker->sentence(3),
            'event_date' => now()->format('Y-m-d'),
            'photo_path' => null,
            'description' => $this->faker->paragraph(),
            'is_featured' => false,
        ];
    }
}
