<?php

namespace App\Http\Controllers\AdminTu;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('code')->get();

        // Pastikan kamu sudah buat view di: resources/views/tu/subjects/index.blade.php
        return view('tu.subjects.index', compact('subjects'));
    }

    // Menyimpan Mapel Baru
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:subjects,code',
            'name' => 'required|string',
        ]);

        Subject::create($request->all());

        return back()->with('success', 'Mata pelajaran berhasil ditambahkan');
    }

    // Update Mapel
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|unique:subjects,code,' . $id,
            'name' => 'required|string',
        ]);

        $subject = Subject::findOrFail($id);
        $subject->update($request->all());

        return back()->with('success', 'Data diperbarui');
    }

    // Hapus Mapel
    public function destroy($id)
    {
        Subject::destroy($id);
        return back()->with('success', 'Data dihapus');
    }
}
