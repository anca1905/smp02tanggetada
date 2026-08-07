<?php

namespace Tests\Unit\Teacher\Actions\Setting;

use Tests\TestCase;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Teacher\Setting\UpdateTeacherSettingAction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateTeacherSettingActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_teacher_settings_without_photo_and_password()
    {
        $teacher = Teacher::factory()->create(['name' => 'Old Name', 'username' => 'olduser']);
        
        $action = new UpdateTeacherSettingAction();
        $action->execute($teacher, [
            'name' => 'New Name',
            'username' => 'newuser',
            'new_password' => null
        ], null);

        $this->assertEquals('New Name', $teacher->fresh()->name);
        $this->assertEquals('newuser', $teacher->fresh()->username);
    }

    public function test_it_updates_password()
    {
        $teacher = Teacher::factory()->create(['password' => Hash::make('oldpassword')]);
        
        $action = new UpdateTeacherSettingAction();
        $action->execute($teacher, [
            'name' => $teacher->name,
            'username' => $teacher->username,
            'new_password' => 'newpassword123'
        ], null);

        $this->assertTrue(Hash::check('newpassword123', $teacher->fresh()->password));
    }
}
