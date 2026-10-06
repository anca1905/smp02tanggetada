<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TeacherApiTest extends TestCase
{
    protected Teacher $teacher;

    protected Classroom $classroom;

    protected Student $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teacher = Teacher::firstOrCreate(
            ['username' => 'guru_test'],
            [
                'name' => 'Guru Test Mobile',
                'password' => Hash::make('password123'),
                'gender' => 'Male',
                'employee_id' => '198001012005011001',
                'phone' => '081234567890',
                'status' => 'Active',
            ]
        );

        $academicYear = \App\Models\AcademicYear::firstOrCreate(
            ['name' => '2026/2027', 'semester' => 'Ganjil'],
            ['is_active' => true]
        );

        $this->classroom = Classroom::firstOrCreate(
            ['name' => 'Test Class 7A'],
            [
                'level' => '7',
                'teacher_id' => $this->teacher->id,
                'academic_year_id' => $academicYear->id,
            ]
        );

        $this->student = Student::firstOrCreate(
            ['nis' => '999991'],
            [
                'student_name' => 'Siswa Test Mobile',
                'classroom_id' => $this->classroom->id,
                'password' => Hash::make('password123'),
                'gender' => 'M',
                'student_status' => 'Active',
            ]
        );
    }

    public function test_teacher_login_fails_with_wrong_password(): void
    {
        $response = $this->postJson('/api/teacher/login', [
            'username' => 'guru_test',
            'password' => 'wrongpass',
        ]);

        $response->assertStatus(401)
            ->assertJson(['success' => false]);
    }

    public function test_teacher_login_succeeds_and_returns_token(): void
    {
        $response = $this->postJson('/api/teacher/login', [
            'username' => 'guru_test',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'teacher' => ['id', 'name', 'username'],
                    'token',
                ],
            ]);
    }

    public function test_teacher_can_fetch_classes_and_students(): void
    {
        $token = $this->teacher->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/teacher/classes');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $studentsResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson("/api/teacher/students?class_id={$this->classroom->id}");

        $studentsResponse->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_teacher_can_sync_attendance(): void
    {
        $token = $this->teacher->createToken('test_token')->plainTextToken;

        $payload = [
            'batches' => [
                [
                    'class' => $this->classroom->id,
                    'session_type' => 'apel',
                    'date' => now()->toDateString(),
                    'attendance' => [
                        $this->student->nis => ['status' => 'present'],
                    ],
                ],
            ],
        ];

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/teacher/attendance/sync', $payload);

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'synced_count' => 1]);
    }

    public function test_teacher_can_fetch_announcements(): void
    {
        $token = $this->teacher->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/teacher/announcements');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_teacher_can_fetch_notifications(): void
    {
        $token = $this->teacher->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/teacher/notifications');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'title', 'message', 'type', 'created_at'],
                ],
            ]);
    }

    protected function tearDown(): void
    {
        // Cleanup test tokens
        $this->teacher->tokens()->delete();
        parent::tearDown();
    }
}
