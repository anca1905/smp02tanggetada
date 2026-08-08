<?php

namespace Tests\Feature\Student\Controllers;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LearningControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_without_class_sees_no_class_view()
    {
        $student = Student::factory()->create(['classroom_id' => null]);

        $response = $this->actingAs($student, 'student')->get('/student/lms');

        $response->assertStatus(200);
        $response->assertSee('Anda belum terdaftar di kelas apapun.');
    }

    public function test_student_can_access_learning_page()
    {
        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);

        $response = $this->actingAs($student, 'student')->get('/student/lms');

        $response->assertStatus(200);
        $response->assertViewIs('student.lms.index');
    }

    public function test_student_cannot_access_other_class_course()
    {
        $classroom1 = Classroom::factory()->create();
        $classroom2 = Classroom::factory()->create();

        $student = Student::factory()->create(['classroom_id' => $classroom1->id]);

        $schedule = Schedule::factory()->create([
            'classroom_id' => $classroom2->id,
            'subject_id' => Subject::factory()->create()->id,
            'teacher_id' => Teacher::factory()->create()->id,
        ]);

        $response = $this->actingAs($student, 'student')->get('/student/lms/'.$schedule->id);

        $response->assertStatus(403);
    }

    public function test_submit_assignment_success()
    {
        Storage::fake('public');

        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);
        $assignment = Assignment::factory()->create(['classroom_id' => $classroom->id]);

        $file = UploadedFile::fake()->create('document.pdf', 1000);

        $response = $this->actingAs($student, 'student')->post('/student/lms/assignment/submit', [
            'assignment_id' => $assignment->id,
            'file' => $file,
            'note' => 'Ini catatan',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }
}
