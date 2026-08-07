<?php

namespace Tests\Unit\Api\Actions\Attendance;

use App\Actions\Api\Attendance\ProcessQrCheckinAction;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\StudentAttendanceDetail;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessQrCheckinActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_qr_checkin_success_present()
    {
        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);

        $attendance = Attendance::factory()->create([
            'qr_token' => 'valid-token',
            'qr_expires_at' => Carbon::now()->addMinutes(5),
            'class' => $classroom->id,
            'date' => Carbon::today()->toDateString(),
            'start_time' => Carbon::now()->subMinutes(10)->toTimeString(),
        ]);

        $action = new ProcessQrCheckinAction;
        $result = $action->execute($student, 'valid-token');

        $this->assertTrue($result['success']);
        $this->assertFalse($result['already_checked']);
        $this->assertEquals(200, $result['status_code']);
        $this->assertEquals('present', $result['data']->status);
    }

    public function test_qr_checkin_success_late()
    {
        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);

        $attendance = Attendance::factory()->create([
            'qr_token' => 'valid-token',
            'qr_expires_at' => Carbon::now()->addMinutes(5),
            'class' => $classroom->id,
            'date' => Carbon::today()->toDateString(),
            'start_time' => Carbon::now()->subMinutes(20)->toTimeString(),
        ]);

        $action = new ProcessQrCheckinAction;
        $result = $action->execute($student, 'valid-token');

        $this->assertTrue($result['success']);
        $this->assertFalse($result['already_checked']);
        $this->assertEquals(200, $result['status_code']);
        $this->assertEquals('late', $result['data']->status);
    }

    public function test_qr_checkin_fails_expired()
    {
        $student = Student::factory()->create();

        Attendance::factory()->create([
            'qr_token' => 'expired-token',
            'qr_expires_at' => Carbon::now()->subMinutes(5),
        ]);

        $action = new ProcessQrCheckinAction;
        $result = $action->execute($student, 'expired-token');

        $this->assertFalse($result['success']);
        $this->assertEquals(422, $result['status_code']);
        $this->assertStringContainsString('kadaluarsa', $result['message']);
    }

    public function test_qr_checkin_fails_wrong_class()
    {
        $studentClassroom = Classroom::factory()->create();
        $student = Student::factory()->create(['classroom_id' => $studentClassroom->id]);

        $otherClassroom = Classroom::factory()->create();
        Attendance::factory()->create([
            'qr_token' => 'valid-token',
            'qr_expires_at' => Carbon::now()->addMinutes(5),
            'class' => $otherClassroom->id,
        ]);

        $action = new ProcessQrCheckinAction;
        $result = $action->execute($student, 'valid-token');

        $this->assertFalse($result['success']);
        $this->assertEquals(403, $result['status_code']);
    }

    public function test_qr_checkin_already_checked_in()
    {
        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);

        $attendance = Attendance::factory()->create([
            'qr_token' => 'valid-token',
            'qr_expires_at' => Carbon::now()->addMinutes(5),
            'class' => $classroom->id,
        ]);

        StudentAttendanceDetail::factory()->create([
            'attendance_id' => $attendance->id,
            'student_id' => $student->id,
            'status' => 'present',
        ]);

        $action = new ProcessQrCheckinAction;
        $result = $action->execute($student, 'valid-token');

        $this->assertTrue($result['success']);
        $this->assertTrue($result['already_checked']);
        $this->assertEquals(200, $result['status_code']);
    }
}
