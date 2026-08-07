<?php

namespace Tests\Unit\AdminTu\Actions\Academic;

use App\Actions\Academic\SetActiveAcademicYearAction;
use App\Models\AcademicYear;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetActiveAcademicYearActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sets_academic_year_as_active_and_deactivates_others()
    {
        $year1 = AcademicYear::factory()->create(['is_active' => true]);
        $year2 = AcademicYear::factory()->create(['is_active' => false]);

        $action = new SetActiveAcademicYearAction;
        $action->execute($year2);

        $this->assertFalse($year1->fresh()->is_active);
        $this->assertTrue($year2->fresh()->is_active);
    }
}
