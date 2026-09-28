<?php

namespace App\Http\Controllers\AdminTu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EDokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $edokumens = \App\Models\EDokumen::latest()->get();

        return view('tu.edokumen.index', compact('edokumens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tu.edokumen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'file_path' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $path = $request->file('file_path')->store('edokumen', 'public');

        \App\Models\EDokumen::create([
            'title' => $request->title,
            'category' => $request->category,
            'file_path' => $path,
        ]);

        return redirect()->route('tu.edokumen.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $edokumen = \App\Models\EDokumen::findOrFail($id);

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($edokumen->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($edokumen->file_path);
        }

        $edokumen->delete();

        return redirect()->route('tu.edokumen.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}
