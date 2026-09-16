# Task 07: Nonaktifkan Menu Fasilitas, Sarpras, & Tombol Kiosk Presensi

> **Ref Brief:** Poin #3, #4, #9  
> **Tingkat Urgensi:** Prioritas 3 (Pembersihan UI & Navigasi)

---

## 1. Lingkup Kebutuhan
1. **Poin #3:** Nonaktifkan menu **"Fasilitas Sekolah"** di dashboard admin (`resources/views/layouts/app.blade.php` atau `tu.blade.php`) dan di landing page / navbar publik jika ada.
2. **Poin #4:** Nonaktifkan menu **"Sarana dan Prasarana"** di navbar publik / footer / sub-menu profil sekolah (`resources/views/layouts/public.blade.php`).
3. **Poin #9:** Hapus / sembunyikan tombol **"Kiosk Presensi"** di Hero / Call-to-Action Landing Page (`resources/views/landing.blade.php`) dan halaman login (`resources/views/layouts/auth.blade.php`).

---

## 2. Berkas yang Terlibat
- `resources/views/layouts/app.blade.php` (sidebar admin TU)
- `resources/views/layouts/tu.blade.php`
- `resources/views/layouts/public.blade.php` (navbar & footer profil)
- `resources/views/landing.blade.php` (hero section action buttons)
- `resources/views/layouts/auth.blade.php`
- `routes/web.php` (opsional: proteksi atau redirect rute sarana/fasilitas jika dinonaktifkan)

---

## 3. Langkah-Langkah Pengerjaan

### Langkah 1: Sembunyikan Menu Fasilitas di Sidebar Admin
Di `resources/views/layouts/app.blade.php`:
- Hapus atau komentari item menu rute `tu.facility.index`:
```blade
{{-- Dinonaktifkan sesuai brief #3 --}}
{{-- <a href="{{ route('tu.facility.index') }}" ...>Fasilitas Sekolah</a> --}}
```

### Langkah 2: Sembunyikan Sarana & Prasarana di Landing Page
Di `resources/views/layouts/public.blade.php`:
- Hapus item menu dropdown Profil "Sarana & Prasarana" (desktop dan mobile navigation).
- Jika ada section fasilitas di halaman utama `landing.blade.php`, sembunyikan section tersebut agar tidak menampilkan section kosong.

### Langkah 3: Hapus Tombol Kiosk Presensi di Landing Page & Login
Di `resources/views/landing.blade.php`:
- Hapus tombol tautan `<a href="{{ route('presensi.index') }}" ...>Kiosk Presensi</a>` dari banner/hero. Sisakan tombol utama seperti "Daftar PPDB" atau "Informasi Sekolah".
Di `resources/views/layouts/auth.blade.php`:
- Hapus tautan kiosk presensi jika ada di footer halaman auth.

---

## 4. Exit Criteria (Definisi Selesai)
- [ ] Tautan "Fasilitas Sekolah" tidak lagi tampil di navigasi admin TU.
- [ ] Tautan "Sarana & Prasarana" tidak lagi muncul di menu navbar maupun footer publik.
- [ ] Tombol "Kiosk Presensi" tidak terlihat di Landing Page publik.
- [ ] Tidak ada error layout atau link rusak (broken links) akibat penghapusan elemen.
