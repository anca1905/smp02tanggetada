<?php

namespace App\Http\Controllers\AdminTu;

use App\Models\Facility;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FacilityController extends Controller
{
    //
    public function index(Request $request)
    {
        $fasilitasList = Facility::all();

        return view('tu.facilities.index', compact('fasilitasList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:100',
            'image_path' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/facility'), $filename);
            $data['image_path'] = 'img/facility/' . $filename;
        }
        ;

        Facility::create($data);

        return back()->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $facility = Facility::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:100',
            'image_path' => 'nullable|image|max:2048'
        ]);

        // Jika ada file gambar baru yang diupload
        if ($request->hasFile('image_path')) {
            // Hapus gambar lama jika ada
            if ($facility->image_path && file_exists(public_path($facility->image_path))) {
                unlink(public_path($facility->image_path));
            }

            // Upload gambar baru
            $file = $request->file('image_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/facility'), $filename);
            $data['image_path'] = 'img/facility/' . $filename;
        }

        // Update data facility
        $facility->update($data);

        return back()->with('success', 'Fasilitas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);

        if ($facility->image_path && file_exists(public_path($facility->image_path))) {
            unlink(public_path($facility->image_path));
        }

        $facility->delete();

        return back()->with('success', 'Data fasilitas berhasil dihapus!');
    }
}
