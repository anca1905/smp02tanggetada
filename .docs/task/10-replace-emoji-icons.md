# Task 10: Penggantian Icon Emoji Menjadi SVG / FontAwesome

> **Ref Brief:** Poin #5, #12  
> **Tingkat Urgensi:** Prioritas 4 (Standardisasi Visual & Polish UI)

---

## 1. Masalah & Temuan
1. **Poin #5:** Pada badge sesi di tabel Rekap Absensi (`resources/views/tu/absenteeism_recap.blade.php`), sesi apel, kelas, dan pulang masih menggunakan karakter emoji (`🌅 Apel Pagi`, `🏫 Di Kelas`, `🏠 Pulang`).
2. **Poin #12:** Beberapa tampilan dan dropdown form presensi masih memuat icon emoji (seperti `🌅`, `🏫`, `🏠`, `👋`). Tampilan emoji tidak konsisten di berbagai sistem operasi (misal Windows vs Android vs macOS).

---

## 2. Solusi & Pendekatan Library
- **Status Library:** FontAwesome 6 sudah terpasang via CDN di layout utama (`layouts/app.blade.php`, `layouts/student.blade.php`, `layouts/public.blade.php`).
- **Pendekatan:** Manfaatkan icon FontAwesome yang sudah tersedia (atau inline SVG Tailwind) **tanpa perlu menginstal package/library baru**.
- **Pemetaan Icon:**
  - `🌅 Apel Pagi` $\rightarrow$ `<i class="fas fa-sun text-yellow-600"></i> Apel Pagi`
  - `🏫 Di Kelas` $\rightarrow$ `<i class="fas fa-chalkboard-user text-blue-600"></i> Di Kelas`
  - `🏠 Pulang` $\rightarrow$ `<i class="fas fa-door-open text-green-600"></i> Pulang`
  - Sapaan `👋` $\rightarrow$ Ganti dengan icon `<i class="far fa-hand-wave text-amber-500"></i>` atau inline SVG wave yang seragam.

---

## 3. Berkas yang Terlibat
- `resources/views/tu/absenteeism_recap.blade.php` (badge sesi dan dropdown filter)
- `resources/views/presence.blade.php` (dropdown sesi scanner kiosk)
- `resources/views/student_presence.blade.php` (dropdown sesi presensi guru)
- `resources/views/tu/index.blade.php` (sapaan dashboard)
- `resources/views/teacher/index.blade.php` (sapaan guru)
- `resources/views/student/lms/index.blade.php` (sapaan siswa)

---

## 4. Langkah-Langkah Pengerjaan

### Langkah 1: Ganti Badge pada Rekap Absensi
Di `resources/views/tu/absenteeism_recap.blade.php`:
Ubah array mapping sesi:
```blade
@php
    $sesiMap = [
        'apel'   => ['icon' => 'fa-sun', 'label' => 'Apel Pagi', 'class' => 'bg-amber-50 text-amber-800 border-amber-200'],
        'kelas'  => ['icon' => 'fa-chalkboard-user', 'label' => 'Di Kelas', 'class' => 'bg-blue-50 text-blue-800 border-blue-200'],
        'pulang' => ['icon' => 'fa-house-user', 'label' => 'Pulang', 'class' => 'bg-emerald-50 text-emerald-800 border-emerald-200'],
    ];
@endphp
```
Render badge dengan tag `<i>` FontAwesome.

### Langkah 2: Bersihkan Emoji di Seluruh View Terdeteksi
- Bersihkan pilihan dropdown `<option>` di `presence.blade.php`, `student_presence.blade.php`, dan `tu/absenteeism_recap.blade.php`.
- Bersihkan emoji sapaan di dashboard Admin TU, Guru, dan Siswa.

### Langkah 3: Verifikasi Visual
Pastikan seluruh icon ter-render dengan rapi dan tidak ada kotak broken font pada sistem klien.

---

## 5. Exit Criteria (Definisi Selesai)
- [ ] Seluruh badge sesi di Rekap Absensi menggunakan icon FontAwesome / SVG yang proporsional.
- [ ] Tidak ada lagi karakter emoji pada teks badge dan dropdown form presensi.
- [ ] Tampilan konsisten di seluruh browser dan sistem operasi.
