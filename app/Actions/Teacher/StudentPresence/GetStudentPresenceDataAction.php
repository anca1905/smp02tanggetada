<?php

namespace App\Actions\Teacher\StudentPresence;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GetStudentPresenceDataAction
{
    /**
     * Mengambil daftar kelas, mengambil siswa + status kehadiran yang sudah tersimpan
     * berdasarkan kelas, sesi, dan tanggal terpilih.
     */
    public function execute(?string $selectedClass, ?string $selectedDate = null, ?string $selectedSession = null, ?int $selectedSubject = null): array
    {
        $selectedDate = $selectedDate ?? Carbon::today()->format('Y-m-d');
        $selectedSession = $selectedSession ?? 'kelas';

        $classList = Classroom::orderBy('name', 'asc')->pluck('name', 'id');

        $teacher = Auth::guard('teacher')->user();
        $subjects = collect();

        if ($teacher) {
            $subjects = Schedule::where('teacher_id', $teacher->id)
                ->with('subject')
                ->get()
                ->pluck('subject')
                ->filter()
                ->unique('id')
                ->values();
        }

        if ($subjects->isEmpty()) {
            $subjects = Subject::orderBy('name', 'asc')->get();
        }

        if ($selectedSession === 'kelas' && ! $selectedSubject) {
            $selectedSubject = $subjects->first()?->id;
        } elseif ($selectedSession !== 'kelas') {
            $selectedSubject = null;
        }

        $students = [];
        $attendanceData = null;

        if ($selectedClass) {
            $query = Attendance::where('class', $selectedClass)
                ->where('session_type', $selectedSession)
                ->where('date', $selectedDate);

            if ($selectedSession === 'kelas' && $selectedSubject) {
                $query->where('subject_id', $selectedSubject);
            } elseif ($selectedSession !== 'kelas') {
                $query->whereNull('subject_id');
            }

            $attendanceHeader = $query->first();

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

        return compact('classList', 'students', 'selectedClass', 'selectedDate', 'selectedSession', 'selectedSubject', 'attendanceData', 'subjects');
    }
}
