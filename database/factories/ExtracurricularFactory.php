<?php

namespace Database\Factories;

use App\Models\Extracurricular;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExtracurricularFactory extends Factory
{
    protected $model = Extracurricular::class;

    public function definition(): array
    {
        return [
            'name' => 'Ekskul '.fake()->unique()->words(2, true),
            'category' => 'olahraga',
            'instructor_name' => fake()->name(),
            'schedule_day' => 'Jumat, 15:30 WITA',
            'location' => 'Lapangan Sekolah',
            'description' => fake()->paragraph(),
            'is_active' => true,
        ];
    }
}
