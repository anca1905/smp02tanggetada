<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CounselingCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'type' => 'violation',
            'point' => 10,
            'description' => $this->faker->sentence(),
        ];
    }
}
