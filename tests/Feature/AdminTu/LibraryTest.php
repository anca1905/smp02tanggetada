<?php

namespace Tests\Feature\AdminTu;

use App\Models\Classroom;
use App\Models\LibraryBook;
use App\Models\LibraryLoan;
use App\Models\Operator;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LibraryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_library(): void
    {
        $response = $this->get(route('tu.library.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_tu_can_view_library_index(): void
    {
        $operator = Operator::factory()->create();

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.library.index'));

        $response->assertOk();
        $response->assertSee('Katalog Buku Perpustakaan');
    }

    public function test_admin_tu_can_store_book(): void
    {
        $operator = Operator::factory()->create();

        $payload = [
            'book_code' => 'BOS-7-IPA-NEW',
            'title' => 'Ilmu Pengetahuan Alam Kelas 7',
            'author' => 'Tim Penulis Kemdikbud',
            'publisher' => 'Pusat Perbukuan',
            'publish_year' => '2024',
            'isbn' => '978-602-244-123-4',
            'category' => 'buku_paket_bos',
            'class_level' => '7',
            'total_stock' => 60,
            'location_shelf' => 'Rak BOS 7B',
            'description' => 'Buku teks utama',
        ];

        $response = $this->actingAs($operator, 'operator')
            ->post(route('tu.library.books.store'), $payload);

        $response->assertRedirect(route('tu.library.index', ['tab' => 'books']));
        $this->assertDatabaseHas('library_books', [
            'book_code' => 'BOS-7-IPA-NEW',
            'title' => 'Ilmu Pengetahuan Alam Kelas 7',
            'available_stock' => 60,
        ]);
    }

    public function test_admin_tu_can_update_book(): void
    {
        $operator = Operator::factory()->create();
        $book = LibraryBook::factory()->create([
            'book_code' => 'BOS-OLD-01',
            'title' => 'Judul Lama',
            'total_stock' => 40,
            'available_stock' => 40,
        ]);

        $payload = [
            'book_code' => 'BOS-UPDATED-01',
            'title' => 'Judul Baru Revisi',
            'category' => 'buku_paket_bos',
            'class_level' => '8',
            'total_stock' => 45,
            'location_shelf' => 'Rak Baru',
        ];

        $response = $this->actingAs($operator, 'operator')
            ->put(route('tu.library.books.update', $book), $payload);

        $response->assertRedirect(route('tu.library.index', ['tab' => 'books']));
        $this->assertDatabaseHas('library_books', [
            'id' => $book->id,
            'book_code' => 'BOS-UPDATED-01',
            'title' => 'Judul Baru Revisi',
            'total_stock' => 45,
        ]);
    }

    public function test_admin_tu_can_store_loan_and_decrement_stock(): void
    {
        $operator = Operator::factory()->create();
        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);
        $book = LibraryBook::factory()->create([
            'total_stock' => 10,
            'available_stock' => 10,
        ]);

        $payload = [
            'book_id' => $book->id,
            'student_id' => $student->id,
            'loan_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(14)->format('Y-m-d'),
            'officer_name' => 'Petugas TU',
            'notes' => 'Pinjam buku tugas',
        ];

        $response = $this->actingAs($operator, 'operator')
            ->post(route('tu.library.loans.store'), $payload);

        $response->assertRedirect(route('tu.library.index', ['tab' => 'loans']));
        $this->assertDatabaseHas('library_loans', [
            'book_id' => $book->id,
            'student_id' => $student->id,
            'status' => 'borrowed',
        ]);

        $this->assertEquals(9, $book->fresh()->available_stock);
    }

    public function test_admin_tu_can_bulk_loan_to_classroom(): void
    {
        $operator = Operator::factory()->create();
        $classroom = Classroom::factory()->create(['name' => 'VII-B']);
        $students = Student::factory()->count(5)->create(['classroom_id' => $classroom->id]);
        $book = LibraryBook::factory()->create([
            'category' => 'buku_paket_bos',
            'total_stock' => 30,
            'available_stock' => 30,
        ]);

        $payload = [
            'book_id' => $book->id,
            'classroom_id' => $classroom->id,
            'loan_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(90)->format('Y-m-d'),
            'officer_name' => 'Petugas TU',
            'notes' => 'Buku BOS Smt 1',
        ];

        $response = $this->actingAs($operator, 'operator')
            ->post(route('tu.library.loans.bulk'), $payload);

        $response->assertRedirect(route('tu.library.index', ['tab' => 'loans']));
        $this->assertEquals(5, LibraryLoan::where('book_id', $book->id)->count());
        $this->assertEquals(25, $book->fresh()->available_stock);
    }

    public function test_admin_tu_can_return_loan_and_restore_stock(): void
    {
        $operator = Operator::factory()->create();
        $book = LibraryBook::factory()->create([
            'total_stock' => 10,
            'available_stock' => 9,
        ]);
        $loan = LibraryLoan::factory()->create([
            'book_id' => $book->id,
            'status' => 'borrowed',
        ]);

        $response = $this->actingAs($operator, 'operator')
            ->patch(route('tu.library.loans.return', $loan), [
                'status' => 'returned',
                'fine_amount' => 0,
                'notes' => 'Dikembalikan dalam kondisi sangat baik',
            ]);

        $response->assertRedirect(route('tu.library.index', ['tab' => 'loans']));
        $this->assertEquals('returned', $loan->fresh()->status);
        $this->assertEquals(10, $book->fresh()->available_stock);
    }

    public function test_public_user_can_view_library_catalogue(): void
    {
        $book = LibraryBook::factory()->create(['title' => 'Buku Ensiklopedia IPA']);

        $response = $this->get(route('public.perpustakaan'));
        $response->assertOk();
        $response->assertSee('Buku Ensiklopedia IPA');
    }
}
