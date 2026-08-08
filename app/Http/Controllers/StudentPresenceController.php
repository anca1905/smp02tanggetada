<?php

namespace App\Http\Controllers;

use App\Actions\Teacher\StudentPresence\GenerateQrSessionAction;
use App\Actions\Teacher\StudentPresence\GetStudentPresenceDataAction;
use App\Actions\Teacher\StudentPresence\GetStudentsByClassAction;
use App\Actions\Teacher\StudentPresence\StoreStudentAttendanceAction;
use App\Http\Requests\Teacher\GenerateQrRequest;
use App\Http\Requests\Teacher\StoreStudentAttendanceRequest;
use Illuminate\Http\Request;

class StudentPresenceController extends Controller
{
    public function index(Request $request, GetStudentPresenceDataAction $action)
    {
        $data = $action->execute($request);

        return view('student_presence', $data);
    }

    public function store(StoreStudentAttendanceRequest $request, StoreStudentAttendanceAction $action)
    {
        $action->execute($request->validated());

        return back()->with('success', 'Data presensi berhasil disimpan!');
    }

    /**
     * Generate / refresh QR token untuk sesi presensi.
     * POST /teacher/student-attendance/generate-qr
     */
    public function generateQr(GenerateQrRequest $request, GenerateQrSessionAction $action)
    {
        $response = $action->execute($request->validated());

        return response()->json($response);
    }

    /**
     * Ambil daftar siswa per kelas (AJAX helper untuk halaman presensi).
     */
    public function getSiswa(Request $request, $kelas, GetStudentsByClassAction $action)
    {
        $students = $action->execute($kelas);

        return response()->json($students);
    }
}
