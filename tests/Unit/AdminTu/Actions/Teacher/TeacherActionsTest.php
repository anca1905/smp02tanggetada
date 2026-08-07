<?php

namespace Tests\Unit\AdminTu\Actions\Teacher;

use Tests\TestCase;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Actions\Teacher\CreateTeacherAction;
use App\Actions\Teacher\DeleteTeacherAction;

class TeacherActionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_it_creates_teacher_with_photo()
    {
        $file = UploadedFile::fake()->image('avatar.jpg');
        
        $action = new CreateTeacherAction();
        $teacher = $action->execute([
            'name' => 'Bapak Guru',
            'gender' => 'Male',
            'employee_id' => 'EMP-111',
            'phone' => '081111',
            'subject' => 'IPA',
            'username' => 'bapakguru',
            'password' => 'password123'
        ], $file);

        $this->assertEquals('Bapak Guru', $teacher->name);
        $this->assertNotNull($teacher->photo_url);
    }

    public function test_it_deletes_teacher_and_photo()
    {
        $file = UploadedFile::fake()->image('avatar.jpg');
        $filePath = $file->store('img/guru', 'public');

        $teacher = Teacher::factory()->create([
            'photo_url' => $filePath
        ]);

        Storage::disk('public')->assertExists($filePath);

        $action = new DeleteTeacherAction();
        $action->execute($teacher);

        $this->assertDatabaseMissing('teachers', ['id' => $teacher->id]);
    }
}
