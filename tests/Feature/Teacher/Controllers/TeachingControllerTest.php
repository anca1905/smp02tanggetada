<?php

namespace Tests\Feature\Teacher\Controllers;

use Tests\TestCase;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\AcademicYear;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TeachingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_teacher_cannot_access_other_teacher_course()
    {
        $teacher1 = Teacher::factory()->create();
        $teacher2 = Teacher::factory()->create();
        
        $schedule = Schedule::factory()->create(['teacher_id' => $teacher2->id]);

        $response = $this->actingAs($teacher1, 'teacher')
                         ->get(route('teacher.lms.show', $schedule->id));

        $response->assertStatus(403);
    }

    public function test_upload_material_size_validation()
    {
        $teacher = Teacher::factory()->create();
        $schedule = Schedule::factory()->create(['teacher_id' => $teacher->id]);
        
        // Buat file PDF palsu sebesar 15MB (limit 10MB)
        $largeFile = UploadedFile::fake()->create('large.pdf', 15360); 

        $response = $this->actingAs($teacher, 'teacher')
                         ->post(route('teacher.lms.material.store'), [
                             'schedule_id' => $schedule->id,
                             'title' => 'Materi Besar',
                             'type' => 'pdf',
                             'file' => $largeFile
                         ]);

        $response->assertSessionHasErrors(['file']);
    }
}
