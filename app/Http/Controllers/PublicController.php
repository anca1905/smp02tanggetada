<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\Event;
use App\Models\Message;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Ppdb;
use App\Models\Setting;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $latest_posts = Post::where('is_published', true)->latest()->take(3)->get();
        $staff = Teacher::where('status', 'Active')->count();
        $student = Student::where('student_status', 'Active')->count();
        return view('landing', compact('latest_posts', 'staff', 'student'));
    }

    public function profil()
    {
        $facility = Facility::all();

        return view('public.profile', compact('facility'));
    }

    public function berita()
    {
        $posts = Post::where('is_published', true)->latest()->paginate(9);
        return view('public.news.news', compact('posts'));
    }

    public function showBerita($slug)
    {
        $post = Post::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $recent_posts = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(5)
            ->get();

        return view('public.news.news-show', compact('post', 'recent_posts'));
    }

    public function kontak()
    {
        return view('public.contact');
    }

    public function jadwal()
    {
        return view('public.timetable');
    }

    public function kalender()
    {
        $events = Event::orderBy('start_date', 'asc')->get();

        $eventsByMonth = $events->groupBy(function ($date) {
            return Carbon::parse($date->start_date)->isoFormat('MMMM Y');
        });

        return view('public.calender', compact('eventsByMonth'));
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Message::create($request->all());

        return back()->with('success', 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.');
    }

    /**
     * Tampilkan halaman pembuka PPDB.
     */
    public function ppdb()
    {
        $bukaPpdb = Setting::where('key', 'buka_ppdb')->value('value') ?? '1';

        if ($bukaPpdb !== '1') {
            return view('public.ppdb-closed');
        }

        return view('public.ppdb');
    }

    /**
     * Tampilkan formulir pendaftaran PPDB.
     */
    public function ppdbForm()
    {
        $bukaPpdb = Setting::where('key', 'buka_ppdb')->value('value') ?? '1';

        if ($bukaPpdb !== '1') {
            return view('public.ppdb-closed');
        }

        return view('public.ppdb-form');
    }

    /**
     * Simpan data pendaftar PPDB.
     */
    public function storePpdb(Request $request)
    {
        $bukaPpdb = Setting::where('key', 'buka_ppdb')->value('value') ?? '1';
        if ($bukaPpdb !== '1') {
            return back()->with('error', 'Pendaftaran PPDB saat ini sedang ditutup.');
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'nisn'         => 'required|string|max:20',
            'nik'          => 'required|string|max:20',
            'jenis_kelamin'=> 'required|in:Laki-laki,Perempuan',
            'jurusan'      => 'required|string|max:20',
            'no_hp'        => 'required|string|max:20',
            'asal_sekolah' => 'required|string|max:100',
        ]);

        // Generate No Registrasi unik: REG-YYYY-XXXX
        $lastId = Ppdb::max('id') ?? 0;
        $noReg  = 'REG-' . date('Y') . '-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        Ppdb::create([
            'no_registrasi'     => $noReg,
            'nama_lengkap'      => strtoupper($request->nama_lengkap),
            'nisn'              => $request->nisn,
            'nik'               => $request->nik,
            'jenis_kelamin'     => $request->jenis_kelamin === 'Laki-laki' ? 'L' : 'P',
            'tempat_lahir'      => strtoupper($request->tempat_lahir ?? ''),
            'tanggal_lahir'     => $request->tanggal_lahir ?: null,
            'alamat'            => strtoupper($request->alamat ?? ''),
            'asal_sekolah'      => strtoupper($request->asal_sekolah),
            'tahun_lulus'       => $request->tahun_lulus ?: date('Y'),
            'nama_ayah'         => strtoupper($request->nama_ayah ?? ''),
            'nama_ibu'          => strtoupper($request->nama_ibu ?? ''),
            'no_hp'             => $request->no_hp,
            'jurusan_pilihan'   => $request->jurusan,
            'status_pendaftaran'=> 'Pending',
            'tanggal_daftar'    => now(),
        ]);

        return back()->with('success', "Pendaftaran berhasil! Nomor Registrasi Anda: {$noReg}. Simpan nomor ini untuk keperluan verifikasi.");
    }
}
