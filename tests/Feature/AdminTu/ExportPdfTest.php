<?php

namespace Tests\Feature\AdminTu;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Operator;
use App\Models\Ppdb;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportPdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_tu_can_export_student_card_to_pdf(): void
    {
        $operator = Operator::factory()->create();
        $classroom = Classroom::factory()->create(['name' => 'VII-A']);
        $student = Student::factory()->create([
            'nis' => '120001',
            'student_name' => 'Ahmad Dahlan',
            'classroom_id' => $classroom->id,
            'gender' => 'M',
        ]);

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.student.card.pdf', $student));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString('kartu-pelajar-120001.pdf', $response->headers->get('Content-Disposition'));
    }

    public function test_admin_tu_can_export_attendance_recap_to_pdf(): void
    {
        $operator = Operator::factory()->create();
        $teacher = Teacher::factory()->create();
        $classroom = Classroom::factory()->create(['name' => 'VII-A']);
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);

        $attendance = Attendance::factory()->create([
            'teacher_id' => $teacher->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'session_type' => 'apel',
            'class' => $classroom->name,
        ]);

        StudentAttendance::create([
            'attendance_id' => $attendance->id,
            'student_id' => $student->id,
            'status' => 'present',
        ]);

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.rekap.pdf', [
                'bulan' => Carbon::now()->month,
                'sesi' => 'apel',
                'kelas' => $classroom->id,
            ]));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString('rekap-absensi-siswa', $response->headers->get('Content-Disposition'));
    }

    public function test_admin_tu_can_export_ppdb_receipt_to_pdf(): void
    {
        $operator = Operator::factory()->create();
        $applicant = Ppdb::factory()->create([
            'no_registrasi' => 'REG-2026-0001',
            'nama_lengkap' => 'FAHRI HAMZAH',
            'status_pendaftaran' => 'Pending',
        ]);

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.ppdb.pdf', $applicant->id));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString('bukti-pendaftaran-REG-2026-0001.pdf', $response->headers->get('Content-Disposition'));
    }

    public function test_public_can_download_ppdb_receipt_by_registration_number(): void
    {
        $applicant = Ppdb::factory()->create([
            'no_registrasi' => 'REG-2026-0099',
            'nama_lengkap' => 'SITI AMINAH',
        ]);

        $response = $this->get(route('public.ppdb.receipt', 'REG-2026-0099'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString('bukti-pendaftaran-REG-2026-0099.pdf', $response->headers->get('Content-Disposition'));
    }

    public function test_guest_cannot_access_admin_tu_pdf_exports(): void
    {
        $student = Student::factory()->create();
        $applicant = Ppdb::factory()->create();

        $this->get(route('tu.student.card.pdf', $student))
            ->assertRedirect(route('login'));

        $this->get(route('tu.rekap.pdf'))
            ->assertRedirect(route('login'));

        $this->get(route('tu.ppdb.pdf', $applicant->id))
            ->assertRedirect(route('login'));
    }
}
