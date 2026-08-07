<?php

namespace Tests\Unit\Teacher\Actions\Teaching;

use Tests\TestCase;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\AcademicYear;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Actions\Teacher\Teaching\StoreMaterialAction;
use App\Actions\Teacher\Teaching\DeleteMaterialAction;
use App\Actions\Teacher\Teaching\StoreAssignmentAction;
use App\Actions\Teacher\Teaching\DeleteAssignmentAction;

class TeachingActionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_it_stores_pdf_material_and_uploads_file()
    {
        $schedule = Schedule::factory()->create();
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $action = new StoreMaterialAction();
        $material = $action->execute([
            'schedule_id' => $schedule->id,
            'title' => 'Materi Bab 1',
            'type' => 'pdf',
        ], $file);

        $this->assertEquals('pdf', $material->type);
        $this->assertEquals('document.pdf', $material->file_name);
        Storage::disk('public')->assertExists($material->file_path);
    }

    public function test_it_stores_youtube_link_material()
    {
        $schedule = Schedule::factory()->create();

        $action = new StoreMaterialAction();
        $material = $action->execute([
            'schedule_id' => $schedule->id,
            'title' => 'Materi Video',
            'type' => 'youtube',
            'url' => 'https://youtube.com/watch?v=12345'
        ], null);

        $this->assertEquals('youtube', $material->type);
        $this->assertEquals('https://youtube.com/watch?v=12345', $material->file_path);
    }

    public function test_it_deletes_material_and_removes_file()
    {
        $file = UploadedFile::fake()->create('doc.pdf', 100);
        $filePath = $file->store('materials', 'public');

        $material = Material::factory()->create([
            'type' => 'pdf',
            'file_path' => $filePath
        ]);

        Storage::disk('public')->assertExists($filePath);

        $action = new DeleteMaterialAction();
        $action->execute($material->id);

        $this->assertDatabaseMissing('materials', ['id' => $material->id]);
        Storage::disk('public')->assertMissing($filePath);
    }

    public function test_it_stores_assignment()
    {
        $teacher = Teacher::factory()->create();
        $classroom = Classroom::factory()->create();
        $subject = Subject::factory()->create();
        $file = UploadedFile::fake()->create('tugas.pdf', 100);

        $action = new StoreAssignmentAction();
        $assignment = $action->execute([
            'title' => 'Tugas 1',
            'due_date' => '2026-10-10',
            'classroom_id' => $classroom->id,
            'subject_id' => $subject->id,
        ], $teacher->id, $file);

        $this->assertEquals('Tugas 1', $assignment->title);
        Storage::disk('public')->assertExists($assignment->file_path);
    }

    public function test_it_deletes_assignment()
    {
        $file = UploadedFile::fake()->create('tugas.pdf', 100);
        $filePath = $file->store('assignments', 'public');

        $assignment = Assignment::factory()->create([
            'file_path' => $filePath
        ]);

        $action = new DeleteAssignmentAction();
        $action->execute($assignment->id);

        $this->assertDatabaseMissing('assignments', ['id' => $assignment->id]);
        Storage::disk('public')->assertMissing($filePath);
    }
}
