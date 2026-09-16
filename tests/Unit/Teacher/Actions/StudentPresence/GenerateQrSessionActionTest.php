<?php

namespace Tests\Unit\Teacher\Actions\StudentPresence;

use App\Actions\Teacher\StudentPresence\GenerateQrSessionAction;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class GenerateQrSessionActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_qr_token_and_sets_expiry()
    {
        $teacher = Teacher::factory()->create();
        Auth::guard('teacher')->login($teacher);

        $classroom = Classroom::factory()->create();
        $subject = Subject::factory()->create();

        $action = new GenerateQrSessionAction;
        $result = $action->execute([
            'class' => $classroom->id,
            'session_type' => 'kelas',
            'subject_id' => $subject->id,
            'date' => Carbon::today()->format('Y-m-d'),
        ]);

        $this->assertTrue($result['success']);
        $this->assertNotEmpty($result['qr_token']);
        $this->assertEquals(32, strlen($result['qr_token']));
        $this->assertArrayHasKey('expires_at', $result);
        $this->assertEquals($subject->id, $result['subject_id']);

        $this->assertDatabaseHas('attendances', [
            'class' => $classroom->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
            'qr_token' => $result['qr_token'],
        ]);
    }
}
