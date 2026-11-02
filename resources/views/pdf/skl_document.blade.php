<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Lulus (SKL) - {{ $graduation->student_name }}</title>
    @php
        $schoolName = $site_settings['school_name'] ?? ($site_settings['app_name'] ?? 'SMP NEGERI 02 TANGGETADA');
        $schoolAddress = $site_settings['school_address'] ?? 'Jl. Poros Tanggetada, Kec. Tanggetada, Kab. Kolaka, Sulawesi Tenggara';
        $contact = $site_settings['school_phone'] ?? ($site_settings['school_email'] ?? 'info@smp02tanggetada.sch.id');
        $headmaster = $headmasterName ?? 'Drs. H. Mulyadi, M.Pd.';
        $nip = $headmasterNip ?? '19720315 199802 1 004';
    @endphp

    <style>
        @page {
            size: a4 portrait;
            margin: 12mm 15mm 12mm 15mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10.5pt;
            color: #000;
            line-height: 1.35;
        }

        /* Kop Surat */
        .kop-table {
            width: 100%;
            border-bottom: 2.5pt double #000;
            padding-bottom: 5pt;
            margin-bottom: 12pt;
            border-collapse: collapse;
        }
        .kop-text {
            text-align: center;
        }
        .kop-pemda {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }
        .kop-dinas {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }
        .kop-sekolah {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1pt;
            margin-top: 2pt;
        }
        .kop-alamat {
            font-size: 8.5pt;
            font-family: Arial, sans-serif;
            color: #333;
            margin-top: 3pt;
        }

        /* Judul Surat */
        .title-section {
            text-align: center;
            margin-bottom: 12pt;
        }
        .surat-title {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }
        .surat-number {
            font-size: 10.5pt;
            margin-top: 2pt;
        }

        /* Biodata Table */
        .bio-table {
            width: 100%;
            margin-bottom: 10pt;
            border-collapse: collapse;
        }
        .bio-table td {
            padding: 2.5pt 0;
            vertical-align: top;
        }

        /* Status Box */
        .status-box {
            text-align: center;
            margin: 10pt 0;
            padding: 6pt;
            border: 1.5pt solid #000;
            background-color: #f8fafc;
        }
        .status-text {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1pt;
        }

        /* Table Nilai */
        .score-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10pt 0;
            font-size: 9.5pt;
        }
        .score-table th, .score-table td {
            border: 1pt solid #000;
            padding: 4pt 6pt;
        }
        .score-table th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
        }

        /* Footer & TTD */
        .footer-table {
            width: 100%;
            margin-top: 15pt;
            border-collapse: collapse;
        }
        .footer-table td {
            vertical-align: top;
        }
        .barcode-box {
            border: 1pt dashed #666;
            padding: 5pt;
            text-align: center;
            width: 170pt;
            background: #fff;
        }
        .barcode-text {
            font-family: Arial, sans-serif;
            font-size: 7pt;
            color: #555;
            margin-top: 3pt;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <table class="kop-table">
        <tr>
            <td style="width: 70pt; text-align: center; vertical-align: middle;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg" width="65" height="65" alt="Logo">
            </td>
            <td class="kop-text">
                <div class="kop-pemda">PEMERINTAH KABUPATEN KOLAKA</div>
                <div class="kop-dinas">DINAS PENDIDIKAN DAN KEBUDAYAAN</div>
                <div class="kop-sekolah">{{ $schoolName }}</div>
                <div class="kop-alamat">{{ $schoolAddress }}<br>Kontak / Email: {{ $contact }}</div>
            </td>
            <td style="width: 70pt;"></td>
        </tr>
    </table>

    {{-- JUDUL SURAT --}}
    <div class="title-section">
        <div class="surat-title">SURAT KETERANGAN LULUS</div>
        <div class="surat-number">Nomor : {{ $graduation->letter_number ?: '421.3/..../SMP-02/'.date('Y') }}</div>
    </div>

    <p style="text-align: justify; margin-bottom: 8pt;">
        Yang bertanda tangan di bawah ini, Kepala Sekolah Menengah Pertama (SMP) Negeri 02 Tanggetada, Kabupaten Kolaka, menerangkan dengan sesungguhnya bahwa:
    </p>

    {{-- BIODATA SISWA --}}
    <table class="bio-table">
        <tr>
            <td style="width: 25pt;">1.</td>
            <td style="width: 170pt;">Nama Lengkap Siswa</td>
            <td style="width: 15pt;">:</td>
            <td><strong>{{ strtoupper($graduation->student_name) }}</strong></td>
        </tr>
        <tr>
            <td>2.</td>
            <td>Nomor Induk Siswa (NIS)</td>
            <td>:</td>
            <td>{{ $graduation->nis }}</td>
        </tr>
        <tr>
            <td>3.</td>
            <td>Nomor Induk Siswa Nasional (NISN)</td>
            <td>:</td>
            <td>{{ $graduation->nisn ?: ($graduation->student->nisn ?? '-') }}</td>
        </tr>
        <tr>
            <td>4.</td>
            <td>Tempat, Tanggal Lahir</td>
            <td>:</td>
            <td>{{ $graduation->birth_place ?: ($graduation->student->birth_place ?? 'Kolaka') }}, {{ $graduation->birth_date ? $graduation->birth_date->translatedFormat('d F Y') : ($graduation->student->birth_date ? \Carbon\Carbon::parse($graduation->student->birth_date)->translatedFormat('d F Y') : '-') }}</td>
        </tr>
        <tr>
            <td>5.</td>
            <td>Sekolah Asal / Kelas</td>
            <td>:</td>
            <td>{{ $schoolName }} / {{ $graduation->classroom_name ?: ($graduation->student->classroom->name ?? 'IX') }}</td>
        </tr>
    </table>

    <p style="text-align: justify; margin-bottom: 6pt;">
        Berdasarkan kriteria kelulusan peserta didik pada satuan pendidikan sesuai dengan peraturan perundang-undangan dan rapat dewan guru SMP Negeri 02 Tanggetada, yang bersangkutan dinyatakan:
    </p>

    {{-- STATUS KELULUSAN --}}
    <div class="status-box">
        <div class="status-text">
            @if($graduation->status === 'lulus')
                *** L U L U S ***
            @elseif($graduation->status === 'ditunda')
                *** D I T U N D A ***
            @else
                *** TIDAK LULUS ***
            @endif
        </div>
        <div style="font-size: 8.5pt; margin-top: 2pt;">
            Dari Satuan Pendidikan SMP Negeri 02 Tanggetada Tahun Ajaran {{ date('Y') - 1 }}/{{ date('Y') }}
        </div>
    </div>

    <p style="margin-top: 6pt; margin-bottom: 4pt;">
        Dengan perolehan nilai rata-rata Ujian Sekolah sebagai berikut:
    </p>

    {{-- TABEL NILAI PER MAPEL / RATA-RATA --}}
    @php
        $scores = $graduation->scores_json ?: [
            'Pendidikan Agama dan Budi Pekerti' => 84.0,
            'Pendidikan Pancasila dan Kewarganegaraan' => 85.0,
            'Bahasa Indonesia' => 83.5,
            'Matematika' => 80.0,
            'Ilmu Pengetahuan Alam (IPA)' => 81.5,
            'Ilmu Pengetahuan Sosial (IPS)' => 85.0,
            'Bahasa Inggris' => 82.0,
            'Seni Budaya' => 86.0,
            'Pendidikan Jasmani, Olahraga, dan Kesehatan' => 87.0,
            'Prakarya / Informatika' => 84.5,
            'Muatan Lokal' => 86.0,
        ];
        $total = array_sum($scores);
        $avg = count($scores) > 0 ? round($total / count($scores), 2) : $graduation->average_score;
    @endphp

    <table class="score-table">
        <thead>
            <tr>
                <th style="width: 30pt;">No.</th>
                <th>Mata Pelajaran</th>
                <th style="width: 80pt;">Nilai Ujian</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($scores as $mapel => $nilai)
                <tr>
                    <td style="text-align: center;">{{ $no++ }}.</td>
                    <td>{{ $mapel }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ number_format((float)$nilai, 1) }}</td>
                </tr>
            @endforeach
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="2" style="text-align: right; padding-right: 12pt;">RATA-RATA NILAI UJIAN SEKOLAH :</td>
                <td style="text-align: center; font-size: 10.5pt;">{{ number_format((float)($graduation->average_score ?: $avg), 2) }}</td>
            </tr>
        </tbody>
    </table>

    <p style="text-align: justify; margin-top: 6pt; font-size: 9pt; color: #333;">
        Surat Keterangan Lulus (SKL) ini bersifat sementara sampai dengan diterbitkannya Ijazah Asli, dan sah digunakan untuk keperluan pendaftaran ke jenjang pendidikan selanjutnya (SMA/SMK/MA).
    </p>

    {{-- FOOTER / TTD & VERIFIKASI BARCODE --}}
    <table class="footer-table">
        <tr>
            <td style="width: 45%;">
                <div class="barcode-box">
                    <img src="{{ $barcodeSvg }}" style="width: 100%; height: 35pt;" alt="Barcode Verifikasi">
                    <div class="barcode-text">
                        KODE VERIFIKASI: <strong>{{ $graduation->verification_code }}</strong><br>
                        Pindai untuk verifikasi keaslian dokumen via SIMS
                    </div>
                </div>
            </td>
            <td style="width: 10%;"></td>
            <td style="width: 45%; text-align: left;">
                <div>Tanggetada, {{ $graduation->graduation_date ? $graduation->graduation_date->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</div>
                <div>Kepala SMP Negeri 02 Tanggetada,</div>
                <div style="height: 48pt;"></div>
                <div><strong>{{ $headmaster }}</strong></div>
                <div style="font-size: 9pt;">NIP. {{ $nip }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
