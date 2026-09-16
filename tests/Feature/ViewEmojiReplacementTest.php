<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Operator;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewEmojiReplacementTest extends TestCase
{
    use RefreshDatabase;

    public function test_absenteeism_recap_does_not_contain_session_emojis_and_uses_fontawesome_icons(): void
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

        $response = $this->actingAs($operator, 'operator')->get(route('tu.rekap'));

        $response->assertOk();
        $response->assertDontSee('🌅');
        $response->assertDontSee('🏫');
        $response->assertDontSee('🏠');
        $response->assertSee('fa-sun');
        $response->assertSee('Apel Pagi');
    }

    public function test_presence_kiosk_view_does_not_contain_session_emojis(): void
    {
        $response = $this->get(route('presensi.index'));

        $response->assertOk();
        $response->assertDontSee('🌅');
        $response->assertDontSee('🏫');
        $response->assertDontSee('🏠');
        $response->assertSee('Apel Pagi');
        $response->assertSee('Di Kelas');
        $response->assertSee('Pulang');
    }

    public function test_student_presence_view_does_not_contain_session_emojis(): void
    {
        $teacher = Teacher::factory()->create();

        $response = $this->actingAs($teacher, 'teacher')->get(route('teacher.student-attendance'));

        $response->assertOk();
        $response->assertDontSee('🌅');
        $response->assertDontSee('🏫');
        $response->assertDontSee('🏠');
        $response->assertSee('Apel Pagi');
        $response->assertSee('Di Kelas');
        $response->assertSee('Pulang');
    }

    public function test_dashboard_greetings_do_not_contain_wave_emoji(): void
    {
        $operator = Operator::factory()->create();
        $tuResponse = $this->actingAs($operator, 'operator')->get(route('tu.dashboard'));
        $tuResponse->assertOk();
        $tuResponse->assertDontSee('👋');

        $teacher = Teacher::factory()->create();
        $teacherResponse = $this->actingAs($teacher, 'teacher')->get(route('teacher.dashboard'));
        $teacherResponse->assertOk();
        $teacherResponse->assertDontSee('👋');

        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);
        $studentResponse = $this->actingAs($student, 'student')->get('/student/lms');
        $studentResponse->assertOk();
        $studentResponse->assertDontSee('👋');
    }
}
