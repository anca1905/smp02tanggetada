<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi Siswa - {{ $monthName ?? \Carbon\Carbon::create()->month($bulan)->locale('id')->isoFormat('MMMM') }} {{ $tahun }}</title>
    @php
        $schoolName = $site_settings['school_name'] ?? ($site_settings['app_name'] ?? 'SMP NEGERI 2 TANGGETADA');
        $schoolName = strtoupper($schoolName);

        $daysInMonth = $daysInMonth ?? \Carbon\Carbon::create($tahun, $bulan, 1)->daysInMonth;
        $monthName = $monthName ?? \Carbon\Carbon::create()->month($bulan)->locale('id')->isoFormat('MMMM');
        $matrix = $matrix ?? [];

        // Fallback jika $sheets belum terdefinisi
        if (!isset($sheets) || empty($sheets)) {
            $sheets = [
                [
                    'classroom' => $selectedClass,
                    'namaRombel' => $selectedClass ? $selectedClass->name : 'Semua Kelas',
                    'academicYearName' => $tahun . '/' . ($tahun + 1),
                    'semesterName' => ($bulan >= 7 && $bulan <= 12) ? 'Semester Ganjil' : 'Semester Genap',
                    'waliKelasName' => $selectedClass && $selectedClass->teacher ? $selectedClass->teacher->name : '—',
                    'waliKelasNip' => $selectedClass && $selectedClass->teacher ? ($selectedClass->teacher->employee_id ?? '') : '',
                    'students' => collect(),
                    'countL' => 0,
                    'countP' => 0,
                    'totalCount' => 0,
                ]
            ];
        }
    @endphp

    <style>
        @page {
            size: a4 landscape;
            margin: 8mm 8mm 8mm 8mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8pt;
            color: #000;
            line-height: 1.15;
            background: #fff;
        }

        .page-sheet {
            width: 100%;
        }

        .page-break {
            page-break-after: always;
        }

        /* Header Laporan */
        .header-title-main {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .header-school {
            text-align: center;
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .header-year {
            text-align: center;
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .header-meta {
            text-align: left;
            font-size: 8.5pt;
            font-weight: bold;
            color: #000;
            margin-bottom: 4px;
        }

        /* Tabel Presensi Siswa */
        .table-absen {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 7.5pt;
        }
        .table-absen th,
        .table-absen td {
            border: 1px solid #000;
            text-align: center;
            vertical-align: middle;
            padding: 2px 1px;
            overflow: hidden;
        }
        .table-absen th {
            background-color: #ffffff;
            font-weight: bold;
            color: #000;
        }
        .table-absen td.col-nama {
            text-align: left;
            padding-left: 4px;
            padding-right: 2px;
            white-space: nowrap;
        }
        .table-absen td.col-nisn {
            font-size: 7pt;
            white-space: nowrap;
        }
        .table-absen td.col-day {
            font-weight: bold;
            font-size: 7.5pt;
        }

        /* Layout Footer */
        .footer-table {
            width: 100%;
            border: none;
            margin-top: 8px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .footer-table td {
            border: none;
            vertical-align: top;
            padding: 0;
        }

        /* Tabel Keterangan (S, I, A) */
        .table-keterangan {
            width: auto;
            border-collapse: collapse;
            font-size: 7.5pt;
        }
        .table-keterangan th {
            border: 1px solid #000;
            padding: 2px 10px;
            text-align: center;
            font-weight: bold;
            font-size: 8pt;
            background-color: #fff;
        }
        .table-keterangan td {
            border: 1px solid #000;
            padding: 1.5px 10px;
            vertical-align: middle;
        }
        .table-keterangan td.sym-cell {
            text-align: center;
            font-weight: bold;
            width: 28px;
        }
        .table-keterangan td.desc-cell {
            text-align: left;
            width: 65px;
        }

        /* Ringkasan Jumlah Gender */
        .table-counts {
            margin-top: 6px;
            font-size: 8.5pt;
            font-weight: bold;
            border-collapse: collapse;
        }
        .table-counts td {
            border: none;
            padding: 1px 0;
        }
        .table-counts td.count-label {
            color: #000;
            width: 75px;
        }
        .table-counts td.count-val {
            color: #dc2626; /* Merah sesuai gambar */
            padding-left: 4px;
        }

        /* Tanda Tangan */
        .signature-box {
            display: inline-block;
            text-align: center;
            font-size: 8pt;
            min-width: 160pt;
        }
        .signature-space {
            height: 38px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

@foreach ($sheets as $sheetIndex => $sheet)
    <div class="page-sheet {{ $sheetIndex < count($sheets) - 1 ? 'page-break' : '' }}">

        <!-- Header -->
        <div class="header-title-main">DAFTAR HADIR SISWA</div>
        <div class="header-school">{{ $schoolName }}</div>
        <div class="header-year">TAHUN PELAJARAN {{ $sheet['academicYearName'] }}</div>
        <div class="header-meta">Jenis Rombel: Kelas Utama - Nama Rombel: {{ $sheet['namaRombel'] }} - {{ $sheet['semesterName'] }} - Wali Kelas: {{ $sheet['waliKelasName'] }}</div>

        <!-- Tabel Utama Presensi -->
        <table class="table-absen">
            <thead>
                {{-- Baris Header 1 --}}
                <tr>
                    <th colspan="2" style="width: 110pt;">NOMOR</th>
                    <th rowspan="3" style="width: 155pt;">NAMA SISWA</th>
                    <th rowspan="3" style="width: 22pt;">L/P</th>
                    <th colspan="{{ $daysInMonth }}">Bulan {{ $monthName }} {{ $tahun }}</th>
                </tr>
                {{-- Baris Header 2 --}}
                <tr>
                    <th rowspan="2" style="width: 25pt;">URUT</th>
                    <th rowspan="2" style="width: 85pt;">NISN / NIS</th>
                    <th colspan="{{ $daysInMonth }}">Tanggal</th>
                </tr>
                {{-- Baris Header 3: Nomor Tanggal 1 s.d. $daysInMonth --}}
                <tr>
                    @for ($d = 1; $d <= $daysInMonth; $d++)
                        <th style="width: {{ 485 / $daysInMonth }}pt;">{{ $d }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @forelse ($sheet['students'] as $i => $student)
                    @php
                        $nisnNis = $student->nisn && $student->nis
                            ? $student->nisn . ' / ' . $student->nis
                            : ($student->nisn ?: ($student->nis ?: '-'));
                        $genderCode = in_array(strtoupper($student->gender ?? ''), ['M', 'L'])
                            ? 'L'
                            : (in_array(strtoupper($student->gender ?? ''), ['F', 'P']) ? 'P' : '-');
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="col-nisn">{{ $nisnNis }}</td>
                        <td class="col-nama">{{ $student->student_name }}</td>
                        <td>{{ $genderCode }}</td>
                        @for ($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $cellStatus = $matrix[$student->id][$d] ?? '';
                            @endphp
                            <td class="col-day">{{ $cellStatus }}</td>
                        @endfor
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 4 + $daysInMonth }}" style="padding: 12px; color: #666;">
                            Tidak ada data siswa untuk rombel ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Bagian Bawah: Keterangan S, I, A & Jumlah Siswa & Tanda Tangan -->
        <table class="footer-table">
            <tr>
                <td style="width: 55%;">
                    <!-- Tabel Keterangan -->
                    <table class="table-keterangan">
                        <thead>
                            <tr>
                                <th colspan="2">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="sym-cell">S</td>
                                <td class="desc-cell">Sakit</td>
                            </tr>
                            <tr>
                                <td class="sym-cell">I</td>
                                <td class="desc-cell">Izin</td>
                            </tr>
                            <tr>
                                <td class="sym-cell">A</td>
                                <td class="desc-cell">Alpa</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Hitungan Gender Siswa -->
                    <table class="table-counts">
                        <tr>
                            <td class="count-label">Laki-Laki</td>
                            <td class="count-val">: {{ $sheet['countL'] }} Orang</td>
                        </tr>
                        <tr>
                            <td class="count-label">Perempuan</td>
                            <td class="count-val">: {{ $sheet['countP'] }} Orang</td>
                        </tr>
                        <tr>
                            <td class="count-label">Jumlah</td>
                            <td class="count-val">: {{ $sheet['totalCount'] }} Orang</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 45%; text-align: right;">
                    <div class="signature-box">
                        <div>Tanggetada, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</div>
                        <div style="margin-top: 2px;">Wali Kelas,</div>
                        <div class="signature-space"></div>
                        <div class="signature-name">{{ $sheet['waliKelasName'] }}</div>
                        @if(!empty($sheet['waliKelasNip']))
                            <div>NIP. {{ $sheet['waliKelasNip'] }}</div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

    </div>
@endforeach

</body>
</html>
