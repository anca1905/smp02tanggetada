<?php

namespace Tests\Feature\Api\Controllers;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentApiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_endpoint_authenticated()
    {
        $student = Student::factory()->create();

        $response = $this->actingAs($student)->getJson('/api/student/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'student',
                    'today_schedules',
                    'upcoming_assignments',
                    'attendance_percentage',
                    'announcements',
                ],
            ]);
    }

    public function test_dashboard_endpoint_unauthenticated()
    {
        $response = $this->getJson('/api/student/dashboard');

        $response->assertStatus(401);
    }

    public function test_submit_assignment_endpoint_valid()
    {
        Storage::fake('public');

        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);
        $assignment = Assignment::factory()->create(['classroom_id' => $classroom->id]);

        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($student)->postJson("/api/student/assignments/{$assignment->id}/submit", [
            'file' => $file,
            'student_note' => 'My assignment',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Assignment submitted successfully.',
            ]);

        $this->assertDatabaseHas('assignment_submissions', [
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'student_note' => 'My assignment',
        ]);
    }

    public function test_submit_assignment_unauthorized_classroom()
    {
        $studentClassroom = Classroom::factory()->create();
        $student = Student::factory()->create(['classroom_id' => $studentClassroom->id]);

        $otherClassroom = Classroom::factory()->create();
        $assignment = Assignment::factory()->create(['classroom_id' => $otherClassroom->id]);

        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($student)->postJson("/api/student/assignments/{$assignment->id}/submit", [
            'file' => $file,
        ]);

        $response->assertStatus(403);
    }
}
