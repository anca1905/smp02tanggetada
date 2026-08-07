<?php

namespace Tests\Feature\Teacher\Controllers;

use Tests\TestCase;
use App\Models\Teacher;
use App\Models\AcademicYear;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get(route('teacher.dashboard'));
        $response->assertRedirect('/login');
    }

    public function test_teacher_can_access_dashboard()
    {
        $teacher = Teacher::factory()->create();
        
        $response = $this->actingAs($teacher, 'teacher')
                         ->get(route('teacher.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('teacher.index');
        $response->assertSee($teacher->name);
    }
}
