<?php

namespace Tests\Feature\AdminTu;

use App\Models\Classroom;
use App\Models\Graduation;
use App\Models\Operator;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GraduationManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_graduations(): void
    {
        $response = $this->get(route('tu.graduations.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_tu_can_view_graduations_index(): void
    {
        $operator = Operator::factory()->create();

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.graduations.index'));

        $response->assertOk();
        $response->assertSee('Manajemen Kelulusan');
    }

    public function test_admin_tu_can_store_graduation(): void
    {
        $operator = Operator::factory()->create();
        $classroom = Classroom::factory()->create(['name' => 'IX B']);
        $student = Student::factory()->create([
            'classroom_id' => $classroom->id,
            'student_name' => 'Rina Melati',
            'nis' => '130005',
        ]);

        $payload = [
            'student_id' => $student->id,
            'nisn' => '0087654321',
            'letter_number' => '421.3/SKL/001/SMP-02/2026',
            'status' => 'lulus',
            'average_score' => 88.75,
            'graduation_date' => now()->format('Y-m-d'),
        ];

        $response = $this->actingAs($operator, 'operator')
            ->post(route('tu.graduations.store'), $payload);

        $response->assertRedirect(route('tu.graduations.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('graduations', [
            'student_id' => $student->id,
            'student_name' => 'Rina Melati',
            'status' => 'lulus',
            'average_score' => 88.75,
        ]);

        $this->assertEquals('Graduated', $student->fresh()->student_status);
    }

    public function test_admin_tu_can_update_graduation(): void
    {
        $operator = Operator::factory()->create();
        $graduation = Graduation::factory()->create([
            'status' => 'ditunda',
            'average_score' => 75.00,
        ]);

        $payload = [
            'letter_number' => $graduation->letter_number,
            'status' => 'lulus',
            'average_score' => 82.00,
            'graduation_date' => now()->format('Y-m-d'),
        ];

        $response = $this->actingAs($operator, 'operator')
            ->put(route('tu.graduations.update', $graduation), $payload);

        $response->assertRedirect(route('tu.graduations.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('graduations', [
            'id' => $graduation->id,
            'status' => 'lulus',
            'average_score' => 82.00,
        ]);
    }

    public function test_admin_tu_can_delete_graduation(): void
    {
        $operator = Operator::factory()->create();
        $graduation = Graduation::factory()->create();

        $response = $this->actingAs($operator, 'operator')
            ->delete(route('tu.graduations.destroy', $graduation));

        $response->assertRedirect(route('tu.graduations.index'));
        $this->assertDatabaseMissing('graduations', ['id' => $graduation->id]);
    }

    public function test_public_can_check_graduation_and_download_skl(): void
    {
        Setting::updateOrCreate(['key' => 'graduation_status'], ['value' => '1']);

        $graduation = Graduation::factory()->create([
            'student_name' => 'Aditya Pratama',
            'nis' => '120015',
            'nisn' => '0089998888',
            'status' => 'lulus',
        ]);

        // Cek halaman publik
        $pageResponse = $this->get(route('public.kelulusan'));
        $pageResponse->assertOk();
        $pageResponse->assertSee('Cek Kelulusan Siswa Kelas IX');

        // Submit cek kelulusan
        $checkResponse = $this->post(route('public.kelulusan.cek'), [
            'identity' => '120015',
        ]);

        $checkResponse->assertOk();
        $checkResponse->assertSee('SELAMAT, ANDA LULUS!');
        $checkResponse->assertSee('Aditya Pratama');

        // Download SKL PDF
        $pdfResponse = $this->get(route('public.kelulusan.skl.download', $graduation));
        $pdfResponse->assertOk();
        $this->assertEquals('application/pdf', $pdfResponse->headers->get('content-type'));
    }

    public function test_public_can_verify_skl_via_code(): void
    {
        $graduation = Graduation::factory()->create([
            'verification_code' => 'VERIFTEST123',
            'student_name' => 'Dewi Lestari',
        ]);

        $response = $this->get(route('public.kelulusan.verify', 'VERIFTEST123'));
        $response->assertOk();
        $response->assertSee('DOKUMEN INI ASLI');
        $response->assertSee('Dewi Lestari');
    }
}
