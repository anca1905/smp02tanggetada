<?php

namespace Tests\Unit\Teacher\Actions\Dashboard;

use App\Actions\Teacher\Dashboard\GetTeacherAbsenceHistoryAction;
use App\Models\Teacher;
use App\Models\TeacherAbsence;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetTeacherAbsenceHistoryActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_paginated_history()
    {
        $teacher = Teacher::factory()->create();
        $today = Carbon::today();

        TeacherAbsence::factory()->count(15)->create([
            'teacher_id' => $teacher->id,
            'date' => $today->format('Y-m-d'),
        ]);

        $action = new GetTeacherAbsenceHistoryAction;
        $result = $action->execute($teacher, $today->month, $today->year);

        $this->assertEquals(15, $result->total());
        $this->assertEquals(10, $result->perPage()); // default paginate(10)
    }
}
