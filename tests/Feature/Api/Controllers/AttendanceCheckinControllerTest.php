<?php

namespace Tests\Feature\Api\Controllers;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceCheckinControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkin_endpoint_valid_token()
    {
        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);

        Attendance::factory()->create([
            'qr_token' => 'valid-qr-code',
            'qr_expires_at' => Carbon::now()->addMinutes(10),
            'class' => $classroom->id,
            'date' => Carbon::today()->toDateString(),
        ]);

        $response = $this->actingAs($student)->postJson('/api/student/attendance/checkin', [
            'qr_token' => 'valid-qr-code',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_checkin_endpoint_invalid_token()
    {
        $student = Student::factory()->create();

        $response = $this->actingAs($student)->postJson('/api/student/attendance/checkin', [
            'qr_token' => 'non-existent-token',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }
}
