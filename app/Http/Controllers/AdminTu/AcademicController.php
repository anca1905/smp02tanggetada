<?php

namespace App\Http\Controllers\AdminTu;

use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AcademicController extends Controller
{
    public function index()
    {
        $years = AcademicYear::orderBy('name', 'desc')->get();
        return view('tu.academic.index', compact('years'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        AcademicYear::create([
            'name' => $request->name,
            'semester' => $request->semester,
            'is_active' => false // Default tidak aktif
        ]);

        return back()->with('success', 'Tahun ajaran berhasil ditambahkan');
    }

    public function setActive($id)
    {
        // 1. Matikan semua tahun ajaran dulu
        AcademicYear::query()->update(['is_active' => false]);

        // 2. Aktifkan yang dipilih
        AcademicYear::where('id', $id)->update(['is_active' => true]);

        return back()->with('success', 'Tahun ajaran aktif berhasil diubah!');
    }

    public function destroy($id)
    {
        AcademicYear::destroy($id);
        return back()->with('success', 'Data dihapus');
    }
}
