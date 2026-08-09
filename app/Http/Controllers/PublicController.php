<?php

namespace App\Http\Controllers;

use App\Actions\Public\CheckPpdbStatusAction;
use App\Actions\Public\GetCalendarEventsAction;
use App\Actions\Public\GetLandingPageDataAction;
use App\Actions\Public\GetNewsDetailAction;
use App\Actions\Public\GetNewsListAction;
use App\Actions\Public\GetProfileDataAction;
use App\Actions\Public\ProcessPpdbRegistrationAction;
use App\Actions\Public\StoreContactMessageAction;
use App\Http\Requests\Public\StoreContactMessageRequest;
use App\Http\Requests\Public\StorePpdbRegistrationRequest;
use App\Models\Facility;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Teacher;

class PublicController extends Controller
{
    public function home(GetLandingPageDataAction $action)
    {
        $data = $action->execute();

        return view('landing', $data);
    }

    public function profil(GetProfileDataAction $action)
    {
        $data = $action->execute();

        return view('public.profile', $data);
    }

    // ─── Sub-menu PROFIL ───────────────────────────────────────────────────────

    public function profilSejarah()
    {
        return view('public.profil.sejarah');
    }

    public function profilVisiMisi()
    {
        return view('public.profil.visi-misi');
    }

    public function profilStruktur()
    {
        return view('public.profil.struktur');
    }

    public function profilGtk()
    {
        $teachers = Teacher::where('status', 'Active')->get();
        return view('public.profil.gtk', compact('teachers'));
    }

    public function profilSarana()
    {
        $facility = Facility::all();
        return view('public.profil.sarana', compact('facility'));
    }

    // ─── Sub-menu BERITA ───────────────────────────────────────────────────────

    public function berita(GetNewsListAction $action)
    {
        $posts = $action->execute();

        return view('public.news.news', compact('posts'));
    }

    public function beritaKegiatan()
    {
        $posts = Post::where('is_published', true)
            ->where('category', 'kegiatan')
            ->latest()
            ->paginate(9);
        // fallback jika tidak ada kolom category — tampilkan semua
        if ($posts->isEmpty()) {
            $posts = Post::where('is_published', true)->latest()->paginate(9);
        }
        return view('public.berita.kegiatan', compact('posts'));
    }

    public function galeri()
    {
        $posts = Post::where('is_published', true)
            ->whereNotNull('image')
            ->latest()
            ->paginate(18);
        return view('public.berita.galeri', compact('posts'));
    }

    public function infoNews()
    {
        $posts = Post::where('is_published', true)
            ->where('category', 'info')
            ->latest()
            ->paginate(9);
        if ($posts->isEmpty()) {
            $posts = Post::where('is_published', true)->latest()->paginate(9);
        }
        return view('public.berita.info-penting', compact('posts'));
    }

    public function showBerita(string $slug, GetNewsDetailAction $action)
    {
        $data = $action->execute($slug);

        return view('public.news.news-show', $data);
    }

    // ─── E-LEARNING ───────────────────────────────────────────────────────────

    public function webGuru()
    {
        // Redirect ke URL Web Guru yang dikonfigurasi di settings, atau halaman info
        $url = Setting::where('key', 'web_guru_url')->value('value');
        if ($url) {
            return redirect($url);
        }
        return view('public.elearning.web-guru');
    }

    public function eDokumen()
    {
        return view('public.elearning.e-dokumen');
    }

    // ─── PERPUSTAKAAN ─────────────────────────────────────────────────────────

    public function perpustakaan()
    {
        return view('public.perpustakaan');
    }

    // ─── KONTAK ───────────────────────────────────────────────────────────────

    public function kontak()
    {
        return view('public.contact');
    }

    public function jadwal()
    {
        return view('public.timetable');
    }

    public function kalender(GetCalendarEventsAction $action)
    {
        $eventsByMonth = $action->execute();

        return view('public.calender', compact('eventsByMonth'));
    }

    public function storeContact(
        StoreContactMessageRequest $request,
        StoreContactMessageAction $action,
    ) {
        $action->execute($request->validated());

        return back()->with(
            'success',
            'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.',
        );
    }

    /**
     * Tampilkan halaman pembuka PPDB.
     */
    public function ppdb(CheckPpdbStatusAction $action)
    {
        if (! $action->execute()) {
            return view('public.ppdb-closed');
        }

        return view('public.ppdb');
    }

    /**
     * Tampilkan formulir pendaftaran PPDB.
     */
    public function ppdbForm(CheckPpdbStatusAction $action)
    {
        if (! $action->execute()) {
            return view('public.ppdb-closed');
        }

        return view('public.ppdb-form');
    }

    /**
     * Simpan data pendaftar PPDB.
     */
    public function storePpdb(
        StorePpdbRegistrationRequest $request,
        CheckPpdbStatusAction $checkPpdbStatusAction,
        ProcessPpdbRegistrationAction $action,
    ) {
        if (! $checkPpdbStatusAction->execute()) {
            return back()->with(
                'error',
                'Pendaftaran PPDB saat ini sedang ditutup.',
            );
        }

        $noReg = $action->execute($request->validated());

        return back()->with(
            'success',
            "Pendaftaran berhasil! Nomor Registrasi Anda: {$noReg}. Simpan nomor ini untuk keperluan verifikasi.",
        );
    }
}
