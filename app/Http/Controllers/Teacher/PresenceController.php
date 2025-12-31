<?php

namespace App\Http\Controllers\Teacher;

use Carbon\Carbon;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\Teacher_absence;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PresenceController extends Controller
{
    public function index()
    {
        return view('presence');
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        if (strlen($query) < 3) return response()->json([]);

        $teachers = Teacher::where('name', 'like', "%{$query}%")
            ->orWhere('ID', 'like', "%{$query}%")
            ->where('status', 'Active')
            ->limit(5)
            ->get(['name', 'ID']);

        return response()->json($teachers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'identity' => 'required',
            'password' => 'required',
            'image'    => 'required',
        ]);

        $teacher = Teacher::where('ID', $request->identity)
            ->orWhere('name', $request->identity)
            ->first();

        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Data guru tidak ditemukan.']);
        }

        Auth::guard('teacher')->login($teacher);

        if (!Hash::check($request->password, $teacher->password)) {
            return response()->json(['status' => 'error', 'message' => 'Password salah. Silakan coba lagi.']);
        }

        $image = $request->image;
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
        $image = str_replace(' ', '+', $image);
        $fileName = 'attendance/' . date('Y-m-d') . '_' . $teacher->teacher_id . '_' . time() . '.png';

        Storage::disk('public')->put($fileName, base64_decode($image));
        $photoUrl = 'storage/' . $fileName;

        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now();
        $timeNow = $now->format('H:i:s');
        $dateNowFormatted = $now->isoFormat('dddd, D MMMM Y');

        $attendance = Teacher_absence::where('teacher_id', $teacher->teacher_id)
            ->where('date', $today)
            ->first();

        $statusKeterangan = '';
        $tipeAbsen = '';

        if (!$attendance) {
            $tipeAbsen = 'Absen Datang';
            $batasMasuk = Carbon::createFromTime(7, 30, 0);
            $statusKeterangan = $now->gt($batasMasuk) ? 'Terlambat' : 'Tepat Waktu';

            Teacher_absence::create([
                'teacher_id' => $teacher->teacher_id,
                'date' => $today,
                'arrival_time' => $timeNow,
                'arrival_photo_url' => $photoUrl
            ]);
        } elseif ($attendance->arrival_time && is_null($attendance->return_time)) {
            $arrivalTime = Carbon::parse($attendance->arrival_time);
            if ($now->diffInMinutes($arrivalTime) < 1) {
                return response()->json(['status' => 'warning', 'message' => 'Anda baru saja absen masuk. Tunggu beberapa saat.']);
            }

            $tipeAbsen = 'Absen Pulang';
            $batasPulang = Carbon::createFromTime(14, 0, 0);
            $statusKeterangan = $now->lt($batasPulang) ? 'Pulang Cepat' : 'Tepat Waktu';

            $attendance->update([
                'return_time' => $timeNow,
                'return_photo_url' => $photoUrl
            ]);
        } else {
            return response()->json(['status' => 'info', 'message' => 'Anda sudah menyelesaikan absensi hari ini.']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Presensi Berhasil',
            'detail_status' => $statusKeterangan,
            'detail' => [
                'nama' => $teacher->name,
                'id' => $teacher->ID,
                'tanggal' => $dateNowFormatted,
                'waktu' => $timeNow,
                'keterangan' => "$tipeAbsen ($statusKeterangan)"
            ]
        ]);
    }
}
