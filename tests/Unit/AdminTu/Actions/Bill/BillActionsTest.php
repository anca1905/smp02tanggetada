<?php

namespace Tests\Unit\AdminTu\Actions\Bill;

use App\Actions\Bill\GenerateBillsAction;
use App\Actions\Bill\MarkBillAsPaidAction;
use App\Models\Bill;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_monthly_bills()
    {
        Student::factory()->count(3)->create(['student_status' => 'Active']);
        Student::factory()->create(['student_status' => 'Inactive']); // Should not generate for inactive

        $action = new GenerateBillsAction;
        $action->execute([
            'month' => 'Agustus 2026',
            'amount' => 500000,
            'due_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
        ]);

        $this->assertEquals(4, Bill::count());
        $this->assertEquals('SPP Agustus 2026', Bill::first()->title);
    }

    public function test_mark_bill_as_paid()
    {
        $bill = Bill::factory()->create(['status' => 'unpaid']);

        $action = new MarkBillAsPaidAction;
        $action->execute($bill);

        $this->assertEquals('paid', $bill->fresh()->status);
    }
}
