<?php

namespace Tests\Feature\Teacher;

use App\Models\Classroom;
use App\Models\Operator;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentIdCardAttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_scan_barcode_with_valid_nis_saves_attendance_and_student_relation_works(): void
    {
        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create([
            'classroom_id' => $classroom->id,
            'nis' => '120001',
            'student_name' => 'Ahmad Pelajar',
        ]);

        $date = Carbon::today()->toDateString();

        $response = $this->postJson(route('presensi.scan'), [
            'nis' => '120001',
            'session_type' => 'apel',
            'class' => $classroom->id,
            'date' => $date,
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'student_name' => 'Ahmad Pelajar',
                'already_checked' => false,
            ]);

        $this->assertDatabaseHas('student_attendance_details', [
            'student_id' => $student->id,
            'status' => 'present',
        ]);

        // Verify StudentAttendance model relation
        $attendance = StudentAttendance::with('student')->first();
        $this->assertNotNull($attendance);
        $this->assertNotNull($attendance->student);
        $this->assertEquals('Ahmad Pelajar', $attendance->student->student_name);
        $this->assertEquals('120001', $attendance->student->nis);
    }

    public function test_scan_barcode_duplicate_in_same_session_returns_already_checked(): void
    {
        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create([
            'classroom_id' => $classroom->id,
            'nis' => '120002',
            'student_name' => 'Budi Pelajar',
        ]);

        $payload = [
            'nis' => '120002',
            'session_type' => 'apel',
            'class' => $classroom->id,
            'date' => Carbon::today()->toDateString(),
        ];

        // First scan
        $this->postJson(route('presensi.scan'), $payload)->assertOk()->assertJson(['success' => true]);

        // Second scan in same session
        $responseDuplicate = $this->postJson(route('presensi.scan'), $payload);
        $responseDuplicate->assertOk()
            ->assertJson([
                'success' => false,
                'already_checked' => true,
            ]);

        $this->assertStringContainsString('sudah absen di sesi ini', $responseDuplicate->json('message'));
        $this->assertDatabaseCount('student_attendance_details', 1);
    }

    public function test_scan_barcode_with_student_from_different_class_is_rejected(): void
    {
        $classroomA = Classroom::factory()->create();
        $classroomB = Classroom::factory()->create();

        Student::factory()->create([
            'classroom_id' => $classroomA->id,
            'nis' => '120003',
            'student_name' => 'Citra Pelajar',
        ]);

        // Scan for Classroom B while student belongs to Classroom A
        $response = $this->postJson(route('presensi.scan'), [
            'nis' => '120003',
            'session_type' => 'apel',
            'class' => $classroomB->id,
            'date' => Carbon::today()->toDateString(),
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => false,
                'already_checked' => false,
            ]);

        $this->assertStringContainsString('bukan bagian dari kelas ini', $response->json('message'));
        $this->assertDatabaseCount('student_attendance_details', 0);
    }

    public function test_scan_barcode_with_non_existent_nis_returns_not_found_message(): void
    {
        $classroom = Classroom::factory()->create();

        $response = $this->postJson(route('presensi.scan'), [
            'nis' => '999999',
            'session_type' => 'apel',
            'class' => $classroom->id,
            'date' => Carbon::today()->toDateString(),
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => false,
                'student_name' => null,
            ]);

        $this->assertStringContainsString('tidak ditemukan', $response->json('message'));
    }

    public function test_scan_barcode_accepts_barcode_field_alias_and_defaults_date(): void
    {
        $classroom = Classroom::factory()->create();
        Student::factory()->create([
            'classroom_id' => $classroom->id,
            'nis' => '120004',
            'student_name' => 'Dedi Pelajar',
        ]);

        // Sent with 'barcode' instead of 'nis', omitting 'date'
        $response = $this->postJson(route('presensi.scan'), [
            'barcode' => "120004\n", // Simulating trailing newline from scanner
            'session_type' => 'apel',
            'class' => $classroom->id,
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'student_name' => 'Dedi Pelajar',
            ]);

        $this->assertDatabaseHas('student_attendance_details', [
            'status' => 'present',
        ]);
    }

    public function test_scan_list_returns_actual_student_name(): void
    {
        $classroom = Classroom::factory()->create();
        Student::factory()->create([
            'classroom_id' => $classroom->id,
            'nis' => '120005',
            'student_name' => 'Eka Pelajar',
        ]);

        $date = Carbon::today()->toDateString();

        // Scan first
        $this->postJson(route('presensi.scan'), [
            'nis' => '120005',
            'session_type' => 'apel',
            'class' => $classroom->id,
            'date' => $date,
        ])->assertOk();

        // Query scan-list
        $response = $this->getJson(route('presensi.scan-list', [
            'class' => $classroom->id,
            'session_type' => 'apel',
            'date' => $date,
        ]));

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'data' => [
                    [
                        'student_name' => 'Eka Pelajar',
                    ],
                ],
            ]);
    }

    public function test_student_card_view_renders_barcode_with_student_nis(): void
    {
        $operator = Operator::factory()->create();
        $classroom = Classroom::factory()->create(['name' => 'VII-A']);
        $student = Student::factory()->create([
            'classroom_id' => $classroom->id,
            'nis' => '120006',
            'student_name' => 'Farah Pelajar',
        ]);

        $response = $this->actingAs($operator, 'operator')->get(route('tu.student.card', $student));

        $response->assertOk();
        $response->assertSee('Farah Pelajar');
        $response->assertSee('120006');
        $response->assertSee('JsBarcode("#barcode", "120006"', false);
    }
}
