<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Ppdb\DeletePpdbAction;
use App\Actions\Ppdb\GetPpdbIndexDataAction;
use App\Actions\Ppdb\TogglePpdbStatusAction;
use App\Actions\Ppdb\UpdatePpdbStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ppdb\UpdatePpdbStatusRequest;
use App\Models\Ppdb;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PpdbController extends Controller
{
    /**
     * Tampilkan halaman manajemen PPDB.
     */
    public function index(Request $request, GetPpdbIndexDataAction $action): View
    {
        $data = $action->execute(
            $request->input('jurusan'),
            $request->input('status'),
            $request->input('search'),
        );

        return view('tu.ppdb.index', $data);
    }

    /**
     * Update status pendaftaran (Accepted / Rejected / Pending).
     */
    public function updateStatus(
        UpdatePpdbStatusRequest $request,
        Ppdb $ppdb,
        UpdatePpdbStatusAction $action,
    ): RedirectResponse {
        $status = $request->validated()['status_pendaftaran'];
        $action->execute($ppdb, $status);

        $label = match ($status) {
            'Accepted' => 'Diterima',
            'Rejected' => 'Ditolak',
            default => 'Pending',
        };

        return back()->with('success', "Status pendaftar {$ppdb->nama_lengkap} berhasil diubah menjadi {$label}.");
    }

    /**
     * Hapus data pendaftar.
     */
    public function destroy(Ppdb $ppdb, DeletePpdbAction $action): RedirectResponse
    {
        $nama = $action->execute($ppdb);

        return back()->with('success', "Data pendaftar {$nama} berhasil dihapus.");
    }

    /**
     * Toggle status buka/tutup PPDB.
     */
    public function toggleStatus(TogglePpdbStatusAction $action): RedirectResponse
    {
        $newStatus = $action->execute();

        $msg = $newStatus === '1' ? 'PPDB sekarang dibuka untuk umum.' : 'PPDB sekarang ditutup.';

        return back()->with('success', $msg);
    }
}
