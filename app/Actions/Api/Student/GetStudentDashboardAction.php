<?php

namespace App\Actions\Api\Student;

use App\Models\Assignment;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\StudentAttendanceDetail;
use Carbon\Carbon;

class GetStudentDashboardAction
{
    /**
     * Get dashboard data for a student.
     */
    public function execute(Student $student): array
    {
        // Get today's schedules
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $todayIndo = $days[Carbon::now()->format('l')] ?? 'Senin';

        $schedules = Schedule::with(['subject', 'teacher'])
            ->where('classroom_id', $student->classroom_id)
            ->where('day', $todayIndo)
            ->orderBy('start_time', 'asc')
            ->get();

        // Get upcoming assignments
        $assignments = Assignment::with(['subject'])
            ->where('classroom_id', $student->classroom_id)
            ->where('due_date', '>=', now())
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        // Get Attendance Percentage
        $totalSessions = \App\Models\Attendance::where('class', $student->classroom_id)->count();
        $studentAttendances = StudentAttendanceDetail::where('student_id', $student->id)
            ->whereIn('status', ['present', 'late', 'Hadir', 'Late'])
            ->count();

        $attendancePercentage = $totalSessions > 0 ? round(($studentAttendances / $totalSessions) * 100) : 100;

        // Get Announcements
        $announcements = \App\Models\Post::where('is_published', true)
            ->latest()
            ->take(3)
            ->get(['id', 'title', 'category', 'created_at']);

        return [
            'success' => true,
            'data' => [
                'student' => $student->load('classroom'),
                'today_schedules' => $schedules,
                'upcoming_assignments' => $assignments,
                'attendance_percentage' => $attendancePercentage,
                'announcements' => $announcements,
            ],
        ];
    }
}
