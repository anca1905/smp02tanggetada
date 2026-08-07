<?php

namespace Tests\Unit\Api\Actions\Billing;

use App\Actions\Api\Billing\GetStudentBillsAction;
use App\Models\Bill;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetStudentBillsActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_billing_summary_and_format_is_correct()
    {
        $student = Student::factory()->create();

        // 1 paid bill of 150.000, 2 unpaid bills of 100.000 and 50.000
        Bill::factory()->create([
            'student_id' => $student->id,
            'amount' => 150000,
            'status' => 'paid',
        ]);

        Bill::factory()->create([
            'student_id' => $student->id,
            'amount' => 100000,
            'status' => 'unpaid',
        ]);

        Bill::factory()->create([
            'student_id' => $student->id,
            'amount' => 50000,
            'status' => 'unpaid',
        ]);

        $action = new GetStudentBillsAction;
        $result = $action->execute($student);

        $this->assertTrue($result['success']);

        $summary = $result['data']['summary'];
        $this->assertEquals('Rp150.000', $summary['total_terbayar']);
        $this->assertEquals('Rp150.000', $summary['sisa_tagihan']);
        $this->assertEquals(2, $summary['tagihan_belum_dibayar']);
        $this->assertEquals(1, $summary['transaksi_berhasil']);
        $this->assertEquals('Ada Tunggakan', $summary['status']);

        $this->assertCount(2, $result['data']['active_bills']);
        $this->assertCount(1, $result['data']['history_bills']);
    }
}
