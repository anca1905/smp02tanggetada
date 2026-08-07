<?php

namespace Database\Factories;

use App\Models\Material;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    protected $model = Material::class;

    public function definition(): array
    {
        return [
            'schedule_id' => Schedule::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'type' => 'pdf',
            'file_path' => 'materials/dummy.pdf',
            'file_name' => 'dummy.pdf',
        ];
    }
}
