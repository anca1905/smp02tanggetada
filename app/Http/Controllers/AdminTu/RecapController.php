<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Classroom\GetClassroomsAction;
use App\Actions\Recap\GetStudentRecapAction;
use App\Http\Controllers\Controller;
use App\Models\Setting;
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
        $tahun = Carbon::now()->year;
        $search = $request->get('search');
        $classId = $request->get('kelas');
        $sesi = $request->get('sesi'); // apel|kelas|pulang|null (semua sesi)

        $data = $studentAction->execute($bulan, $tahun, $search, $classId, $sesi);

        $classrooms = $classroomAction->execute();

        return view('tu.absenteeism_recap', compact('data', 'bulan', 'classrooms', 'sesi'));
    }

    /**
     * Export rekap kehadiran siswa ke format PDF.
     */
    public function exportPdf(
        Request $request,
        GetStudentRecapAction $studentAction,
        GetClassroomsAction $classroomAction
    ): Response {
        $bulan = (int) $request->get('bulan', Carbon::now()->month);
        $tahun = Carbon::now()->year;
        $search = $request->get('search');
        $classId = $request->get('kelas');
        $sesi = $request->get('sesi');

        $data = $studentAction->executeAll($bulan, $tahun, $search, $classId, $sesi);
        $classrooms = $classroomAction->execute();
        $selectedClass = $classId ? $classrooms->firstWhere('id', (int) $classId) : null;
        $site_settings = Setting::pluck('value', 'key')->toArray();

        $pdf = Pdf::loadView('pdf.attendance_recap', compact(
            'data',
            'bulan',
            'tahun',
            'sesi',
            'selectedClass',
            'site_settings'
        ))->setPaper('a4', 'landscape');

        $monthName = Carbon::create()->month($bulan)->locale('id')->isoFormat('MMMM');

        return $pdf->download("rekap-absensi-siswa-{$monthName}-{$tahun}.pdf");
    }
}
