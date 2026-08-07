<?php

namespace Tests\Unit\Teacher\Actions\Presence;

use App\Actions\Teacher\Presence\SearchTeacherForPresenceAction;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTeacherForPresenceActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_empty_if_query_less_than_3_chars()
    {
        $action = new SearchTeacherForPresenceAction;
        $result = $action->execute('ab');

        $this->assertEmpty($result);
    }

    public function test_it_returns_active_teachers_matching_query()
    {
        Teacher::factory()->create(['name' => 'Budi Santoso', 'status' => 'Active', 'employee_id' => 'EMP-1111']);
        Teacher::factory()->create(['name' => 'Andi Wijaya', 'status' => 'Active', 'employee_id' => 'EMP-2222']);
        Teacher::factory()->create(['name' => 'Budi Inactive', 'status' => 'Inactive', 'employee_id' => 'EMP-3333']);

        $action = new SearchTeacherForPresenceAction;

        $result = $action->execute('Budi');

        $this->assertCount(2, $result);
        $this->assertEquals('Budi Santoso', $result[0]->name);
    }
}
