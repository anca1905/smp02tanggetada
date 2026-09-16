<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi Siswa - {{ \Carbon\Carbon::create()->month($bulan)->locale('id')->isoFormat('MMMM') }} {{ $tahun }}</title>
    @php
        $schoolName = $site_settings['school_name'] ?? ($site_settings['app_name'] ?? 'SMP 02 TANGGETADA');
        $schoolAddress = $site_settings['school_address'] ?? 'Jl. Poros Tanggetada, Kabupaten Kolaka, Sulawesi Tenggara';
        $principalName = $site_settings['id_card_principal_name'] ?? 'Kepala Sekolah, M.Pd';
        
        $sesiMap = [
            'apel' => 'Apel Pagi',
            'kelas' => 'Di Kelas',
            'pulang' => 'Pulang',
        ];
        $sesiText = $sesi ? ($sesiMap[$sesi] ?? ucfirst($sesi)) : 'Semua Sesi';
        $kelasText = $selectedClass ? 'Kelas ' . $selectedClass->name : 'Semua Kelas';
        $bulanText = \Carbon\Carbon::create()->month($bulan)->locale('id')->isoFormat('MMMM') . ' ' . $tahun;

        // Hitung rekap ringkasan
        $summary = [
            'present' => 0,
            'sick' => 0,
            'permission' => 0,
            'absent' => 0,
            'late' => 0,
        ];
        foreach ($data as $item) {
            $st = strtolower($item->status);
            if (isset($summary[$st])) {
                $summary[$st]++;
            }
        }
    @endphp

    <style>
        @page {
            size: a4 landscape;
            margin: 12mm 12mm 15mm 12mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
            color: #1f2937;
            line-height: 1.3;
        }

        /* Kop Laporan */
        .kop-table {
            width: 100%;
            border-bottom: 2pt solid #111827;
            padding-bottom: 6pt;
            margin-bottom: 10pt;
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
            font-size: 11pt;
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

        /* Meta Info Filter */
        .meta-table {
            width: 100%;
            margin-bottom: 8pt;
            border-collapse: collapse;
            font-size: 8.5pt;
        }
        .meta-table td {
            padding: 2pt 0;
            vertical-align: top;
        }
        .meta-label {
            font-weight: bold;
            color: #4b5563;
            width: 80pt;
        }
        .meta-val {
            color: #111827;
        }

        /* Summary Badges */
        .summary-box {
            margin-bottom: 10pt;
            padding: 5pt 8pt;
            background: #f9fafb;
            border: 0.5pt solid #e5e7eb;
            font-size: 8pt;
        }
        .summary-box span {
            margin-right: 15pt;
            font-weight: 500;
        }
        .summary-box strong {
            font-weight: bold;
            color: #111827;
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }
        .data-table th {
            background-color: #f3f4f6;
            color: #111827;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7.5pt;
            border: 0.5pt solid #9ca3af;
            padding: 4pt 5pt;
            text-align: left;
        }
        .data-table td {
            border: 0.5pt solid #d1d5db;
            padding: 4pt 5pt;
            vertical-align: middle;
        }
        .data-table tr:nth-child(even) td {
            background-color: #fcfdfd;
        }
        .text-center {
            text-align: center;
        }

        /* Status Pills */
        .status-hadir {
            color: #065f46;
            font-weight: bold;
        }
        .status-sakit {
            color: #854d0e;
            font-weight: bold;
        }
        .status-izin {
            color: #1e40af;
            font-weight: bold;
        }
        .status-alpha {
            color: #991b1b;
            font-weight: bold;
        }
        .status-terlambat {
            color: #c2410c;
            font-weight: bold;
        }

        /* Signatures */
        .sign-table {
            width: 100%;
            margin-top: 25pt;
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
            height: 45pt;
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
                <div class="kop-subtitle">LAPORAN REKAPITULASI KEHADIRAN SISWA</div>
                <div class="kop-address">{{ $schoolAddress }}</div>
            </td>
        </tr>
    </table>

    <!-- META FILTER -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Bulan / Tahun</td>
            <td style="width: 10pt;">:</td>
            <td class="meta-val"><strong>{{ $bulanText }}</strong></td>
            <td class="meta-label" style="text-align: right; padding-right: 8pt;">Kelas :</td>
            <td class="meta-val" style="width: 120pt;"><strong>{{ $kelasText }}</strong></td>
        </tr>
        <tr>
            <td class="meta-label">Sesi Kehadiran</td>
            <td style="width: 10pt;">:</td>
            <td class="meta-val">{{ $sesiText }}</td>
            <td class="meta-label" style="text-align: right; padding-right: 8pt;">Total Data :</td>
            <td class="meta-val"><strong>{{ count($data) }} baris</strong></td>
        </tr>
    </table>

    <!-- RINGKASAN -->
    <div class="summary-box">
        <strong>Ringkasan:</strong>
        <span>Hadir: <strong>{{ $summary['present'] }}</strong></span>
        <span>Terlambat: <strong>{{ $summary['late'] }}</strong></span>
        <span>Izin: <strong>{{ $summary['permission'] }}</strong></span>
        <span>Sakit: <strong>{{ $summary['sick'] }}</strong></span>
        <span>Alpha: <strong>{{ $summary['absent'] }}</strong></span>
    </div>

    <!-- DATA TABLE -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25pt;" class="text-center">No</th>
                <th style="width: 75pt;">Tanggal</th>
                <th style="width: 65pt;">Sesi</th>
                <th style="width: 55pt;">NIS</th>
                <th>Nama Lengkap Siswa</th>
                <th style="width: 70pt;">Kelas</th>
                <th style="width: 70pt;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $item)
                @php
                    $sesiName = match($item->attendance->session_type ?? '') {
                        'apel' => 'Apel Pagi',
                        'kelas' => 'Di Kelas',
                        'pulang' => 'Pulang',
                        default => '—',
                    };

                    [$statusClass, $statusLabel] = match(strtolower($item->status)) {
                        'present' => ['status-hadir', 'Hadir'],
                        'sick' => ['status-sakit', 'Sakit'],
                        'permission' => ['status-izin', 'Izin'],
                        'absent' => ['status-alpha', 'Alpha'],
                        'late' => ['status-terlambat', 'Terlambat'],
                        default => ['', $item->status],
                    };
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->attendance->date)->isoFormat('dddd, D MMM Y') }}</td>
                    <td>{{ $sesiName }}</td>
                    <td>{{ optional($item->student)->nis ?? '-' }}</td>
                    <td><strong>{{ optional($item->student)->student_name ?? 'Data Siswa Dihapus' }}</strong></td>
                    <td>{{ optional(optional($item->student)->classroom)->name ?? '-' }}</td>
                    <td class="text-center"><span class="{{ $statusClass }}">{{ $statusLabel }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 15pt; color: #6b7280;">
                        Tidak ada data absensi yang ditemukan untuk filter ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- SIGNATURES -->
    <table class="sign-table">
        <tr>
            <td>
                <div>Mengetahui,</div>
                <div>Kepala Sekolah</div>
                <div class="sign-space"></div>
                <div class="sign-name">{{ $principalName }}</div>
            </td>
            <td>
                <div>Tanggetada, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</div>
                <div>Petugas Administrasi / TU</div>
                <div class="sign-space"></div>
                <div class="sign-name">Staff Administrasi TU</div>
            </td>
        </tr>
    </table>

</body>
</html>
