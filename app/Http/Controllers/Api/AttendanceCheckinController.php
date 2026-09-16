<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\Attendance\GetCheckinListAction;
use App\Actions\Api\Attendance\GetSessionInfoAction;
use App\Actions\Api\Attendance\ProcessQrCheckinAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\QrCheckinRequest;
use Illuminate\Http\Request;

class AttendanceCheckinController extends Controller
{
    /**
     * Siswa scan QR lalu check-in.
     * POST /api/student/attendance/checkin
     * Body: { qr_token: "xxx" }
     */
    public function checkin(
        QrCheckinRequest $request,
        ProcessQrCheckinAction $action,
    ) {
        $result = $action->execute($request->user(), $request->qr_token);
        $status = $result['status_code'] ?? 200;
        unset($result['status_code']);

        return response()->json($result, $status);
    }

    /**
     * Daftar siswa yang sudah check-in untuk sesi tertentu.
     * GET /api/student/attendance/checkin-list?class=X&date=Y
     * (Bisa diakses tanpa auth – hanya untuk display di web guru)
     */
    public function checkinList(Request $request, GetCheckinListAction $action)
    {
        $request->validate([
            'class' => 'required',
            'date' => 'required|date',
            'session_type' => 'nullable|string',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        return response()->json(
            $action->execute($request->class, $request->date, $request->session_type, $request->subject_id),
        );
    }

    /**
     * Ambil detail sesi dari token (untuk preview sebelum confirm di mobile).
     * GET /api/student/attendance/session?qr_token=xxx
     */
    public function sessionInfo(Request $request, GetSessionInfoAction $action)
    {
        $request->validate([
            'qr_token' => 'required|string',
        ]);

        $result = $action->execute($request->qr_token);
        $status = $result['status_code'] ?? 200;
        unset($result['status_code']);

        return response()->json($result, $status);
    }
}
