<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\Billing\GetStudentBillsAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillingApiController extends Controller
{
    /**
     * Get a student's bills.
     */
    public function getStudentBills(
        Request $request,
        GetStudentBillsAction $action,
    ) {
        $result = $action->execute(
            $request->user(),
            $request->input('student_id'),
        );
        $status = $result['status_code'] ?? 200;
        unset($result['status_code']);

        return response()->json($result, $status);
    }
}
