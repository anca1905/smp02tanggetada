<?php

namespace Tests\Unit\Student\Actions\Learning;

use App\Actions\Student\Learning\SubmitStudentAssignmentAction;
use App\Models\Assignment;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SubmitStudentAssignmentActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_saves_file_and_creates_submission_record()
    {
        Storage::fake('public');

        $student = Student::factory()->create();
        Auth::guard('student')->login($student);

        $assignment = Assignment::factory()->create();

        $file = UploadedFile::fake()->create('document.pdf', 1000);

        $data = [
            'assignment_id' => $assignment->id,
            'note' => 'Ini tugas saya',
        ];

        $action = new SubmitStudentAssignmentAction;
        $action->execute($data, $file);

        $this->assertDatabaseHas('assignment_submissions', [
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'student_note' => 'Ini tugas saya',
        ]);

        $files = Storage::disk('public')->files('submissions');
        $this->assertCount(1, $files);
    }
}
