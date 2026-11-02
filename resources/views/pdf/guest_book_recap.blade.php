<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Buku Tamu Digital - {{ $site_settings['app_name'] ?? 'SMP 02 Tanggetada' }}</title>
    @php
        $schoolName = $site_settings['school_name'] ?? ($site_settings['app_name'] ?? 'SMP NEGERI 02 TANGGETADA');
        $schoolAddress = $site_settings['school_address'] ?? 'Jl. Poros Tanggetada, Kab. Kolaka, Sulawesi Tenggara';
    @endphp

    <style>
        @page {
            size: a4 landscape;
            margin: 12mm 15mm 12mm 15mm;
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
        }

        .header-section {
            text-align: center;
            border-bottom: 2pt solid #111827;
            padding-bottom: 6pt;
            margin-bottom: 12pt;
        }
        .header-title {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header-subtitle {
            font-size: 9.5pt;
            color: #4b5563;
        }

        table.content-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8pt;
        }
        table.content-table th, table.content-table td {
            border: 1pt solid #9ca3af;
            padding: 4.5pt 6pt;
        }
        table.content-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 8pt;
        }

        .text-center { text-align: center; }
        .footer-sign {
            margin-top: 20pt;
            width: 100%;
        }
        .footer-sign td {
            vertical-align: top;
        }
    </style>
</head>
<body>

    <div class="header-section">
        <div class="header-title">BUKU TAMU & REGISTER KUNJUNGAN SEKOLAH</div>
        <div class="header-subtitle">{{ $schoolName }} — {{ $schoolAddress }}</div>
        <div style="font-size: 8pt; color: #6b7280; margin-top: 2pt;">Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB / WITA</div>
    </div>

    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 25pt;">No</th>
                <th style="width: 65pt;">Tanggal / Jam</th>
                <th style="width: 110pt;">Nama Tamu</th>
                <th style="width: 110pt;">Instansi / Alamat</th>
                <th style="width: 75pt;">No. Kontak/WA</th>
                <th style="width: 75pt;">Kategori</th>
                <th style="width: 85pt;">Bertemu Dengan</th>
                <th>Keperluan / Pesan</th>
                <th style="width: 55pt;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($guests as $index => $g)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">
                        {{ $g->visit_date ? $g->visit_date->format('d/m/Y') : '-' }}<br>
                        <span style="font-size: 7.5pt; color: #6b7280;">{{ $g->visit_time ?: '-' }}</span>
                    </td>
                    <td><strong>{{ $g->name }}</strong></td>
                    <td>{{ $g->institution ?: '-' }}</td>
                    <td>{{ $g->phone }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $g->category)) }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $g->meet_with)) }}</td>
                    <td>{{ $g->purpose }}</td>
                    <td class="text-center">
                        @if($g->status === 'completed')
                            Selesai
                        @elseif($g->status === 'in_progress')
                            Diterima
                        @else
                            Menunggu
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 15pt; color: #6b7280;">Belum ada data kunjungan tamu.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-sign">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%; text-align: center;">
                <div>Tanggetada, {{ now()->translatedFormat('d F Y') }}</div>
                <div>Petugas Penerima Tamu / Tata Usaha,</div>
                <div style="height: 45pt;"></div>
                <div>( __________________________ )</div>
                <div style="font-size: 8pt; color: #4b5563;">Staff Pelayanan / Resepsionis</div>
            </td>
        </tr>
    </table>

</body>
</html>
