<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;

class FacilityFactory extends Factory
{
    protected $model = Facility::class;

    public function definition(): array
    {
        return [
            'name' => 'Proyektor '.$this->faker->unique()->numberBetween(1, 100),
            'quantity' => 10,
            'condition' => 'Good',
        ];
    }
}
