<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Pelajar - {{ $student->student_name }}</title>
    @php
        $bgColor1 = $site_settings['id_card_bg_color_1'] ?? '#166534';
        $bgColor2 = $site_settings['id_card_bg_color_2'] ?? '#eab308';
        $bgColor3 = $site_settings['id_card_bg_color_3'] ?? '#15803d';
        $fontColor = $site_settings['id_card_font_color'] ?? '#111827';
        $rules = $site_settings['id_card_rules'] ?? "1. Kartu ini berlaku selama pemilik berstatus sebagai siswa di sekolah ini.\n2. Kartu ini tidak boleh berpindah milik.\n3. Apabila kehilangan kartu ini, harap segera melapor ke sekolah.\n4. Wajib dibawa setiap hari.";
        
        $schoolName = $site_settings['id_card_school_name'] ?? ($site_settings['app_name'] ?? 'SMP MODERN NU PLEMAHAN');
        $schoolAddress = $site_settings['id_card_school_address'] ?? 'Jl. Pendidikan No. 1, Kecamatan Pendidikan, Kabupaten Pendidikan';
        $principalName = $site_settings['id_card_principal_name'] ?? 'Nama Kepala Sekolah, M.Pd';

        $logoSrc = null;
        if (!empty($site_settings['school_logo'])) {
            $logoPath = storage_path('app/public/' . $site_settings['school_logo']);
            if (file_exists($logoPath)) {
                $logoSrc = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));
            }
        }

        $photoSrc = null;
        if (!empty($student->photo_url)) {
            $photoPath = storage_path('app/public/' . $student->photo_url);
            if (file_exists($photoPath)) {
                $photoSrc = 'data:image/' . pathinfo($photoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($photoPath));
            }
        }
    @endphp

    <style>
        @page {
            size: 242.64pt 153.07pt landscape;
            margin: 0pt;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 7pt;
            color: {{ $fontColor }};
            background-color: #ffffff;
        }
        .page {
            width: 242.64pt;
            height: 153.07pt;
            position: relative;
            overflow: hidden;
            background: #ffffff;
        }
        .page-break {
            page-break-after: always;
        }

        /* FRONT CARD */
        .header {
            width: 100%;
            height: 38pt;
            background-color: {{ $bgColor1 }};
            color: #ffffff;
            border-bottom: 2.5pt solid {{ $bgColor2 }};
            padding: 3pt 6pt;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-logo {
            width: 30pt;
            vertical-align: middle;
            text-align: left;
        }
        .logo-img {
            width: 26pt;
            height: 26pt;
            border-radius: 50%;
            background: #ffffff;
        }
        .logo-placeholder {
            width: 26pt;
            height: 26pt;
            line-height: 26pt;
            text-align: center;
            background: #ffffff;
            color: {{ $bgColor1 }};
            font-weight: bold;
            font-size: 8pt;
            border-radius: 50%;
        }
        .header-info {
            vertical-align: middle;
            text-align: center;
            padding-right: 15pt;
        }
        .school-name {
            font-size: 8.5pt;
            font-weight: bold;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.3pt;
        }
        .school-address {
            font-size: 5pt;
            color: #f0fdf4;
            margin-top: 1pt;
        }

        /* Body Section */
        .body-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5pt;
            padding: 0 6pt;
        }
        .photo-cell {
            width: 52pt;
            vertical-align: top;
            padding-left: 6pt;
        }
        .photo-box {
            width: 48pt;
            height: 64pt;
            border: 1pt solid {{ $bgColor1 }};
            background: #f3f4f6;
            text-align: center;
            overflow: hidden;
        }
        .photo-img {
            width: 48pt;
            height: 64pt;
            display: block;
        }
        .photo-placeholder {
            width: 48pt;
            height: 64pt;
            line-height: 64pt;
            font-size: 7pt;
            color: #9ca3af;
            background: #f3f4f6;
        }

        .data-cell {
            vertical-align: top;
            padding-left: 6pt;
            padding-right: 6pt;
        }
        .student-name-title {
            font-size: 8pt;
            font-weight: bold;
            color: {{ $bgColor1 }};
            text-transform: uppercase;
            margin-bottom: 3pt;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 165pt;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 6pt;
        }
        .info-table td {
            padding: 1pt 0;
            vertical-align: top;
        }
        .label-col {
            width: 42pt;
            color: #4b5563;
        }
        .sep-col {
            width: 5pt;
            color: #6b7280;
        }
        .val-col {
            color: #111827;
            font-weight: bold;
        }

        /* Footer Section */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            position: absolute;
            bottom: 5pt;
            left: 0;
            padding: 0 6pt;
        }
        .badge-cell {
            vertical-align: bottom;
            padding-left: 6pt;
        }
        .validity-badge {
            display: inline-block;
            background: {{ $bgColor1 }};
            color: #ffffff;
            font-size: 4.5pt;
            font-weight: bold;
            padding: 1.5pt 4pt;
            border-radius: 2pt;
            letter-spacing: 0.2pt;
        }
        .signature-cell {
            vertical-align: bottom;
            text-align: right;
            padding-right: 6pt;
            font-size: 5pt;
            line-height: 1.2;
        }
        .principal-name {
            font-weight: bold;
            color: {{ $fontColor }};
            margin-top: 10pt;
            text-decoration: underline;
        }
        .bottom-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3pt;
            background: {{ $bgColor3 }};
        }

        /* BACK CARD */
        .back-container {
            padding: 8pt 12pt;
            text-align: center;
        }
        .back-title {
            font-size: 8pt;
            font-weight: bold;
            color: {{ $bgColor1 }};
            text-transform: uppercase;
            letter-spacing: 0.5pt;
            border-bottom: 1pt solid {{ $bgColor2 }};
            padding-bottom: 3pt;
            margin-bottom: 6pt;
        }
        .rules-box {
            text-align: left;
            font-size: 5.5pt;
            line-height: 1.4;
            color: #374151;
            margin-bottom: 8pt;
            background: #f9fafb;
            padding: 4pt 6pt;
            border-left: 2pt solid {{ $bgColor1 }};
        }
        .barcode-section {
            margin-top: 6pt;
            text-align: center;
        }
        .barcode-img {
            max-width: 150pt;
            height: 28pt;
        }
        .barcode-text {
            font-size: 6.5pt;
            font-weight: bold;
            letter-spacing: 2pt;
            margin-top: 1pt;
            color: {{ $fontColor }};
        }
    </style>
</head>
<body>

    <!-- FRONT CARD -->
    <div class="page page-break">
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="header-logo">
                        @if ($logoSrc)
                            <img src="{{ $logoSrc }}" alt="Logo" class="logo-img">
                        @else
                            <div class="logo-placeholder">SMP</div>
                        @endif
                    </td>
                    <td class="header-info">
                        <div class="school-name">{{ $schoolName }}</div>
                        <div class="school-address">{{ $schoolAddress }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <table class="body-table">
            <tr>
                <td class="photo-cell">
                    <div class="photo-box">
                        @if ($photoSrc)
                            <img src="{{ $photoSrc }}" alt="Foto Siswa" class="photo-img">
                        @else
                            <div class="photo-placeholder">FOTO 3x4</div>
                        @endif
                    </div>
                </td>
                <td class="data-cell">
                    <div class="student-name-title">{{ $student->student_name }}</div>
                    <table class="info-table">
                        <tr>
                            <td class="label-col">NIS / NISN</td>
                            <td class="sep-col">:</td>
                            <td class="val-col">{{ $student->nis }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Kelas</td>
                            <td class="sep-col">:</td>
                            <td class="val-col">{{ $student->classroom->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Jenis Kelamin</td>
                            <td class="sep-col">:</td>
                            <td class="val-col">{{ $student->gender == 'M' ? 'Laki-laki' : 'Perempuan' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="footer-table">
            <tr>
                <td class="badge-cell">
                    <span class="validity-badge">BERLAKU SELAMA MENJADI SISWA</span>
                </td>
                <td class="signature-cell">
                    <div>Mengetahui,</div>
                    <div>Kepala Sekolah</div>
                    <div class="principal-name">{{ $principalName }}</div>
                </td>
            </tr>
        </table>
        <div class="bottom-bar"></div>
    </div>

    <!-- BACK CARD -->
    <div class="page">
        <div class="back-container">
            <div class="back-title">Ketentuan Penggunaan</div>
            <div class="rules-box">
                {!! nl2br(e($rules)) !!}
            </div>

            <div class="barcode-section">
                <img src="{{ $barcodeSvg }}" alt="Barcode NIS" class="barcode-img">
                <div class="barcode-text">* {{ $student->nis }} *</div>
            </div>
        </div>
        <div class="bottom-bar"></div>
    </div>

</body>
</html>
