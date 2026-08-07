<?php

namespace Tests\Unit\AdminTu\Actions\Recap;

use Tests\TestCase;
use App\Models\Teacher;
use App\Models\Teacher_absence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Recap\GetTeacherRecapAction;
use Carbon\Carbon;

class GetRecapDataActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_monthly_recap_data_for_teachers()
    {
        $teacher = Teacher::factory()->create();
        $date = Carbon::create(2026, 8, 10); // Aug 10, 2026
        
        Teacher_absence::factory()->create([
            'teacher_id' => $teacher->id,
            'date' => $date->format('Y-m-d')
        ]);

        $action = new GetTeacherRecapAction();
        
        $result = $action->execute(8, 2026, null); 
        $this->assertNotNull($result);
    }
}
