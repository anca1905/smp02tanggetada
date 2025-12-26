<?php

namespace App\Http\Controllers\AdminTu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Setting::whereIn('key', ['jadwal_kelas_10', 'jadwal_kelas_11', 'jadwal_kelas_12'])
                            ->pluck('value', 'key')
                            ->toArray();

        return view('tu.schedules.index', compact('schedules'));
    }

    public function update(Request $request)
    {
        $keys = ['jadwal_kelas_10', 'jadwal_kelas_11', 'jadwal_kelas_12'];

        foreach ($keys as $key) {
            if ($request->hasFile($key)) {
                $request->validate([$key => 'mimes:pdf|max:5120']);

                $oldFile = Setting::where('key', $key)->value('value');
                if ($oldFile) {
                    Storage::disk('public')->delete($oldFile);
                }

                $path = $request->file($key)->store('jadwal', 'public');
                Setting::updateOrCreate(['key' => $key], ['value' => $path]);
            }
        }

        return back()->with('success', 'File jadwal pelajaran berhasil diperbarui!');
    }
}