<?php

namespace App\Http\Controllers\Teacher;

use App\Actions\Teacher\StudentPresence\CloseSessionAction;
use App\Actions\Teacher\StudentPresence\GenerateQrSessionAction;
use App\Actions\Teacher\StudentPresence\GetStudentPresenceDataAction;
use App\Actions\Teacher\StudentPresence\GetStudentsByClassAction;
use App\Actions\Teacher\StudentPresence\ScanBarcodeAction;
use App\Actions\Teacher\StudentPresence\StoreStudentAttendanceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\CloseSessionRequest;
use App\Http\Requests\Teacher\GenerateQrRequest;
use App\Http\Requests\Teacher\ScanBarcodeRequest;
use App\Http\Requests\Teacher\StoreStudentAttendanceRequest;
use App\Models\Classroom;
use Illuminate\Http\Request;

class StudentPresenceController extends Controller
{
    /**
     * Halaman input manual absensi siswa (untuk guru yang login).
     */
    public function index(Request $request, GetStudentPresenceDataAction $action)
    {
        $selectedClass   = $request->get('kelas');
        $selectedDate    = $request->get('date');
        $selectedSession = $request->get('sesi', 'kelas');
        $data = $action->execute($selectedClass, $selectedDate, $selectedSession);

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
     * Ambil daftar siswa per kelas (AJAX helper).
     */
    public function getSiswa($kelas, GetStudentsByClassAction $action)
    {
        $students = $action->execute($kelas);

        return response()->json($students);
    }

    // ====================================================================
    // KIOSK — Scan Barcode Siswa (akses publik tanpa login guru)
    // ====================================================================

    /**
     * Halaman kiosk scan barcode siswa.
     * GET /presensi
     */
    public function kiosk(Request $request)
    {
        $classrooms = Classroom::orderBy('name')->get();

        return view('presence', compact('classrooms'));
    }

    /**
     * Proses scan barcode NIS siswa dari kiosk.
     * POST /presensi/scan
     */
    public function scan(ScanBarcodeRequest $request, ScanBarcodeAction $action)
    {
        $validated = $request->validated();

        $result = $action->execute(
            $validated['nis'],
            $validated['session_type'],
            $validated['class'],
            $validated['date'],
        );

        return response()->json($result);
    }

    /**
     * Ambil daftar siswa yang sudah di-scan pada sesi tertentu (polling AJAX).
     * GET /presensi/scan-list
     */
    public function scanList(Request $request)
    {
        $request->validate([
            'class'        => 'required',
            'session_type' => 'required|in:apel,kelas,pulang',
            'date'         => 'required|date',
        ]);

        $header = \App\Models\Attendance::where('class', $request->class)
            ->where('session_type', $request->session_type)
            ->where('date', $request->date)
            ->first();

        if (! $header) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $list = \App\Models\StudentAttendance::where('attendance_id', $header->id)
            ->where('status', 'present')
            ->with('student')
            ->get()
            ->map(fn($a) => [
                'student_name' => $a->student->student_name ?? '-',
                'scan_time'    => $a->updated_at->format('H:i:s'),
            ]);

        return response()->json(['success' => true, 'data' => $list]);
    }

    /**
     * Tutup sesi absensi — auto-mark absent untuk yang belum scan.
     * POST /presensi/close
     */
    public function closeSession(CloseSessionRequest $request, CloseSessionAction $action)
    {
        $validated = $request->validated();
        $result = $action->execute(
            $validated['class'],
            $validated['session_type'],
            $validated['date'],
        );

        return response()->json($result);
    }
}
