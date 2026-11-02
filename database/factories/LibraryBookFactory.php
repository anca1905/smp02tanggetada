<?php

namespace Database\Factories;

use App\Models\LibraryBook;
use Illuminate\Database\Eloquent\Factories\Factory;

class LibraryBookFactory extends Factory
{
    protected $model = LibraryBook::class;

    public function definition(): array
    {
        return [
            'book_code' => 'BOS-TEST-'.fake()->unique()->numerify('####'),
            'title' => fake()->sentence(4),
            'author' => fake()->name(),
            'publisher' => fake()->company(),
            'publish_year' => '2023',
            'isbn' => fake()->isbn13(),
            'category' => 'buku_paket_bos',
            'class_level' => '7',
            'total_stock' => 50,
            'available_stock' => 50,
            'location_shelf' => 'Rak A1',
            'description' => fake()->paragraph(),
        ];
    }
}
