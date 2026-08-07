<?php

namespace Tests\Feature\Api\Controllers;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_login_endpoint()
    {
        $student = Student::factory()->create([
            'nis' => '120001',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/student/login', [
            'nis' => '120001',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'student',
                    'token',
                ],
            ]);
    }

    public function test_student_login_endpoint_validation_error()
    {
        $response = $this->postJson('/api/student/login', [
            'nis' => '120001',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_parent_login_endpoint()
    {
        Student::factory()->create([
            'nis' => '120001',
            'parent_password' => Hash::make('ortu120001'),
        ]);

        $response = $this->postJson('/api/student/parent-login', [
            'nis' => '120001',
            'password' => 'ortu120001',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'is_parent' => true,
                ],
            ]);
    }

    public function test_logout_endpoint()
    {
        $student = Student::factory()->create();
        $token = $student->createToken('test_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/student/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logout berhasil',
            ]);

        $this->assertCount(0, $student->tokens);
    }
}
