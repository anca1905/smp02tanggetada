<?php

namespace Tests\Unit\Teacher\Actions\Dashboard;

use App\Actions\Teacher\Dashboard\GetTeacherDashboardStatsAction;
use App\Models\Teacher;
use App\Models\Teacher_absence;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetTeacherDashboardStatsActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_correct_stats()
    {
        $teacher = Teacher::factory()->create();

        $today = Carbon::today();

        Teacher_absence::factory()->create([
            'teacher_id' => $teacher->id,
            'date' => $today->format('Y-m-d'),
            'arrival_time' => '07:00:00',
            'return_time' => '14:00:00',
        ]);

        $action = new GetTeacherDashboardStatsAction;
        $result = $action->execute($teacher, $today->month, $today->year);

        $this->assertArrayHasKey('status_datang', $result);
        $this->assertArrayHasKey('status_pulang', $result);
        $this->assertArrayHasKey('totalHadir', $result);
        $this->assertArrayHasKey('chartDataDatang', $result);

        $this->assertStringContainsString('Sudah Absen', $result['status_datang']);
        $this->assertStringContainsString('Sudah Absen', $result['status_pulang']);
        $this->assertEquals(1, $result['totalHadir']);
    }
}
