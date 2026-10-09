<?php

namespace Tests\Unit\Teacher\Actions;

use App\Actions\Teacher\Dashboard\GetTeacherDashboardStatsAction;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetTeacherDashboardStatsActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_all_required_teacher_dashboard_keys(): void
    {
        $teacher = Teacher::factory()->create();

        $action = new GetTeacherDashboardStatsAction;
        $result = $action->execute($teacher, Carbon::today()->month, Carbon::today()->year);

        $this->assertArrayHasKey('totalKelasDiampu', $result);
        $this->assertArrayHasKey('totalSiswaDiajar', $result);
        $this->assertArrayHasKey('totalJamMengajar', $result);
        $this->assertArrayHasKey('todaySchedules', $result);
        $this->assertArrayHasKey('weeklyScheduleData', $result);
        $this->assertArrayHasKey('announcements', $result);
        $this->assertArrayHasKey('todayFormatted', $result);
        $this->assertCount(6, $result['weeklyDays']);
        $this->assertCount(6, $result['weeklyScheduleData']);
    }

    public function test_it_calculates_taught_classrooms_and_students_correctly(): void
    {
        $teacher = Teacher::factory()->create();
        $subject = Subject::factory()->create();
        $classroom = Classroom::factory()->create(['name' => 'VII-A']);

        Student::factory()->count(5)->create([
            'classroom_id' => $classroom->id,
            'student_status' => 'Active',
        ]);

        Schedule::factory()->create([
            'teacher_id' => $teacher->id,
            'classroom_id' => $classroom->id,
            'subject_id' => $subject->id,
            'day' => 'Senin',
        ]);

        $action = new GetTeacherDashboardStatsAction;
        $result = $action->execute($teacher, Carbon::today()->month, Carbon::today()->year);

        $this->assertEquals(1, $result['totalKelasDiampu']);
        $this->assertEquals(5, $result['totalSiswaDiajar']);
        $this->assertEquals(1, $result['totalJamMengajar']);
        $this->assertEquals(1, $result['weeklyScheduleData'][0]); // Senin
    }
}
