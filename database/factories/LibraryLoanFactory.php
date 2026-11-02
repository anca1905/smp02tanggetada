<?php

namespace Database\Factories;

use App\Models\LibraryBook;
use App\Models\LibraryLoan;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class LibraryLoanFactory extends Factory
{
    protected $model = LibraryLoan::class;

    public function definition(): array
    {
        return [
            'book_id' => LibraryBook::factory(),
            'student_id' => Student::factory(),
            'loan_code' => 'PINJAM-'.fake()->unique()->numerify('######'),
            'loan_date' => now()->subDays(3)->format('Y-m-d'),
            'due_date' => now()->addDays(11)->format('Y-m-d'),
            'status' => 'borrowed',
            'fine_amount' => 0,
            'officer_name' => 'Petugas TU',
            'notes' => fake()->sentence(),
        ];
    }
}
