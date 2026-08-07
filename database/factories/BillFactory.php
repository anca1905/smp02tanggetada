<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillFactory extends Factory
{
    protected $model = Bill::class;

    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'title' => 'SPP Bulan Ini',
            'type' => 'SPP Bulanan',
            'amount' => 500000,
            'due_date' => Carbon::now()->addDays(10),
            'status' => 'unpaid',
        ];
    }
}
