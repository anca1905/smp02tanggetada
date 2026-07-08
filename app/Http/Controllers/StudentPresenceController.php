<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudentPresenceController extends Controller
{
    public function index(Request $request)
    {
        $guru = Auth::user();

        $selectedClass = $request->get('kelas');
        $selectedDate  = $request->get('date', Carbon::today()->format('Y-m-d'));

        $classList = Classroom::orderBy('name', 'asc')->pluck('name', 'id');

        $students      = [];
        $attendanceData = null;

        if ($selectedClass) {
            $attendanceHeader = Attendance::where('class', $selectedClass)
                ->where('date', $selectedDate)
                ->first();

            if ($attendanceHeader) {
                $attendanceData = $attendanceHeader;

                $students = Student::leftJoin('student_attendance_details', function ($join) use ($attendanceHeader) {
                    $join->on('students.id', '=', 'student_attendance_details.student_id')
                        ->where('student_attendance_details.attendance_id', '=', $attendanceHeader->id);
                })
                    ->where('students.classroom_id', $selectedClass)
                    ->orderBy('students.student_name', 'asc')
                    ->select('students.*', 'student_attendance_details.status as saved_status')
                    ->get();
            } else {
                $students = Student::where('classroom_id', $selectedClass)
                    ->orderBy('student_name', 'asc')
                    ->get();
            }
        }

        return view('student_presence', compact('classList', 'students', 'selectedClass', 'selectedDate', 'attendanceData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class'      => 'required',
            'date'       => 'required|date',
            'attendance' => 'required|array',
        ]);

        $guru = Auth::user();

        $header = Attendance::updateOrCreate(
            [
                'class' => $request->class,
                'date'  => $request->date,
            ],
            [
                'teacher_id' => $guru->id,
                'start_time' => Carbon::now()->format('H:i:00'),
                'end_time'   => Carbon::now()->addHour()->format('H:i:00'),
            ]
        );

        foreach ($request->attendance as $nis => $data) {
            // Cari student by NIS
            $student = Student::where('nis', $nis)->first();
            if (!$student) continue;

            StudentAttendance::updateOrCreate(
                [
                    'attendance_id' => $header->id,
                    'nis'           => $nis,
                ],
                [
                    'status'     => $data['status'],
                    'student_id' => $student->id,
                ]
            );
        }

        return back()->with('success', 'Data presensi berhasil disimpan!');
    }

    /**
     * Generate / refresh QR token untuk sesi presensi.
     * POST /teacher/student-attendance/generate-qr
     */
    public function generateQr(Request $request)
    {
        $request->validate([
            'class' => 'required',
            'date'  => 'required|date',
        ]);

        $guru = Auth::user();

        // Buat atau update sesi presensi
        $header = Attendance::updateOrCreate(
            [
                'class' => $request->class,
                'date'  => $request->date,
            ],
            [
                'teacher_id' => $guru->id,
                'start_time' => Carbon::now()->format('H:i:00'),
                'end_time'   => Carbon::now()->addHour()->format('H:i:00'),
            ]
        );

        // Generate token baru + set expire 5 menit
        $token = Str::random(32);
        $header->update([
            'qr_token'      => $token,
            'qr_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        return response()->json([
            'success'    => true,
            'qr_token'   => $token,
            'expires_at' => $header->qr_expires_at->toISOString(),
            'class'      => $request->class,
            'date'       => $request->date,
        ]);
    }

    /**
     * Ambil daftar siswa per kelas (AJAX helper untuk halaman presensi).
     */
    public function getSiswa(Request $request, $kelas)
    {
        $students = Student::where('classroom_id', $kelas)
            ->orderBy('student_name')
            ->get(['id', 'nis', 'student_name']);

        return response()->json($students);
    }
}