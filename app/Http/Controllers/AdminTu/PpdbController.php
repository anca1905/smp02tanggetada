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
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

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
     * Tampilkan detail pendaftar beserta berkas.
     */
    public function show(Ppdb $ppdb): View
    {
        return view('tu.ppdb.show', compact('ppdb'));
    }

    /**
     * Tampilkan berkas dokumen PPDB secara inline.
     */
    public function showDocument(Ppdb $ppdb, string $field): Response
    {
        $allowedFields = [
            'doc_ijazah',
            'doc_transkrip',
            'doc_tka',
            'doc_akta',
            'doc_kk',
            'doc_ktp_ayah',
            'doc_ktp_ibu',
            'doc_pas_photo',
        ];

        abort_unless(in_array($field, $allowedFields, true), 404);

        $filePath = $ppdb->{$field};
        abort_if(empty($filePath), 404);

        $disk = Storage::disk('public')->exists($filePath)
            ? 'public'
            : (Storage::disk('local')->exists($filePath) ? 'local' : null);

        abort_unless($disk, 404, 'Berkas tidak ditemukan pada server.');

        return Storage::disk($disk)->response($filePath);
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
