<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Classroom\GetClassroomsAction;
use App\Actions\Recap\GetStudentRecapAction;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $bulan   = (int) $request->get('bulan', Carbon::now()->month);
        $tahun   = Carbon::now()->year;
        $search  = $request->get('search');
        $classId = $request->get('kelas');
        $sesi    = $request->get('sesi'); // apel|kelas|pulang|null (semua sesi)

        $data = $studentAction->execute($bulan, $tahun, $search, $classId, $sesi);

        $classrooms = $classroomAction->execute();

        return view('tu.absenteeism_recap', compact('data', 'bulan', 'classrooms', 'sesi'));
    }
}
