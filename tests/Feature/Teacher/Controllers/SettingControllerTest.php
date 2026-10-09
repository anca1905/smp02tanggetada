<?php

namespace Tests\Feature\Teacher\Controllers;

use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SettingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_settings_validation_errors()
    {
        $teacher = Teacher::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($teacher, 'teacher')
            ->post(route('teacher.settings.update'), [
                'name' => '', // Required
                'username' => 'newuser',
                'current_password' => 'wrongpass', // Wrong
                'new_password' => 'newpass123',
                'new_password_confirmation' => 'mismatch', // Mismatch
            ]);

        $response->assertSessionHasErrors(['name', 'current_password', 'new_password']);
    }

    public function test_update_settings_success()
    {
        $teacher = Teacher::factory()->create([
            'password' => Hash::make('password123'),
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

    public function test_index_displays_employee_id_and_homeroom_classroom(): void
    {
        $teacher = Teacher::factory()->create([
            'employee_id' => '198701012010011002',
            'name' => 'Dewi Sarmila, S.Pd., Gr.',
        ]);
        \App\Models\Classroom::factory()->create([
            'name' => 'VIII A',
            'teacher_id' => $teacher->id,
        ]);

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.settings'));

        $response->assertStatus(200);
        $response->assertSee('198701012010011002');
        $response->assertSee('VIII A');
    }
}
