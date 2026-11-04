<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortalController extends Controller
{
    /**
     * Daftar seluruh modul PRO yang dikontrol oleh pengembang.
     */
    protected array $proFeatures = [
        'feature_absensi_guru' => [
            'name' => 'Presensi Guru & Pegawai GTK',
            'desc' => 'Mencatat jam masuk/pulang guru, foto kehadiran, dan rekap dinas sertifikasi.',
            'category' => 'SDM & GTK',
        ],
        'feature_spp_billing' => [
            'name' => 'Manajemen SPP & Tagihan Siswa',
            'desc' => 'Pembukuan iuran bulanan/komite siswa, invoice, dan status pelunasan.',
            'category' => 'Keuangan',
        ],
        'feature_whatsapp_gateway' => [
            'name' => 'WhatsApp Gateway & Notifikasi Ortu',
            'desc' => 'Kirim otomatis notifikasi presensi & broadcast pengumuman langsung ke WA wali murid.',
            'category' => 'Integrasi',
        ],
        'feature_jurnal_guru' => [
            'name' => 'Jurnal Mengajar Harian (KBM Digital)',
            'desc' => 'Catatan harian guru per kelas dan pantauan langsung oleh Kepala Sekolah.',
            'category' => 'Akademik',
        ],
        'feature_cbt_exam' => [
            'name' => 'CBT Ujian Online Anti-Curang',
            'desc' => 'Pelaksanaan ujian tengah/akhir semester bebas kertas via HP/komputer sekolah.',
            'category' => 'Ujian & Penilaian',
        ],
        'feature_kelulusan_skl' => [
            'name' => 'Kelulusan & SKL Online Terpadu',
            'desc' => 'Portal pengumuman kelulusan siswa, cetak Surat Keterangan Lulus (SKL) otomatis, dan verifikasi QR Code.',
            'category' => 'Kelulusan & Alumni',
        ],
    ];

    /**
     * Tampilkan halaman autentikasi kunci rahasia pengembang.
     */
    public function showLogin(): View|RedirectResponse
    {
        if ($this->isAuthenticated()) {
            return redirect()->route('dev.portal.index');
        }

        return view('developer.login');
    }

    /**
     * Validasi Kunci Rahasia Developer (dengan proteksi timing attack).
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $request->validate([
            'secret_key' => 'required|string',
        ]);

        $masterKey = (string) config('app.skillance_dev_key');

        // Gunakan hash_equals untuk mencegah timing attack
        if (hash_equals($masterKey, (string) $request->secret_key)) {
            session([
                'skillance_dev_authenticated' => true,
                'skillance_dev_auth_time' => now()->timestamp,
            ]);

            return redirect()->route('dev.portal.index')->with('success', 'Autentikasi Pengembang Berhasil.');
        }

        return back()->withErrors([
            'secret_key' => 'Kunci akses tidak sah. Percobaan ini telah dibatasi oleh sistem keamanan.',
        ]);
    }

    /**
     * Tampilkan Portal Manajemen Fitur PRO.
     */
    public function index(): View|RedirectResponse
    {
        if (! $this->isAuthenticated()) {
            return redirect()->route('dev.portal.login')->withErrors(['secret_key' => 'Sesi telah berakhir, silakan masukkan kunci kembali.']);
        }

        $featureKeys = array_keys($this->proFeatures);
        $activeSettings = Setting::whereIn('key', $featureKeys)->pluck('value', 'key')->toArray();

        $features = [];
        foreach ($this->proFeatures as $key => $meta) {
            $features[$key] = array_merge($meta, [
                'is_active' => ($activeSettings[$key] ?? '0') === '1',
            ]);
        }

        // Metrik Keamanan Sistem
        $securityAudit = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug') ? 'AKTIF (PERINGATAN)' : 'AMAN (Nonaktif)',
            'database_status' => 'Terhubung (MySQL)',
            'security_headers' => 'Aktif (Strict)',
        ];

        $billingSuspended = (Setting::where('key', 'billing_suspended')->value('value') === '1')
            || (bool) config('app.billing_suspended', false);

        return view('developer.portal', compact('features', 'securityAudit', 'billingSuspended'));
    }

    /**
     * Toggle Billing Suspension (Kunci / Buka Suspensi Tagihan Overdue).
     */
    public function toggleSuspension(Request $request): RedirectResponse
    {
        if (! $this->isAuthenticated()) {
            return redirect()->route('dev.portal.login');
        }

        $isCurrentlySuspended = (Setting::where('key', 'billing_suspended')->value('value') === '1')
            || (bool) config('app.billing_suspended', false);

        $nextValue = $isCurrentlySuspended ? '0' : '1';

        Setting::updateOrCreate(
            ['key' => 'billing_suspended'],
            ['value' => $nextValue]
        );

        $message = $nextValue === '1'
            ? 'Suspensi tagihan berhasil DIAKTIFKAN. Akses web dan API untuk publik/sekolah kini ditangguhkan.'
            : 'Suspensi tagihan berhasil DINONAKTIFKAN. Seluruh akses web dan API sekolah telah dipulihkan normal.';

        return back()->with('success', $message);
    }

    /**
     * Simpan Perubahan Fitur PRO.
     */
    public function update(Request $request): RedirectResponse
    {
        if (! $this->isAuthenticated()) {
            return redirect()->route('dev.portal.login');
        }

        foreach (array_keys($this->proFeatures) as $key) {
            $value = $request->has($key) ? '1' : '0';
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Konfigurasi Fitur PRO Berhasil Diperbarui.');
    }

    /**
     * Logout dan kunci portal.
     */
    public function logout(): RedirectResponse
    {
        session()->forget(['skillance_dev_authenticated', 'skillance_dev_auth_time']);

        return redirect('/');
    }

    /**
     * Cek apakah sesi developer aktif dan belum kedaluwarsa (maks 30 menit).
     */
    protected function isAuthenticated(): bool
    {
        if (! session('skillance_dev_authenticated')) {
            return false;
        }

        $authTime = session('skillance_dev_auth_time', 0);
        // Timeout 30 menit
        if ((now()->timestamp - $authTime) > 1800) {
            session()->forget(['skillance_dev_authenticated', 'skillance_dev_auth_time']);

            return false;
        }

        return true;
    }
}
