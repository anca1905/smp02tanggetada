<?php

namespace App\Actions\Teacher\StudentPresence;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GetStudentPresenceDataAction
{
    /**
     * Mengambil daftar kelas, mengambil siswa + status kehadiran yang sudah tersimpan
     * berdasarkan kelas dan tanggal terpilih.
     */
    public function execute(Request $request): array
    {
        $selectedClass = $request->get('kelas');
        $selectedDate = $request->get('date', Carbon::today()->format('Y-m-d'));

        $classList = Classroom::orderBy('name', 'asc')->pluck('name', 'id');

        $students = [];
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

        return compact('classList', 'students', 'selectedClass', 'selectedDate', 'attendanceData');
    }
}
