<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Schedule;
use App\Models\StudentAttendanceDetail;
use App\Models\StudentGrade;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StudentApiController extends Controller
{
    public function dashboard(Request $request)
    {
        $student = $request->user();

        // Get today's schedules
        $today = Carbon::now()->isoFormat('dddd'); // e.g. "Senin"
        
        // Translating English days to Indonesian since seeder uses "Senin"
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

        return response()->json([
            'success' => true,
            'data' => [
                'student' => $student->load('classroom'),
                'today_schedules' => $schedules,
                'upcoming_assignments' => $assignments,
            ]
        ]);
    }

    public function schedules(Request $request)
    {
        $student = $request->user();

        // Get all schedules for the student's classroom
        $schedules = Schedule::with(['subject', 'teacher'])
            ->where('classroom_id', $student->classroom_id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }

    public function assignments(Request $request)
    {
        $student = $request->user();

        $assignments = Assignment::with(['subject', 'teacher', 'submissions' => function($query) use ($student) {
            $query->where('student_id', $student->id);
        }])
            ->where('classroom_id', $student->classroom_id)
            ->orderBy('due_date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $assignments
        ]);
    }

    public function materials(Request $request)
    {
        $student = $request->user();
        
        // Find materials related to student's classroom schedules
        $scheduleIds = Schedule::where('classroom_id', $student->classroom_id)->pluck('id');
        
        $materials = \App\Models\Material::with(['schedule.subject', 'schedule.teacher'])
            ->whereIn('schedule_id', $scheduleIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $materials
        ]);
    }

    public function submitAssignment(Request $request, $id)
    {
        $student = $request->user();
        
        $request->validate([
            'file' => 'required|file|max:10240', // max 10MB
            'student_note' => 'nullable|string'
        ]);

        $assignment = Assignment::findOrFail($id);

        if ($assignment->classroom_id != $student->classroom_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }

        $submission = \App\Models\AssignmentSubmission::firstOrNew([
            'assignment_id' => $assignment->id,
            'student_id' => $student->id
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('assignments/submissions', $filename, 'public');
            $submission->file_path = $path;
        }

        $submission->student_note = $request->student_note;
        $submission->submitted_at = now();
        $submission->save();

        return response()->json([
            'success' => true,
            'message' => 'Assignment submitted successfully.',
            'data' => $submission
        ]);
    }

    public function attendances(Request $request)
    {
        $student = $request->user();

        $attendances = StudentAttendanceDetail::with(['attendance.teacher'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $attendances
        ]);
    }

    public function grades(Request $request)
    {
        $student = $request->user();

        $grades = StudentGrade::with(['subject'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $grades
        ]);
    }

    public function announcements(Request $request)
    {
        $announcements = \App\Models\Post::where('is_published', true)
            ->latest()
            ->take(20)
            ->get(['id', 'title', 'content', 'category', 'created_at']);

        return response()->json([
            'success' => true,
            'data' => $announcements
        ]);
    }
}
