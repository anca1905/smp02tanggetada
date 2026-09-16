# Task 08: Ganti Fitur Cetak (window.print) Menjadi Export PDF

> **Ref Brief:** Poin #6  
> **Tingkat Urgensi:** Prioritas 4 (Reporting & Export Dokumen)

---

## 1. Masalah & Kebutuhan
Saat ini, fitur cetak pada sistem (seperti Cetak Kartu Pelajar dan Cetak Rekap Absensi Guru/Siswa) masih mengandalkan tombol cetak browser sisi klien (`onclick="window.print()"`).
Pengguna membutuhkan tombol **"Export PDF"** yang menghasilkan berkas `.pdf` langsung untuk diunduh, disimpan, atau diarsipkan secara rapi.

Modul-modul target cetak:
1. **Kartu Pelajar:** `tu.student_card` (`resources/views/tu/student_card.blade.php`)
2. **Rekap Absensi:** `tu.rekap` (`resources/views/tu/absenteeism_recap.blade.php`)
3. **Bukti Pendaftaran PPDB:** Halaman cetak bukti registrasi calon siswa

---

## 2. Kebutuhan Library Tambahan (PENTING: Memerlukan Konfirmasi User)
Untuk meng-generate file PDF di sisi backend PHP/Laravel, saat ini aplikasi **belum** memiliki library PDF.
- **Rekomendasi Paket:** `barryvdh/laravel-dompdf` (DomPDF wrapper untuk Laravel, paling ringan, tidak memerlukan dependency headless Chrome/Node).
- **Pertanyaan Konfirmasi:** Wajib menanyakan persetujuan user sebelum menjalankan `composer require barryvdh/laravel-dompdf`.

---

## 3. Berkas yang Terlibat
- `composer.json` (jika disetujui memasang package PDF)
- Controller:
  - `app/Http/Controllers/AdminTu/StudentController.php` (export kartu pelajar PDF)
  - `app/Http/Controllers/AdminTu/RecapController.php` (export rekap absensi PDF)
  - `app/Http/Controllers/PublicController.php` (export bukti pendaftaran PPDB)
- Template PDF Blade:
  - `resources/views/pdf/student_card.blade.php`
  - `resources/views/pdf/attendance_recap.blade.php`
  - `resources/views/pdf/ppdb_receipt.blade.php`
- Views Asal (Ganti tombol `window.print()` menjadi tombol download PDF):
  - `resources/views/tu/student_card.blade.php`
  - `resources/views/tu/absenteeism_recap.blade.php`

---

## 4. Langkah-Langkah Implementasi

### Langkah 1: Konfirmasi dan Instalasi Library
- Ajukan persetujuan user untuk menambah `barryvdh/laravel-dompdf`.
- Jalankan instalasi jika disetujui:
  ```bash
  composer require barryvdh/laravel-dompdf
  ```

### Langkah 2: Buat Template Tampilan Khusus PDF
- Buat file Blade di direktori `resources/views/pdf/`.
- Gunakan inline CSS atau CSS kompatibel DomPDF (hindari script JS eksternal di dalam template render PDF).
- Untuk barcode di kartu pelajar PDF: encode barcode/QR menjadi base64 image PNG/SVG agar langsung ter-render tanpa JavaScript runtime.

### Langkah 3: Buat Endpoint Export PDF
Contoh pada `StudentController.php`:
```php
public function exportCardPdf(Student $student)
{
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.student_card', compact('student'))
        ->setPaper([0, 0, 242.64, 153.07], 'landscape'); // Ukuran ID card (85.6mm x 54mm)
        
    return $pdf->download("kartu-pelajar-{$student->nis}.pdf");
}
```

### Langkah 4: Hubungkan Tombol di View
- Ganti tombol `onclick="window.print()"` menjadi link/button yang mengarah ke route export PDF terkait.

---

## 5. Exit Criteria (Definisi Selesai)
- [x] Tombol pada modul terkait memicu pengunduhan file `.pdf` yang valid (bukan dialog print browser).
- [x] Berkas PDF yang diunduh memiliki tata letak yang rapi dan proporsional (ukuran ID Card / A4 sesuai dokumen).
- [x] Header HTTP mengembalikan `Content-Type: application/pdf`.
- [x] Test HTTP download response menghasilkan status 200 OK.
