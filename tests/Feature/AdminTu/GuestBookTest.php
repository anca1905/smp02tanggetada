<?php

namespace Tests\Feature\AdminTu;

use App\Models\GuestBook;
use App\Models\Operator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestBookTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_view_guest_book_and_submit_entry(): void
    {
        $response = $this->get(route('public.buku-tamu'));
        $response->assertOk();
        $response->assertSee('Formulir Kunjungan Tamu');

        $payload = [
            'name' => 'Drs. Bambang Sudirman',
            'institution' => 'Dinas Pendidikan Kab. Kolaka',
            'phone' => '081234567890',
            'category' => 'dinas_pemerintah',
            'meet_with' => 'kepala_sekolah',
            'purpose' => 'Koordinasi evaluasi mutu pendidikan semester ganjil',
            'visit_date' => now()->format('Y-m-d'),
        ];

        $postResponse = $this->post(route('public.buku-tamu.store'), $payload);
        $postResponse->assertRedirect();
        $postResponse->assertSessionHas('success');

        $this->assertDatabaseHas('guest_books', [
            'name' => 'Drs. Bambang Sudirman',
            'institution' => 'Dinas Pendidikan Kab. Kolaka',
            'category' => 'dinas_pemerintah',
        ]);
    }

    public function test_admin_tu_can_view_guest_books_and_update_status(): void
    {
        $operator = Operator::factory()->create();
        $guest = GuestBook::factory()->create([
            'status' => 'waiting',
        ]);

        $indexResponse = $this->actingAs($operator, 'operator')
            ->get(route('tu.guest-books.index'));

        $indexResponse->assertOk();
        $indexResponse->assertSee('Register Buku Tamu Digital');

        $updateResponse = $this->actingAs($operator, 'operator')
            ->patch(route('tu.guest-books.status', $guest), [
                'status' => 'completed',
                'notes' => 'Tamu telah diterima oleh Kepala Sekolah',
            ]);

        $updateResponse->assertRedirect(route('tu.guest-books.index'));
        $updateResponse->assertSessionHas('success');

        $this->assertDatabaseHas('guest_books', [
            'id' => $guest->id,
            'status' => 'completed',
            'notes' => 'Tamu telah diterima oleh Kepala Sekolah',
        ]);
    }

    public function test_admin_tu_can_export_guest_books_pdf(): void
    {
        $operator = Operator::factory()->create();
        GuestBook::factory()->count(3)->create();

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.guest-books.export-pdf'));

        $response->assertOk();
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    public function test_admin_tu_can_delete_guest_book_entry(): void
    {
        $operator = Operator::factory()->create();
        $guest = GuestBook::factory()->create();

        $response = $this->actingAs($operator, 'operator')
            ->delete(route('tu.guest-books.destroy', $guest));

        $response->assertRedirect(route('tu.guest-books.index'));
        $this->assertDatabaseMissing('guest_books', ['id' => $guest->id]);
    }
}
