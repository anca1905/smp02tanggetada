<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GuestBookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'institution' => $this->faker->company(),
            'phone' => '08'.$this->faker->numerify('##########'),
            'category' => 'wali_murid',
            'meet_with' => 'kepala_sekolah',
            'purpose' => $this->faker->sentence(),
            'visit_date' => now()->format('Y-m-d'),
            'visit_time' => now()->format('H:i'),
            'status' => 'completed',
            'notes' => null,
        ];
    }
}
