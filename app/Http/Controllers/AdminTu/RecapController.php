<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Classroom\GetClassroomsAction;
use App\Actions\Recap\GetStudentRecapAction;
use App\Actions\Recap\GetTeacherRecapAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Recap\GetRecapRequest;
use Carbon\Carbon;
use Illuminate\View\View;

class RecapController extends Controller
{
    /**
     * Menampilkan rekap kehadiran guru atau siswa.
     */
    public function index(
        GetRecapRequest $request,
        GetTeacherRecapAction $teacherAction,
        GetStudentRecapAction $studentAction,
        GetClassroomsAction $classroomAction
    ): View {
        // Ambil filter, terapkan nilai default jika kosong
        $kategori = $request->validated('kategori', 'teacher');
        $bulan = (int) $request->validated('bulan', Carbon::now()->month);
        $tahun = Carbon::now()->year;
        $search = $request->validated('search');
        $classId = $request->validated('class');

        // Arahkan ke action spesifik berdasarkan kategori
        if ($kategori === 'teacher') {
            $data = $teacherAction->execute($bulan, $tahun, $search);
        } else {
            $data = $studentAction->execute($bulan, $tahun, $search, $classId);
        }

        $classrooms = $classroomAction->execute();

        return view('tu.absenteeism_recap', compact('data', 'kategori', 'bulan', 'classrooms'));
    }
}
