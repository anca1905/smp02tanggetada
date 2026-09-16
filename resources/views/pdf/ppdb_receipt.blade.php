<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pendaftaran PPDB - {{ $ppdb->nama_lengkap }} ({{ $ppdb->no_registrasi }})</title>
    @php
        $schoolName = $site_settings['school_name'] ?? ($site_settings['app_name'] ?? 'SMP 02 TANGGETADA');
        $schoolAddress = $site_settings['school_address'] ?? 'Jl. Poros Tanggetada, Kabupaten Kolaka, Sulawesi Tenggara';
        $contact = $site_settings['school_phone'] ?? ($site_settings['school_email'] ?? 'info@smp02tanggetada.sch.id');

        $photoSrc = null;
        if (!empty($ppdb->doc_pas_photo)) {
            $photoPath = storage_path('app/public/' . $ppdb->doc_pas_photo);
            if (file_exists($photoPath)) {
                $photoSrc = 'data:image/' . pathinfo($photoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($photoPath));
            }
        }
    @endphp

    <style>
        @page {
            size: a4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #1f2937;
            line-height: 1.35;
        }

        /* Kop Surat */
        .kop-table {
            width: 100%;
            border-bottom: 2pt solid #111827;
            padding-bottom: 6pt;
            margin-bottom: 12pt;
            border-collapse: collapse;
        }
        .kop-text {
            text-align: center;
        }
        .kop-title {
            font-size: 14pt;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }
        .kop-subtitle {
            font-size: 10pt;
            font-weight: bold;
            color: #374151;
            margin-top: 1pt;
            text-transform: uppercase;
        }
        .kop-address {
            font-size: 7.5pt;
            color: #6b7280;
            margin-top: 2pt;
        }

        /* Title */
        .doc-header {
            text-align: center;
            margin-bottom: 14pt;
        }
        .doc-title {
            font-size: 12pt;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
            border-bottom: 1pt solid #cbd5e1;
            display: inline-block;
            padding-bottom: 2pt;
        }

        /* Top Box (Reg + Barcode + Photo) */
        .header-box {
            width: 100%;
            border: 1pt solid #cbd5e1;
            background: #f8fafc;
            border-collapse: collapse;
            margin-bottom: 12pt;
        }
        .header-box td {
            padding: 8pt;
            vertical-align: middle;
        }
        .reg-number {
            font-size: 13pt;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 1pt;
        }
        .reg-date {
            font-size: 8pt;
            color: #475569;
            margin-top: 2pt;
        }
        .reg-barcode {
            text-align: center;
        }
        .barcode-img {
            max-width: 140pt;
            height: 28pt;
        }
        .photo-td {
            width: 65pt;
            text-align: center;
        }
        .photo-box {
            width: 55pt;
            height: 70pt;
            border: 1pt solid #94a3b8;
            background: #ffffff;
            margin: 0 auto;
            text-align: center;
            overflow: hidden;
        }
        .photo-img {
            width: 55pt;
            height: 70pt;
            display: block;
        }
        .photo-empty {
            font-size: 7pt;
            color: #94a3b8;
            padding-top: 25pt;
        }

        /* Section Headings */
        .section-title {
            font-size: 9.5pt;
            font-weight: bold;
            color: #0f172a;
            background-color: #e2e8f0;
            padding: 3pt 6pt;
            margin-top: 8pt;
            margin-bottom: 5pt;
            border-left: 3pt solid #1e3a8a;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8pt;
            font-size: 8.5pt;
        }
        .data-table td {
            padding: 2.5pt 4pt;
            vertical-align: top;
        }
        .col-label {
            width: 130pt;
            color: #475569;
        }
        .col-sep {
            width: 8pt;
            color: #64748b;
        }
        .col-val {
            color: #0f172a;
            font-weight: 500;
        }

        /* Checklist Requirements */
        .checklist-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4pt;
            margin-bottom: 10pt;
            font-size: 8pt;
        }
        .checklist-table td {
            padding: 2.5pt 4pt;
            vertical-align: top;
        }
        .check-box {
            width: 12pt;
            font-family: monospace;
            font-size: 9pt;
            font-weight: bold;
        }

        /* Notes Box */
        .notice-box {
            background-color: #fefce8;
            border: 1pt solid #fef08a;
            padding: 5pt 8pt;
            font-size: 7.5pt;
            color: #854d0e;
            margin-bottom: 12pt;
            border-radius: 2pt;
        }

        /* Signatures */
        .sign-table {
            width: 100%;
            margin-top: 15pt;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .sign-table td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            font-size: 8.5pt;
        }
        .sign-space {
            height: 40pt;
        }
        .sign-name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- KOP -->
    <table class="kop-table">
        <tr>
            <td class="kop-text">
                <div class="kop-title">{{ $schoolName }}</div>
                <div class="kop-subtitle">PANITIA PENERIMAAN PESERTA DIDIK BARU (PPDB)</div>
                <div class="kop-address">{{ $schoolAddress }} | Kontak: {{ $contact }}</div>
            </td>
        </tr>
    </table>

    <!-- JUDUL -->
    <div class="doc-header">
        <div class="doc-title">BUKTI PENDAFTARAN SISWA BARU</div>
    </div>

    <!-- BOX REGISTRASI -->
    <table class="header-box">
        <tr>
            <td style="width: 50%;">
                <div style="font-size: 7.5pt; color: #64748b; text-transform: uppercase;">No. Registrasi:</div>
                <div class="reg-number">{{ $ppdb->no_registrasi }}</div>
                <div class="reg-date">
                    Tgl Daftar: {{ \Carbon\Carbon::parse($ppdb->tanggal_daftar)->isoFormat('dddd, D MMMM Y') }}
                </div>
            </td>
            <td class="reg-barcode" style="width: 32%;">
                <img src="{{ $barcodeSvg }}" alt="Barcode" class="barcode-img">
                <div style="font-size: 7pt; letter-spacing: 1pt; margin-top: 1pt;">* {{ $ppdb->no_registrasi }} *</div>
            </td>
            <td class="photo-td">
                <div class="photo-box">
                    @if ($photoSrc)
                        <img src="{{ $photoSrc }}" alt="Pas Foto" class="photo-img">
                    @else
                        <div class="photo-empty">FOTO<br>3 x 4</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- A. DATA CALON SISWA -->
    <div class="section-title">A. DATA PRIBADI CALON SISWA</div>
    <table class="data-table">
        <tr>
            <td class="col-label">Nama Lengkap</td>
            <td class="col-sep">:</td>
            <td class="col-val"><strong>{{ $ppdb->nama_lengkap }}</strong></td>
        </tr>
        <tr>
            <td class="col-label">NISN / NIK</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $ppdb->nisn ?: '-' }} / {{ $ppdb->nik ?: '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Tempat, Tanggal Lahir</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $ppdb->tempat_lahir }}, {{ $ppdb->tanggal_lahir ? \Carbon\Carbon::parse($ppdb->tanggal_lahir)->isoFormat('D MMMM Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Jenis Kelamin</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $ppdb->jenis_kelamin_label }}</td>
        </tr>
        <tr>
            <td class="col-label">Agama</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $ppdb->agama ?: '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Asal Sekolah</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $ppdb->asal_sekolah ?: '-' }} (Lulus: {{ $ppdb->tahun_lulus ?: '-' }})</td>
        </tr>
        <tr>
            <td class="col-label">Alamat Lengkap</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $ppdb->alamat ?: '-' }}</td>
        </tr>
    </table>

    <!-- B. DATA ORANG TUA / WALI -->
    <div class="section-title">B. DATA ORANG TUA / WALI</div>
    <table class="data-table">
        <tr>
            <td class="col-label">Nama Ayah Kandung</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $ppdb->nama_ayah ?: '-' }} (Pekerjaan: {{ $ppdb->pekerjaan_ayah ?: '-' }})</td>
        </tr>
        <tr>
            <td class="col-label">Nama Ibu Kandung</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $ppdb->nama_ibu ?: '-' }} (Pekerjaan: {{ $ppdb->pekerjaan_ibu ?: '-' }})</td>
        </tr>
        @if ($ppdb->nama_wali)
        <tr>
            <td class="col-label">Nama Wali</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $ppdb->nama_wali }} (Pekerjaan: {{ $ppdb->pekerjaan_wali ?: '-' }})</td>
        </tr>
        @endif
        <tr>
            <td class="col-label">No. Handphone / WhatsApp</td>
            <td class="col-sep">:</td>
            <td class="col-val">{{ $ppdb->no_hp ?: '-' }}</td>
        </tr>
    </table>

    <!-- C. KELENGKAPAN BERKAS FISIK UNTUK VERIFIKASI -->
    <div class="section-title">C. KELENGKAPAN BERKAS VERIFIKASI FISIK (Diisi Petugas)</div>
    <table class="checklist-table">
        <tr>
            <td class="check-box">[ &nbsp; ]</td>
            <td>Cetak Bukti Pendaftaran Online ini (1 lembar)</td>
            <td class="check-box">[ &nbsp; ]</td>
            <td>Fotokopi Akta Kelahiran & Kartu Keluarga (KK)</td>
        </tr>
        <tr>
            <td class="check-box">[ &nbsp; ]</td>
            <td>Pas Foto Berwarna ukuran 3x4 (3 lembar)</td>
            <td class="check-box">[ &nbsp; ]</td>
            <td>Fotokopi KTP Orang Tua (Ayah & Ibu)</td>
        </tr>
        <tr>
            <td class="check-box">[ &nbsp; ]</td>
            <td>Fotokopi Ijazah SD / Surat Keterangan Lulus (SKL) Legalisir</td>
            <td class="check-box">[ &nbsp; ]</td>
            <td>Fotokopi KIP / PIP / PKH (jika ada)</td>
        </tr>
    </table>

    <div class="notice-box">
        <strong>PERHATIAN:</strong> Harap membawa lembar bukti pendaftaran ini beserta seluruh kelengkapan berkas fisik ke Sekretariat PPDB sekolah untuk tahap verifikasi berkas dan validasi data.
    </div>

    <!-- TANDA TANGAN -->
    <table class="sign-table">
        <tr>
            <td>
                <div>Calon Siswa / Orang Tua,</div>
                <div class="sign-space"></div>
                <div class="sign-name">{{ $ppdb->nama_lengkap }}</div>
            </td>
            <td>
                <div>Tanggetada, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</div>
                <div>Panitia PPDB / Petugas TU,</div>
                <div class="sign-space"></div>
                <div class="sign-name">Panitia Penerimaan</div>
            </td>
        </tr>
    </table>

</body>
</html>
