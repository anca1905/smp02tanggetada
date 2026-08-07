<?php

namespace Tests\Unit\Api\Actions\Student;

use App\Actions\Api\Student\GetStudentDashboardAction;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Post;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\StudentAttendanceDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetStudentDashboardActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_returns_correct_structure()
    {
        $student = Student::factory()->create();

        Schedule::factory()->create(['classroom_id' => $student->classroom_id]);
        Assignment::factory()->create(['classroom_id' => $student->classroom_id]);

        $attendance = Attendance::factory()->create(['class' => $student->classroom_id]);
        StudentAttendanceDetail::factory()->create([
            'attendance_id' => $attendance->id,
            'student_id' => $student->id,
            'status' => 'present'
        ]);

        Post::factory()->create(['is_published' => true]);

        $action = new GetStudentDashboardAction;
        $result = $action->execute($student);

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('student', $result['data']);
        $this->assertArrayHasKey('today_schedules', $result['data']);
        $this->assertArrayHasKey('upcoming_assignments', $result['data']);
        $this->assertArrayHasKey('attendance_percentage', $result['data']);
        $this->assertArrayHasKey('announcements', $result['data']);

        $this->assertEquals(100, $result['data']['attendance_percentage']);
    }
}
