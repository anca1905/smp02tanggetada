<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Classroom\GetClassroomsAction;
use App\Actions\Recap\GetStudentRecapAction;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Setting;
use App\Models\Student;
use App\Models\StudentAttendance;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class RecapController extends Controller
{
    /**
     * Menampilkan rekap kehadiran siswa.
     * (Rekap guru dihapus — sekolah hanya membutuhkan rekap absensi siswa.)
     */
    public function index(
        Request $request,
        GetStudentRecapAction $studentAction,
        GetClassroomsAction $classroomAction
    ): View {
        $bulan = (int) $request->get('bulan', Carbon::now()->month);
        $tahun = (int) $request->get('tahun', Carbon::now()->year);
        $search = $request->get('search');
        $classId = $request->get('kelas');
        $sesi = $request->get('sesi'); // apel|kelas|pulang|null (semua sesi)

        $data = $studentAction->execute($bulan, $tahun, $search, $classId, $sesi);

        $classrooms = $classroomAction->execute();

        return view('tu.absenteeism_recap', compact('data', 'bulan', 'classrooms', 'sesi'));
    }

    /**
     * Export rekap kehadiran siswa ke format PDF (format Daftar Hadir Siswa bulanan).
     */
    public function exportPdf(
        Request $request,
        GetStudentRecapAction $studentAction,
        GetClassroomsAction $classroomAction
    ): Response {
        $bulan = (int) $request->get('bulan', Carbon::now()->month);
        $tahun = (int) $request->get('tahun', Carbon::now()->year);
        $search = $request->get('search');
        $classId = $request->get('kelas');
        $sesi = $request->get('sesi');
        $isStream = $request->boolean('stream');

        $data = $studentAction->executeAll($bulan, $tahun, $search, $classId, $sesi);
        $classrooms = $classroomAction->execute();
        $selectedClass = $classId ? $classrooms->firstWhere('id', (int) $classId) : null;
        $site_settings = Setting::pluck('value', 'key')->toArray();

        $daysInMonth = Carbon::create($tahun, $bulan, 1)->daysInMonth;
        $monthName = Carbon::create()->month($bulan)->locale('id')->isoFormat('MMMM');

        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $defaultAcademicYearName = $activeAcademicYear ? $activeAcademicYear->name : ($bulan >= 7 ? "{$tahun}/".($tahun + 1) : ($tahun - 1)."/{$tahun}");
        $defaultSemester = $activeAcademicYear ? $activeAcademicYear->semester : ($bulan >= 7 && $bulan <= 12 ? 'Semester Ganjil' : 'Semester Genap');
        if (! str_starts_with(strtolower((string) $defaultSemester), 'semester')) {
            $defaultSemester = 'Semester '.$defaultSemester;
        }

        // Tentukan kelas yang akan diproses
        if ($selectedClass) {
            $classesToProcess = collect([$selectedClass]);
        } elseif ($classrooms->isNotEmpty()) {
            $classesToProcess = $classrooms;
        } else {
            $classesToProcess = collect([null]);
        }

        // Ambil siswa sesuai filter
        $studentsQuery = Student::query()
            ->with(['classroom.teacher', 'classroom.academicYear'])
            ->when($selectedClass, fn ($q) => $q->where('classroom_id', $selectedClass->id))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('student_name', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%");
                });
            })
            ->orderBy('student_name', 'asc');

        $allStudents = $studentsQuery->get();
        $studentIds = $allStudents->pluck('id')->toArray();

        // Query riwayat absensi siswa pada bulan & tahun tersebut
        $attendanceQuery = StudentAttendance::query()
            ->join('attendances', 'student_attendance_details.attendance_id', '=', 'attendances.id')
            ->whereMonth('attendances.date', $bulan)
            ->whereYear('attendances.date', $tahun)
            ->whereIn('student_attendance_details.student_id', $studentIds);

        if ($sesi) {
            $attendanceQuery->where('attendances.session_type', $sesi);
        }

        $attendanceRecords = $attendanceQuery->select([
            'student_attendance_details.student_id',
            'student_attendance_details.status',
            'attendances.date',
            'attendances.session_type',
        ])->get();

        // Matriks absensi: [student_id][day] => 'S'|'I'|'A'
        $matrix = [];
        foreach ($attendanceRecords as $record) {
            $day = (int) Carbon::parse($record->date)->format('j');
            $sid = $record->student_id;
            $st = strtolower($record->status);

            $sym = match ($st) {
                'sick', 'sakit', 's' => 'S',
                'permission', 'izin', 'i' => 'I',
                'absent', 'alpa', 'alpha', 'a' => 'A',
                default => '',
            };

            if ($sym !== '') {
                $existing = $matrix[$sid][$day] ?? '';
                // Prioritas jika multi-sesi: Alpa > Sakit > Izin
                if ($existing === '' || $sym === 'A' || ($sym === 'S' && $existing === 'I')) {
                    $matrix[$sid][$day] = $sym;
                }
            }
        }

        $sheets = [];
        foreach ($classesToProcess as $cls) {
            if ($cls) {
                $clsStudents = $allStudents->where('classroom_id', $cls->id)->values();
                $teacher = $cls->teacher;
                $academicYear = $cls->academicYear ?? $activeAcademicYear;
                $clsAcademicYearName = $academicYear ? $academicYear->name : $defaultAcademicYearName;
                $clsSemester = $academicYear && $academicYear->semester
                    ? (str_starts_with(strtolower((string) $academicYear->semester), 'semester') ? $academicYear->semester : 'Semester '.$academicYear->semester)
                    : $defaultSemester;
                $namaRombel = $cls->name;
            } else {
                $clsStudents = $allStudents->values();
                $teacher = null;
                $clsAcademicYearName = $defaultAcademicYearName;
                $clsSemester = $defaultSemester;
                $namaRombel = 'Semua Kelas';
            }

            // Lewati rombel kosong jika multi rombel dan tidak dipilih spesifik
            if ($clsStudents->isEmpty() && ! $selectedClass && $classesToProcess->count() > 1) {
                continue;
            }

            $countL = $clsStudents->filter(fn ($s) => in_array(strtoupper($s->gender ?? ''), ['M', 'L']))->count();
            $countP = $clsStudents->filter(fn ($s) => in_array(strtoupper($s->gender ?? ''), ['F', 'P']))->count();

            $sheets[] = [
                'classroom' => $cls,
                'namaRombel' => $namaRombel,
                'academicYearName' => $clsAcademicYearName,
                'semesterName' => $clsSemester,
                'waliKelasName' => $teacher->name ?? '—',
                'waliKelasNip' => $teacher->employee_id ?? '',
                'students' => $clsStudents,
                'countL' => $countL,
                'countP' => $countP,
                'totalCount' => $clsStudents->count(),
            ];
        }

        if (empty($sheets)) {
            $sheets[] = [
                'classroom' => $selectedClass,
                'namaRombel' => $selectedClass ? $selectedClass->name : 'Semua Kelas',
                'academicYearName' => $defaultAcademicYearName,
                'semesterName' => $defaultSemester,
                'waliKelasName' => $selectedClass && $selectedClass->teacher ? $selectedClass->teacher->name : '—',
                'waliKelasNip' => $selectedClass && $selectedClass->teacher ? ($selectedClass->teacher->employee_id ?? '') : '',
                'students' => collect(),
                'countL' => 0,
                'countP' => 0,
                'totalCount' => 0,
            ];
        }

        $pdf = Pdf::loadView('pdf.attendance_recap', compact(
            'data',
            'sheets',
            'matrix',
            'bulan',
            'tahun',
            'daysInMonth',
            'monthName',
            'sesi',
            'selectedClass',
            'site_settings'
        ))->setPaper('a4', 'landscape');

        $fileName = "rekap-absensi-siswa-{$monthName}-{$tahun}.pdf";

        return $isStream ? $pdf->stream($fileName) : $pdf->download($fileName);
    }
}
