<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\Auth;

class StudentPresenceController extends Controller
{
    public function index(Request $request)
    {
        $guru = Auth::user();

        $selectedClass = $request->get('kelas');
        $selectedDate = $request->get('date', Carbon::today()->format('Y-m-d'));

        $classList = Student::select('class')->distinct()->orderBy('class')->pluck('class');

        $students = [];
        $attendanceData = null;

        if ($selectedClass) {
            $attendanceHeader = Attendance::where('class', $selectedClass)
                ->where('date', $selectedDate)
                ->first();

            if ($attendanceHeader) {
                $attendanceData = $attendanceHeader;

                $students = Student::leftJoin('student_attendance_details', function ($join) use ($attendanceHeader) {
                    $join->on('students.nis', '=', 'student_attendance_details.nis')
                        ->where('student_attendance_details.attendance_id', '=', $attendanceHeader->attendance_id);
                })
                    ->where('students.class', $selectedClass)
                    ->orderBy('students.student_name', 'asc')
                    ->select('students.*', 'student_attendance_details.status as saved_status')
                    ->get();
            } else {
                $students = Student::where('class', $selectedClass)
                    ->orderBy('student_name', 'asc')
                    ->get();
            }
        }

        return view('student_presence', compact('classList', 'students', 'selectedClass', 'selectedDate', 'attendanceData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class' => 'required',
            'date' => 'required|date',
            'attendance' => 'required|array',
        ]);

        $guru = Auth::user();

        $header = Attendance::updateOrCreate(
            [
                'class' => $request->class,
                'date' => $request->date,
            ],
            [
                'teacher_id' => $guru->teacher_id,
                'start_time' => Carbon::now()->format('H:i:00'),
                'end_time'   => Carbon::now()->addHour()->format('H:i:00'),
            ]
        );

        foreach ($request->attendance as $nis => $data) {
            StudentAttendance::updateOrCreate(
                [
                    'attendance_id' => $header->attendance_id,
                    'nis' => $nis,
                ],
                [
                    'status' => $data['status'],
                ]
            );
        }

        return back()->with('success', 'Data presensi berhasil disimpan!');
    }
}