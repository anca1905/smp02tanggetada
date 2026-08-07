<?php

namespace Tests\Unit\Teacher\Actions\Presence;

use Tests\TestCase;
use App\Models\Teacher;
use App\Models\Teacher_absence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use App\Actions\Teacher\Presence\StoreTeacherPresenceAction;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StoreTeacherPresenceActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_it_returns_error_if_teacher_not_found()
    {
        $action = new StoreTeacherPresenceAction();
        $result = $action->execute([
            'identity' => 'Unknown',
            'password' => 'password',
            'image' => 'data:image/jpeg;base64,dummy'
        ]);

        $this->assertEquals('error', $result['status']);
        $this->assertEquals('Data guru tidak ditemukan.', $result['message']);
    }

    public function test_it_returns_error_if_password_incorrect()
    {
        $teacher = Teacher::factory()->create(['name' => 'Budi', 'password' => Hash::make('password123')]);

        $action = new StoreTeacherPresenceAction();
        $result = $action->execute([
            'identity' => 'Budi',
            'password' => 'wrongpassword',
            'image' => 'data:image/jpeg;base64,dummy'
        ]);

        $this->assertEquals('error', $result['status']);
        $this->assertEquals('Password salah. Silakan coba lagi.', $result['message']);
    }

    public function test_it_stores_arrival_presence_successfully()
    {
        $teacher = Teacher::factory()->create(['name' => 'Budi', 'password' => Hash::make('password123')]);
        
        $action = new StoreTeacherPresenceAction();
        $result = $action->execute([
            'identity' => 'Budi',
            'password' => 'password123',
            'image' => 'data:image/jpeg;base64,dummybase64image'
        ]);

        $this->assertEquals('success', $result['status']);
        
        $this->assertDatabaseHas('teacher_attendances', [
            'teacher_id' => $teacher->id,
            'date' => Carbon::today()->format('Y-m-d'),
        ]);
        
        // Assert absence returned doesn't have return_time
        $absence = Teacher_absence::where('teacher_id', $teacher->id)->first();
        $this->assertNotNull($absence->arrival_time);
        $this->assertNull($absence->return_time);
    }
}
