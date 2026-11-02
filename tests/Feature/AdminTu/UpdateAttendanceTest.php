<?php

namespace Tests\Feature\AdminTu;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Operator;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateAttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_tu_can_update_student_attendance_status(): void
    {
        $operator = Operator::factory()->create();
        $teacher = Teacher::factory()->create();
        $classroom = Classroom::factory()->create(['name' => 'VII A']);
        $student = Student::factory()->create([
            'classroom_id' => $classroom->id,
            'student_name' => 'Budi Santoso',
        ]);

        $attendance = Attendance::factory()->create([
            'teacher_id' => $teacher->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'session_type' => 'apel',
            'class' => $classroom->name,
        ]);

        $detail = StudentAttendance::create([
            'attendance_id' => $attendance->id,
            'student_id' => $student->id,
            'status' => 'absent',
        ]);

        $response = $this->actingAs($operator, 'operator')
            ->patch(route('tu.rekap.update', $detail), [
                'status' => 'permission',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Status presensi siswa berhasil diperbarui!');

        $this->assertDatabaseHas('student_attendance_details', [
            'id' => $detail->id,
            'status' => 'permission',
        ]);
    }

    public function test_admin_tu_can_update_status_to_bolos(): void
    {
        $operator = Operator::factory()->create();
        $teacher = Teacher::factory()->create();
        $classroom = Classroom::factory()->create(['name' => 'VIII B']);
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);

        $attendance = Attendance::factory()->create([
            'teacher_id' => $teacher->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'session_type' => 'kelas',
            'class' => $classroom->name,
        ]);

        $detail = StudentAttendance::create([
            'attendance_id' => $attendance->id,
            'student_id' => $student->id,
            'status' => 'present',
        ]);

        $response = $this->actingAs($operator, 'operator')
            ->patch(route('tu.rekap.update', $detail), [
                'status' => 'bolos',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('student_attendance_details', [
            'id' => $detail->id,
            'status' => 'bolos',
        ]);
    }

    public function test_update_requires_valid_status(): void
    {
        $operator = Operator::factory()->create();
        $teacher = Teacher::factory()->create();
        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);

        $attendance = Attendance::factory()->create([
            'teacher_id' => $teacher->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'session_type' => 'apel',
            'class' => $classroom->name,
        ]);

        $detail = StudentAttendance::create([
            'attendance_id' => $attendance->id,
            'student_id' => $student->id,
            'status' => 'absent',
        ]);

        $response = $this->actingAs($operator, 'operator')
            ->patch(route('tu.rekap.update', $detail), [
                'status' => 'invalid_status_xyz',
            ]);

        $response->assertSessionHasErrors('status');
        $this->assertDatabaseHas('student_attendance_details', [
            'id' => $detail->id,
            'status' => 'absent',
        ]);
    }

    public function test_guest_cannot_update_student_attendance(): void
    {
        $teacher = Teacher::factory()->create();
        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);

        $attendance = Attendance::factory()->create([
            'teacher_id' => $teacher->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'session_type' => 'apel',
            'class' => $classroom->name,
        ]);

        $detail = StudentAttendance::create([
            'attendance_id' => $attendance->id,
            'student_id' => $student->id,
            'status' => 'absent',
        ]);

        $response = $this->patch(route('tu.rekap.update', $detail), [
            'status' => 'present',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('student_attendance_details', [
            'id' => $detail->id,
            'status' => 'absent',
        ]);
    }
}
