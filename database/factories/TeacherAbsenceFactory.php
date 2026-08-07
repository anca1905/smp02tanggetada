<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\Teacher_absence;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherAbsenceFactory extends Factory
{
    protected $model = Teacher_absence::class;

    public function definition(): array
    {
        return [
            'teacher_id' => Teacher::factory(),
            'date' => Carbon::today()->format('Y-m-d'),
            'arrival_time' => '07:00:00',
            'return_time' => '14:00:00',
            'arrival_photo_url' => 'dummy/photo.jpg',
            'return_photo_url' => 'dummy/photo2.jpg',
        ];
    }
}
