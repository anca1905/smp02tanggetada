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
use App\Http\Requests\Extracurricular\StoreExtracurricularRegistrationRequest;
use App\Http\Requests\Graduation\CheckGraduationRequest;
use App\Http\Requests\GuestBook\StoreGuestBookRequest;
use App\Http\Requests\Public\StoreContactMessageRequest;
use App\Http\Requests\Public\StorePpdbRegistrationRequest;
use App\Models\Achievement;
use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use App\Models\Graduation;
use App\Models\GuestBook;
use App\Models\LibraryBook;
use App\Models\Post;
use App\Models\Ppdb;
use App\Models\Setting;
use App\Models\Teacher;
use App\Services\BarcodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    public function profilSarana(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('public.profil');
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
        $dokumens = \App\Models\EDokumen::all()->groupBy('category');

        return view('public.elearning.e-dokumen', compact('dokumens'));
    }

    // ─── PERPUSTAKAAN ─────────────────────────────────────────────────────────

    public function perpustakaan(Request $request)
    {
        $site_settings = Setting::pluck('value', 'key')->toArray();

        $booksQuery = LibraryBook::query()->latest('id');

        if ($request->filled('q')) {
            $q = $request->query('q');
            $booksQuery->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('author', 'like', "%{$q}%")
                    ->orWhere('book_code', 'like', "%{$q}%")
                    ->orWhere('isbn', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category') && $request->query('category') !== 'all') {
            $booksQuery->where('category', $request->query('category'));
        }

        if ($request->filled('class_level') && $request->query('class_level') !== 'all') {
            $booksQuery->where('class_level', $request->query('class_level'));
        }

        $books = $booksQuery->paginate(12)->withQueryString();

        $stats = [
            'total_titles' => LibraryBook::count(),
            'total_stock' => (int) LibraryBook::sum('total_stock'),
            'bos_count' => LibraryBook::where('category', 'buku_paket_bos')->count(),
            'lit_count' => LibraryBook::whereIn('category', ['fiksi', 'non_fiksi', 'referensi'])->count(),
        ];

        return view('public.perpustakaan', compact('books', 'stats', 'site_settings'));
    }

    // ─── EKSTRAKURIKULER & OSIS ───────────────────────────────────────────────

    public function ekstrakurikuler(Request $request)
    {
        $site_settings = Setting::pluck('value', 'key')->toArray();

        $query = Extracurricular::where('is_active', true)->withCount('acceptedRegistrations')->latest('id');

        if ($request->filled('category') && $request->query('category') !== 'all') {
            $query->where('category', $request->query('category'));
        }

        $extracurriculars = $query->get();

        return view('public.ekstrakurikuler', compact('extracurriculars', 'site_settings'));
    }

    public function storeEkstrakurikuler(StoreExtracurricularRegistrationRequest $request)
    {
        $data = $request->validated();
        $data['status'] = 'pending';

        ExtracurricularRegistration::create($data);

        return back()->with('success', 'Formulir pendaftaran ekstrakurikuler berhasil dikirim! Silakan menunggu konfirmasi dari pembina ekskul atau staf kesiswaan.');
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

        return back()
            ->with('success', "Pendaftaran berhasil! Nomor Registrasi Anda: {$noReg}. Simpan nomor ini untuk keperluan verifikasi.")
            ->with('no_registrasi', $noReg);
    }

    /**
     * Download bukti pendaftaran PPDB untuk pendaftar (publik).
     */
    public function downloadPpdbReceipt(string $no_registrasi): Response
    {
        $ppdb = Ppdb::where('no_registrasi', $no_registrasi)->firstOrFail();
        $site_settings = Setting::pluck('value', 'key')->toArray();
        $barcodeSvg = BarcodeService::generateBase64Svg($ppdb->no_registrasi, 1, 35);

        $pdf = Pdf::loadView('pdf.ppdb_receipt', compact('ppdb', 'site_settings', 'barcodeSvg'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("bukti-pendaftaran-{$ppdb->no_registrasi}.pdf");
    }

    /**
     * Galeri Prestasi Siswa (Hall of Fame) Publik
     */
    public function prestasi(Request $request)
    {
        $query = Achievement::query()
            ->latest('event_date')
            ->latest('id');

        if ($request->filled('level')) {
            $query->where('level', $request->query('level'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('student_name', 'like', "%{$search}%")
                    ->orWhere('event_name', 'like', "%{$search}%")
                    ->orWhere('rank', 'like', "%{$search}%");
            });
        }

        $achievements = $query->paginate(12)->withQueryString();
        $featuredAchievements = Achievement::where('is_featured', true)->latest('event_date')->take(3)->get();

        return view('public.achievements', compact('achievements', 'featuredAchievements'));
    }

    /**
     * Halaman Portal Pengumuman Kelulusan Siswa
     */
    public function kelulusan()
    {
        $status = Setting::where('key', 'graduation_status')->value('value') ?? '1';
        $announcementDate = Setting::where('key', 'graduation_announcement_date')->value('value') ?? null;
        $site_settings = Setting::pluck('value', 'key')->toArray();

        return view('public.kelulusan', compact('status', 'announcementDate', 'site_settings'));
    }

    /**
     * Proses Cek Kelulusan Siswa Mandiri
     */
    public function cekKelulusan(CheckGraduationRequest $request)
    {
        $status = Setting::where('key', 'graduation_status')->value('value') ?? '1';
        $announcementDate = Setting::where('key', 'graduation_announcement_date')->value('value') ?? null;

        // Cek jika pengumuman belum dibuka
        if ($status !== '1' && $announcementDate && now()->lt(\Carbon\Carbon::parse($announcementDate))) {
            return back()->with('error', 'Pengumuman kelulusan belum dibuka. Silakan tunggu jadwal resmi.');
        }

        $identity = trim($request->validated('identity'));
        $birthDate = $request->validated('birth_date');

        $query = Graduation::query()->where(function ($q) use ($identity) {
            $q->where('nisn', $identity)
                ->orWhere('nis', $identity);
        });

        if ($birthDate) {
            $query->whereDate('birth_date', $birthDate);
        }

        $graduation = $query->first();

        if (! $graduation) {
            return back()->with('error', "Data siswa dengan NISN / NIS '{$identity}' tidak ditemukan pada daftar pengumuman kelulusan. Mohon periksa kembali nomor yang Anda masukkan.");
        }

        $site_settings = Setting::pluck('value', 'key')->toArray();

        return view('public.kelulusan_result', compact('graduation', 'site_settings'));
    }

    /**
     * Download Surat Keterangan Lulus (SKL) PDF Resmi
     */
    public function downloadSkl(Graduation $graduation): Response
    {
        $status = Setting::where('key', 'graduation_status')->value('value') ?? '1';
        $announcementDate = Setting::where('key', 'graduation_announcement_date')->value('value') ?? null;

        if ($status !== '1' && $announcementDate && now()->lt(\Carbon\Carbon::parse($announcementDate))) {
            abort(403, 'Pengunduhan SKL belum dibuka.');
        }

        if ($graduation->status !== 'lulus') {
            abort(403, 'SKL hanya dapat diunduh untuk siswa dengan status LULUS.');
        }

        $site_settings = Setting::pluck('value', 'key')->toArray();
        $headmasterName = Setting::where('key', 'graduation_headmaster_name')->value('value') ?? 'Drs. H. Mulyadi, M.Pd.';
        $headmasterNip = Setting::where('key', 'graduation_headmaster_nip')->value('value') ?? '19720315 199802 1 004';

        $barcodeSvg = BarcodeService::generateBase64Svg($graduation->verification_code, 2, 45);

        $pdf = Pdf::loadView('pdf.skl_document', compact('graduation', 'site_settings', 'barcodeSvg', 'headmasterName', 'headmasterNip'))
            ->setPaper('a4', 'portrait');

        $safeName = str_replace([' ', '/', '\\'], '_', $graduation->student_name);

        return $pdf->download("SKL_{$graduation->nis}_{$safeName}.pdf");
    }

    /**
     * Verifikasi Keaslian Dokumen SKL via Scan Barcode/QR
     */
    public function verifySkl(string $code)
    {
        $graduation = Graduation::where('verification_code', $code)->firstOrFail();
        $site_settings = Setting::pluck('value', 'key')->toArray();

        return view('public.kelulusan_verify', compact('graduation', 'site_settings'));
    }

    /**
     * Halaman Pengisian Buku Tamu Digital
     */
    public function bukuTamu()
    {
        $site_settings = Setting::pluck('value', 'key')->toArray();

        return view('public.guest_book', compact('site_settings'));
    }

    /**
     * Simpan Buku Tamu Digital dari Pengunjung
     */
    public function storeBukuTamu(StoreGuestBookRequest $request)
    {
        $data = $request->validated();
        if (empty($data['visit_time'])) {
            $data['visit_time'] = now()->format('H:i');
        }

        GuestBook::create($data);

        return back()->with('success', 'Terima kasih! Kunjungan Anda telah berhasil dicatat pada Buku Tamu Digital SMP 02 Tanggetada.');
    }
}
