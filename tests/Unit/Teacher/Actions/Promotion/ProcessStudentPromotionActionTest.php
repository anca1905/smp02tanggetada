<?php

namespace Tests\Unit\Teacher\Actions\Promotion;

use Tests\TestCase;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Teacher\Promotion\ProcessStudentPromotionAction;

class ProcessStudentPromotionActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_processes_student_promotions()
    {
        $oldClass = Classroom::factory()->create(['name' => '10A']);
        $newClass = Classroom::factory()->create(['name' => '11A']);
        
        $student1 = Student::factory()->create(['nis' => '120001', 'classroom_id' => $oldClass->id]);
        $student2 = Student::factory()->create(['nis' => '120002', 'classroom_id' => $oldClass->id]);

        $action = new ProcessStudentPromotionAction();
        
        $data = [
            'next_classroom_id' => $newClass->id,
            'action' => [
                '120001' => 'Naik',
                '120002' => 'Tinggal'
            ]
        ];

        $action->execute($data);

        $this->assertEquals($newClass->id, $student1->fresh()->classroom_id);
        $this->assertEquals($oldClass->id, $student2->fresh()->classroom_id);
    }
}
