<?php

namespace Tests\Feature\AdminTu\Controllers;

use App\Models\Operator;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeacherControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_it_displays_teachers_list()
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        Teacher::factory()->count(3)->create();

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.teacher.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tu.teacher_data');
    }

    public function test_it_stores_new_teacher()
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($operator, 'operator')
            ->post(route('tu.teacher.store'), [
                'name' => 'Budi Baru',
                'gender' => 'Male',
                'employee_id' => 'EMP-777',
                'phone' => '081234567890',
                'subject' => 'IPA',
                'status' => 'Active',
                'username' => 'budibaru',
                'password' => 'password123',
                'photo_url' => $file,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('teachers', ['name' => 'Budi Baru']);
    }

    public function test_it_deletes_teacher()
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        $teacher = Teacher::factory()->create();

        $response = $this->actingAs($operator, 'operator')
            ->delete(route('tu.teacher.destroy', $teacher->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('teachers', ['id' => $teacher->id]);
    }
}
