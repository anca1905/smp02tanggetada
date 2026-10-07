<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\Auth\ApiLogoutAction;
use App\Actions\Api\Auth\TeacherLoginAction;
use App\Actions\Teacher\StudentPresence\StoreStudentAttendanceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TeacherLoginRequest;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherApiController extends Controller
{
    /**
     * Login for teachers.
     */
    public function login(TeacherLoginRequest $request, TeacherLoginAction $action): JsonResponse
    {
        $result = $action->execute($request->username, $request->password);
        $status = $result['status_code'] ?? 200;
        unset($result['status_code']);

        return response()->json($result, $status);
    }

    /**
     * Logout teacher.
     */
    public function logout(Request $request, ApiLogoutAction $action): JsonResponse
    {
        return response()->json($action->execute($request), 200);
    }

    /**
     * Teacher dashboard data.
     */
    public function dashboard(Request $request): JsonResponse
    {
        /** @var \App\Models\Teacher $teacher */
        $teacher = $request->user();
        $classroom = $teacher->classroom;

        $totalClasses = Classroom::count();
        $totalStudents = $classroom ? $classroom->students()->count() : 0;

        // Today's attendance recap if homeroom class
        $todayAttendanceRecap = [
            'present' => 0,
            'sick' => 0,
            'permission' => 0,
            'absent' => 0,
            'late' => 0,
        ];

        if ($classroom) {
            $today = Carbon::today()->toDateString();
            $attendance = Attendance::where('class', $classroom->id)
                ->where('date', $today)
                ->first();

            if ($attendance) {
                $counts = StudentAttendance::where('attendance_id', $attendance->id)
                    ->selectRaw('status, count(*) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray();

                foreach ($counts as $status => $count) {
                    if (isset($todayAttendanceRecap[$status])) {
                        $todayAttendanceRecap[$status] = $count;
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'teacher' => [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'username' => $teacher->username,
                    'employee_id' => $teacher->employee_id,
                    'subject' => $teacher->subject,
                    'homeroom_class' => $teacher->homeroom_class,
                    'photo_url' => $teacher->photo_url,
                ],
                'classroom' => $classroom ? [
                    'id' => $classroom->id,
                    'name' => $classroom->name,
                    'level' => $classroom->level,
                    'student_count' => $totalStudents,
                ] : null,
                'summary' => [
                    'total_classes' => $totalClasses,
                    'homeroom_students' => $totalStudents,
                    'today_recap' => $todayAttendanceRecap,
                ],
            ],
        ]);
    }

    /**
     * Get list of classrooms for roll call selection.
     */
    public function classes(Request $request): JsonResponse
    {
        $classrooms = Classroom::withCount('students')
            ->orderBy('level')
            ->orderBy('name')
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'level' => $c->level,
                'student_count' => $c->students_count,
            ]);

        return response()->json([
            'success' => true,
            'data' => $classrooms,
        ]);
    }

    /**
     * Get students for a specific classroom or all classrooms (used for offline caching).
     */
    public function students(Request $request): JsonResponse
    {
        $request->validate([
            'class_id' => 'nullable',
        ]);

        $query = Student::where('student_status', 'Active')
            ->with('classroom:id,name')
            ->orderBy('student_name');

        if ($request->filled('class_id') && $request->class_id !== 'all') {
            $query->where('classroom_id', $request->class_id);
        }

        $students = $query->get()
            ->map(fn ($s) => [
                'id' => $s->id,
                'nis' => $s->nis,
                'nisn' => $s->nisn,
                'student_name' => $s->student_name,
                'gender' => $s->gender,
                'photo_url' => $s->photo_url,
                'class_id' => $s->classroom_id,
                'class_name' => $s->classroom?->name ?? '-',
            ]);

        return response()->json([
            'success' => true,
            'data' => $students,
        ]);
    }

    /**
     * Get list of subjects for class session roll call.
     */
    public function subjects(): JsonResponse
    {
        $subjects = Subject::orderBy('name')
            ->get(['id', 'code', 'name']);

        return response()->json([
            'success' => true,
            'data' => $subjects,
        ]);
    }

    /**
     * Get existing attendance records for a class, date, and session.
     */
    public function attendanceHistory(Request $request): JsonResponse
    {
        $request->validate([
            'class_id' => 'nullable',
            'date' => 'required|date',
            'session_type' => 'required|in:apel,kelas,pulang',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        if (! $request->filled('class_id') || $request->class_id === 'all') {
            $attendanceIds = Attendance::where('date', $request->date)
                ->where('session_type', $request->session_type)
                ->pluck('id');

            $records = StudentAttendance::whereIn('attendance_id', $attendanceIds)
                ->with('student:id,nis')
                ->get()
                ->mapWithKeys(fn ($item) => [
                    $item->student?->nis => $item->status,
                ]);

            return response()->json([
                'success' => true,
                'has_data' => $records->isNotEmpty(),
                'data' => $records,
            ]);
        }

        $query = Attendance::where('class', $request->class_id)
            ->where('date', $request->date)
            ->where('session_type', $request->session_type);

        if ($request->session_type === 'kelas' && $request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $attendance = $query->first();

        if (! $attendance) {
            return response()->json([
                'success' => true,
                'has_data' => false,
                'data' => [],
            ]);
        }

        $records = StudentAttendance::where('attendance_id', $attendance->id)
            ->with('student:id,nis')
            ->get()
            ->mapWithKeys(fn ($item) => [
                $item->student?->nis => $item->status,
            ]);

        return response()->json([
            'success' => true,
            'has_data' => true,
            'data' => $records,
        ]);
    }

    /**
     * Sync/Submit attendance (supports single batch or multiple offline batches).
     */
    public function syncAttendance(
        Request $request,
        StoreStudentAttendanceAction $action
    ): JsonResponse {
        // Can be a single batch or multiple batches: { batches: [ ... ] }
        $batches = $request->input('batches');

        if (! is_array($batches)) {
            // Check if root payload is a single batch
            if ($request->has('class') && $request->has('attendance')) {
                $batches = [$request->all()];
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Format payload tidak valid. Harap kirimkan data presensi.',
                ], 422);
            }
        }

        $synced = 0;
        $errors = [];

        foreach ($batches as $index => $batch) {
            if (! isset($batch['class'], $batch['session_type'], $batch['date'], $batch['attendance'])) {
                $errors[] = "Batch index #$index tidak lengkap.";

                continue;
            }

            try {
                $action->execute([
                    'class' => $batch['class'],
                    'session_type' => $batch['session_type'],
                    'subject_id' => $batch['subject_id'] ?? null,
                    'date' => $batch['date'],
                    'attendance' => $batch['attendance'],
                ]);
                $synced++;
            } catch (\Throwable $e) {
                $errors[] = "Gagal memproses batch #$index: ".$e->getMessage();
            }
        }

        return response()->json([
            'success' => $synced > 0 || empty($errors),
            'message' => "Berhasil menyinkronkan {$synced} sesi presensi.",
            'synced_count' => $synced,
            'errors' => $errors,
        ]);
    }

    /**
     * Scan student barcode for roll call session.
     */
    public function scanBarcode(Request $request, \App\Actions\Teacher\StudentPresence\ScanBarcodeAction $action): JsonResponse
    {
        $request->validate([
            'nis' => 'required|string',
            'session_type' => 'required|in:apel,kelas,pulang',
            'class_id' => 'nullable',
            'date' => 'required|date',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        $classId = $request->class_id;
        if ($classId && $classId !== 'all') {
            if (! Classroom::where('id', $classId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas tidak ditemukan.',
                ], 422);
            }
        }

        $result = $action->execute(
            $request->nis,
            $request->session_type,
            $classId ? (string) $classId : 'all',
            $request->date,
        );

        $status = $result['success'] ? 200 : ($result['already_checked'] ? 200 : 422);

        return response()->json($result, $status);
    }

    /**
     * Get announcements for teachers.
     */
    public function announcements(): JsonResponse
    {
        $posts = \App\Models\Post::where('is_published', true)
            ->latest()
            ->take(20)
            ->get(['id', 'title', 'content', 'category', 'image', 'created_at']);

        return response()->json([
            'success' => true,
            'data' => $posts,
        ]);
    }

    /**
     * Get notifications for teachers.
     */
    public function notifications(Request $request): JsonResponse
    {
        /** @var \App\Models\Teacher $teacher */
        $teacher = $request->user();

        $notifications = [];

        // 1. Published posts
        $posts = \App\Models\Post::where('is_published', true)
            ->latest()
            ->take(5)
            ->get();

        foreach ($posts as $post) {
            $notifications[] = [
                'id' => 'post_'.$post->id,
                'title' => $post->title,
                'message' => \Illuminate\Support\Str::limit(strip_tags($post->content), 120),
                'full_message' => strip_tags($post->content),
                'type' => 'announcement',
                'category' => $post->category ?? 'Pengumuman',
                'created_at' => $post->created_at ? $post->created_at->toIso8601String() : now()->toIso8601String(),
                'is_read' => false,
            ];
        }

        // 2. Attendance reminder
        $today = Carbon::today()->format('Y-m-d');
        $notifications[] = [
            'id' => 'att_apel_'.$today,
            'title' => 'Pengingat Apel Pagi',
            'message' => 'Lakukan pengecekan kehadiran apel pagi sebelum pukul 07:15 WITA.',
            'full_message' => 'Lakukan pengecekan kehadiran apel pagi sebelum pukul 07:15 WITA di lapangan upacara.',
            'type' => 'attendance',
            'category' => 'Presensi',
            'created_at' => Carbon::today()->setTime(6, 45)->toIso8601String(),
            'is_read' => false,
        ];

        // 3. System status notification
        $notifications[] = [
            'id' => 'att_sync_'.$today,
            'title' => 'Penyimpanan Offline & Sinkronisasi',
            'message' => 'Aplikasi bekerja penuh saat offline. Data akan otomatis dikirim saat sinyal kembali.',
            'full_message' => 'Aplikasi ini bekerja penuh saat internet mati. Anda bisa mengabsen kelas tanpa kuota, dan data akan otomatis dikirim saat sinyal kembali.',
            'type' => 'system',
            'category' => 'Sistem',
            'created_at' => Carbon::today()->setTime(6, 0)->toIso8601String(),
            'is_read' => true,
        ];

        return response()->json([
            'success' => true,
            'data' => $notifications,
        ]);
    }
}
