<?php

namespace Tests\Unit\Principal\Actions;

use App\Actions\Principal\GetPrincipalDashboardAction;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetPrincipalDashboardActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_all_required_dashboard_keys()
    {
        Teacher::factory()->count(3)->create(['status' => 'Active']);

        $action = new GetPrincipalDashboardAction;
        $result = $action->execute();

        $this->assertArrayHasKey('totalGuru', $result);
        $this->assertArrayHasKey('totalSiswa', $result);
        $this->assertArrayHasKey('attendanceChart', $result);
        $this->assertArrayHasKey('topTeachers', $result);
        $this->assertArrayHasKey('studentPerClass', $result);

        $this->assertEquals(3, $result['totalGuru']);

        $this->assertCount(6, $result['attendanceChart']);
        $this->assertLessThanOrEqual(5, $result['topTeachers']->count());
    }
}
