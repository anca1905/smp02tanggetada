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

    public function test_admin_tu_can_stream_attendance_recap_pdf_with_proper_elements(): void
    {
        $operator = Operator::factory()->create();
        $teacher = Teacher::factory()->create(['name' => 'Eka Widiyawati, S.Pd', 'employee_id' => '19850101']);
        $classroom = Classroom::factory()->create(['name' => 'IX B', 'teacher_id' => $teacher->id]);

        $student1 = Student::factory()->create([
            'classroom_id' => $classroom->id,
            'student_name' => 'Alif',
            'gender' => 'M',
            'nis' => '875',
            'nisn' => '0119324778',
        ]);

        $student2 = Student::factory()->create([
            'classroom_id' => $classroom->id,
            'student_name' => 'Dea Novarina',
            'gender' => 'F',
            'nis' => '927',
            'nisn' => '3126217225',
        ]);

        $date = Carbon::create(2026, 9, 15)->format('Y-m-d');
        $attendance = Attendance::factory()->create([
            'teacher_id' => $teacher->id,
            'date' => $date,
            'session_type' => 'apel',
            'class' => $classroom->name,
        ]);

        StudentAttendance::create([
            'attendance_id' => $attendance->id,
            'student_id' => $student1->id,
            'status' => 'sick',
        ]);

        StudentAttendance::create([
            'attendance_id' => $attendance->id,
            'student_id' => $student2->id,
            'status' => 'permission',
        ]);

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.rekap.pdf', [
                'bulan' => 9,
                'tahun' => 2026,
                'kelas' => $classroom->id,
                'stream' => 1,
            ]));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString('inline', $response->headers->get('Content-Disposition'));
    }

    public function test_attendance_recap_rendered_view_contains_expected_dapodik_format(): void
    {
        $teacher = Teacher::factory()->create(['name' => 'Eka Widiyawati', 'employee_id' => '19850101']);
        $classroom = Classroom::factory()->create(['name' => 'IX B', 'teacher_id' => $teacher->id]);

        $student = Student::factory()->create([
            'classroom_id' => $classroom->id,
            'student_name' => 'Alif',
            'gender' => 'M',
            'nis' => '875',
            'nisn' => '0119324778',
        ]);

        $rendered = view('pdf.attendance_recap', [
            'bulan' => 9,
            'tahun' => 2026,
            'daysInMonth' => 30,
            'monthName' => 'September',
            'selectedClass' => $classroom,
            'site_settings' => ['school_name' => 'SMP NEGERI 2 TANGGETADA'],
            'sheets' => [
                [
                    'classroom' => $classroom,
                    'namaRombel' => 'IX B',
                    'academicYearName' => '2026/2027',
                    'semesterName' => 'Semester Ganjil',
                    'waliKelasName' => 'Eka Widiyawati',
                    'waliKelasNip' => '19850101',
                    'students' => collect([$student]),
                    'countL' => 1,
                    'countP' => 0,
                    'totalCount' => 1,
                ],
            ],
            'matrix' => [
                $student->id => [
                    15 => 'S',
                ],
            ],
            'sesi' => null,
            'data' => collect(),
        ])->render();

        $this->assertStringContainsString('DAFTAR HADIR SISWA', $rendered);
        $this->assertStringContainsString('SMP NEGERI 2 TANGGETADA', $rendered);
        $this->assertStringContainsString('TAHUN PELAJARAN 2026/2027', $rendered);
        $this->assertStringContainsString('Jenis Rombel: Kelas Utama - Nama Rombel: IX B - Semester Ganjil - Wali Kelas: Eka Widiyawati', $rendered);
        $this->assertStringContainsString('URUT', $rendered);
        $this->assertStringContainsString('NISN / NIS', $rendered);
        $this->assertStringContainsString('NAMA SISWA', $rendered);
        $this->assertStringContainsString('L/P', $rendered);
        $this->assertStringContainsString('Bulan September 2026', $rendered);
        $this->assertStringContainsString('Tanggal', $rendered);
        $this->assertStringContainsString('0119324778 / 875', $rendered);
        $this->assertStringContainsString('Alif', $rendered);
        $this->assertStringContainsString('Keterangan', $rendered);
        $this->assertStringContainsString('Sakit', $rendered);
        $this->assertStringContainsString('Izin', $rendered);
        $this->assertStringContainsString('Alpa', $rendered);
        $this->assertStringContainsString('Laki-Laki', $rendered);
        $this->assertStringContainsString('Perempuan', $rendered);
        $this->assertStringContainsString('Jumlah', $rendered);
        $this->assertStringContainsString(': 1 Orang', $rendered);
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
