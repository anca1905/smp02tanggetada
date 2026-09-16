<?php

namespace Tests\Unit\Teacher\Actions\StudentPresence;

use App\Actions\Teacher\StudentPresence\StoreStudentAttendanceAction;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class StoreStudentAttendanceActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_attendance_header_and_details()
    {
        $teacher = Teacher::factory()->create();
        Auth::guard('teacher')->login($teacher);

        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create([
            'classroom_id' => $classroom->id,
            'nis' => '12345',
        ]);

        $action = new StoreStudentAttendanceAction;
        $action->execute([
            'class' => $classroom->id,
            'session_type' => 'kelas',
            'date' => Carbon::today()->format('Y-m-d'),
            'attendance' => [
                '12345' => ['status' => 'present'],
            ],
        ]);

        $this->assertDatabaseHas('attendances', [
            'class' => $classroom->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'teacher_id' => $teacher->id,
        ]);

        $header = Attendance::first();

        $this->assertDatabaseHas('student_attendance_details', [
            'attendance_id' => $header->id,
            'status' => 'present',
            'student_id' => $student->id,
        ]);
    }
}
