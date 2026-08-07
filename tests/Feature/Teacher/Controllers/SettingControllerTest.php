<?php

namespace Tests\Feature\Teacher\Controllers;

use Tests\TestCase;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;

class SettingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_settings_validation_errors()
    {
        $teacher = Teacher::factory()->create([
            'password' => Hash::make('password123')
        ]);
        
        $response = $this->actingAs($teacher, 'teacher')
                         ->post(route('teacher.settings.update'), [
                             'name' => '', // Required
                             'username' => 'newuser',
                             'current_password' => 'wrongpass', // Wrong
                             'new_password' => 'newpass123',
                             'new_password_confirmation' => 'mismatch' // Mismatch
                         ]);

        $response->assertSessionHasErrors(['name', 'current_password', 'new_password']);
    }

    public function test_update_settings_success()
    {
        $teacher = Teacher::factory()->create([
            'password' => Hash::make('password123')
        ]);
        
        $response = $this->actingAs($teacher, 'teacher')
                         ->post(route('teacher.settings.update'), [
                             'name' => 'John Doe updated',
                             'username' => 'johndoe_update',
                             'current_password' => 'password123',
                         ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profil berhasil diperbarui!');
    }
}
