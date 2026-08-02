<?php

namespace App\Http\Controllers\AdminTu;

use App\Models\Ppdb;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PpdbController extends Controller
{
    /**
     * Tampilkan halaman manajemen PPDB.
     */
    public function index(Request $request)
    {
        $query = Ppdb::orderBy('tanggal_daftar', 'desc');

        // Filter jurusan
        if ($request->filled('jurusan')) {
            $query->where('jurusan_pilihan', $request->jurusan);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status_pendaftaran', $request->status);
        }

        // Pencarian nama / no_registrasi
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('no_registrasi', 'like', '%' . $request->search . '%')
                  ->orWhere('nisn', 'like', '%' . $request->search . '%');
            });
        }

        $pendaftars = $query->paginate(15)->withQueryString();

        // Statistik
        $stats = [
            'total'    => Ppdb::count(),
            'pending'  => Ppdb::where('status_pendaftaran', 'Pending')->count(),
            'accepted' => Ppdb::where('status_pendaftaran', 'Accepted')->count(),
            'rejected' => Ppdb::where('status_pendaftaran', 'Rejected')->count(),
        ];

        // Status PPDB (buka/tutup) dari settings
        $bukaPpdb = Setting::where('key', 'buka_ppdb')->value('value') ?? '1';

        return view('tu.ppdb.index', compact('pendaftars', 'stats', 'bukaPpdb'));
    }

    /**
     * Update status pendaftaran (Accepted / Rejected / Pending).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pendaftaran' => 'required|in:Pending,Accepted,Rejected',
        ]);

        $ppdb = Ppdb::findOrFail($id);
        $ppdb->update(['status_pendaftaran' => $request->status_pendaftaran]);

        $label = match ($request->status_pendaftaran) {
            'Accepted' => 'Diterima',
            'Rejected' => 'Ditolak',
            default    => 'Pending',
        };

        return back()->with('success', "Status pendaftar {$ppdb->nama_lengkap} berhasil diubah menjadi {$label}.");
    }

    /**
     * Hapus data pendaftar.
     */
    public function destroy($id)
    {
        $ppdb = Ppdb::findOrFail($id);
        $nama = $ppdb->nama_lengkap;
        $ppdb->delete();

        return back()->with('success', "Data pendaftar {$nama} berhasil dihapus.");
    }

    /**
     * Toggle status buka/tutup PPDB.
     */
    public function toggleStatus(Request $request)
    {
        $current = Setting::where('key', 'buka_ppdb')->value('value') ?? '1';
        $new = $current === '1' ? '0' : '1';

        Setting::updateOrCreate(
            ['key' => 'buka_ppdb'],
            ['value' => $new]
        );

        $msg = $new === '1' ? 'PPDB sekarang dibuka untuk umum.' : 'PPDB sekarang ditutup.';
        return back()->with('success', $msg);
    }
}
