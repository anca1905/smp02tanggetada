<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Pelajar - {{ $student->student_name }}</title>
    @vite('resources/css/app.css')
    
    @php
        $bgColor1 = $site_settings['id_card_bg_color_1'] ?? '#166534';
        $bgColor2 = $site_settings['id_card_bg_color_2'] ?? '#eab308';
        $bgColor3 = $site_settings['id_card_bg_color_3'] ?? '#15803d';
        $fontColor = $site_settings['id_card_font_color'] ?? '#111827';
        $rules = $site_settings['id_card_rules'] ?? "1. Kartu ini berlaku selama pemilik berstatus sebagai siswa di sekolah ini.\n2. Kartu ini tidak boleh berpindah milik.\n3. Apabila kehilangan kartu ini, harap segera melapor ke sekolah.\n4. Wajib dibawa setiap hari.";
        
        $schoolName = $site_settings['id_card_school_name'] ?? ($site_settings['app_name'] ?? 'SMP MODERN NU PLEMAHAN');
        $schoolAddress = $site_settings['id_card_school_address'] ?? 'Jl. Pendidikan No. 1, Kecamatan Pendidikan, Kabupaten Pendidikan';
        $principalName = $site_settings['id_card_principal_name'] ?? 'Nama Kepala Sekolah, M.Pd';
    @endphp

    <style>
        @page {
            size: 85.6mm 54mm landscape;
            margin: 0mm;
        }
        body {
            background-color: #f3f4f6;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            font-family: Arial, sans-serif;
            color: {{ $fontColor }};
        }
        
        .card-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            margin: 20px 0;
        }

        .id-card {
            width: 85.6mm;
            height: 54mm;
            background-color: white;
            position: relative;
            overflow: hidden;
            border: 1px solid #ccc;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        @media print {
            body {
                background-color: white;
            }
            .card-container {
                margin: 0;
                gap: 0;
                display: block;
            }
            .id-card {
                box-shadow: none;
                margin: 0;
                border: none;
                page-break-after: always; /* Ensure front and back are on separate pages if printing 2-sided directly */
            }
            .id-card:last-child {
                page-break-after: auto;
            }
            .no-print {
                display: none !important;
            }
        }

        /* Background Design */
        .card-bg {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 0;
            overflow: hidden;
        }
        
        .bg-header {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 14mm;
            background: {{ $bgColor1 }};
        }

        .bg-wave-yellow {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 25mm;
            background: {{ $bgColor2 }};
            clip-path: polygon(0 40%, 100% 0, 100% 100%, 0% 100%);
        }
        
        .bg-wave-green {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 20mm;
            background: {{ $bgColor3 }};
            clip-path: polygon(0 50%, 100% 0, 100% 100%, 0% 100%);
        }

        /* Content Layer */
        .card-content {
            position: relative;
            z-index: 10;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Header Content */
        .header-content {
            height: 14mm;
            display: flex;
            align-items: center;
            padding: 0 4mm;
            color: white;
        }
        
        .school-logo {
            width: 9mm;
            height: 9mm;
            object-fit: contain;
            margin-right: 2mm;
            background: white;
            border-radius: 50%;
            padding: 1px;
        }
        
        .header-text {
            flex: 1;
            text-align: center;
        }
        
        .school-name {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.1;
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }
        
        .school-address {
            font-size: 6.5px;
            margin-top: 1.5px;
            font-weight: normal;
            color: white;
        }

        /* Main Body Content */
        .body-content {
            flex: 1;
            padding: 2mm 4mm;
            display: flex;
            gap: 3mm;
        }

        .photo-container {
            width: 18mm;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .student-photo {
            width: 18mm;
            height: 24mm;
            background: #e5e7eb;
            border: 1.5px solid {{ $bgColor1 }};
            padding: 1px;
            object-fit: cover;
        }

        .data-container {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .student-name-main {
            font-size: 10px;
            font-weight: bold;
            color: {{ $fontColor }};
            text-transform: uppercase;
            margin-top: 5mm;
            margin-bottom: 2mm;
        }
        
        .data-table {
            width: 100%;
            font-size: 6.5px;
            color: {{ $fontColor }};
            line-height: 1.3;
        }
        
        .data-table td {
            vertical-align: top;
        }
        
        .data-table td:nth-child(1) {
            width: 16mm;
            font-weight: 600;
        }
        
        .data-table td:nth-child(2) {
            width: 2mm;
        }

        .bottom-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding: 0 4mm 2mm 4mm;
            margin-top: -3mm;
        }
        
        .validity-badge {
            background: #dc2626;
            color: white;
            font-size: 5px;
            font-weight: bold;
            padding: 1.5mm 3mm;
            border-radius: 20px;
            text-transform: uppercase;
            box-shadow: 0 1px 2px rgba(0,0,0,0.3);
            margin-bottom: 3mm;
        }

        .signature-area {
            text-align: center;
            font-size: 6.5px;
            color: white;
            margin-bottom: 2mm;
            margin-right: 2mm;
            z-index: 10;
        }
        
        .signature-date {
            margin-bottom: 1.5mm;
            line-height: 1.3;
        }
        
        .signature-name {
            font-weight: bold;
            margin-top: 8mm;
            text-decoration: underline;
        }

        /* Back Card Specifics */
        .back-title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            color: {{ $bgColor1 }};
            margin-top: 4mm;
            margin-bottom: 2mm;
            text-transform: uppercase;
        }
        
        .back-rules {
            font-size: 7px;
            line-height: 1.4;
            padding: 0 6mm;
            text-align: left;
            flex: 1;
            white-space: pre-line;
            color: {{ $fontColor }};
        }

        .barcode-wrapper {
            background: white;
            padding: 1.5mm 2mm;
            border-radius: 4px;
            text-align: center;
            margin: 0 auto 4mm auto;
            border: 1px solid #e5e7eb;
        }

        #barcode {
            height: 10mm !important;
            width: auto;
        }
    </style>
</head>
<body class="py-10">

    <div class="no-print mb-6 text-center space-y-3">
        <h1 class="text-2xl font-bold text-gray-800">Preview Kartu Pelajar (Depan & Belakang)</h1>
        <div class="flex justify-center gap-3">
            <a href="{{ route('tu.student.card.pdf', $student) }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium shadow-md transition inline-flex items-center">
                <i class="fas fa-file-pdf mr-2"></i> Export PDF
            </a>
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium shadow-md transition">
                <i class="fas fa-print mr-2"></i> Cetak Browser
            </button>
            <button onclick="window.close()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-medium transition">
                Tutup
            </button>
        </div>
    </div>

    <div class="card-container">
        <!-- FRONT ID Card -->
        <div class="id-card">
            <!-- Background Layers -->
            <div class="card-bg">
                <div class="bg-wave-yellow"></div>
                <div class="bg-wave-green"></div>
                <div class="bg-header"></div>
            </div>
            
            <!-- Content Layer -->
            <div class="card-content">
                <!-- Header -->
                <div class="header-content">
                    <img src="{{ isset($site_settings['school_logo']) ? asset('storage/' . $site_settings['school_logo']) : 'https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg' }}" 
                         alt="Logo" class="school-logo">
                    <div class="header-text">
                        <div class="school-name">{{ $schoolName }}</div>
                        <div class="school-address">{{ $schoolAddress }}</div>
                    </div>
                </div>
                
                <!-- Body -->
                <div class="body-content">
                    <div class="photo-container">
                        <img src="{{ $student->photo_url ? asset('storage/' . $student->photo_url) : 'https://ui-avatars.com/api/?name='.urlencode($student->student_name).'&background=random&size=150' }}" alt="Foto Siswa" class="student-photo">
                    </div>
                    
                    <div class="data-container">
                        <div class="student-name-main">{{ $student->student_name }}</div>
                        <table class="data-table">
                            <tr>
                                <td>NIS / NISN</td>
                                <td>:</td>
                                <td>{{ $student->nis }}</td>
                            </tr>
                            <tr>
                                <td>Kelas</td>
                                <td>:</td>
                                <td>{{ $student->classroom->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Lahir</td>
                                <td>:</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td>:</td>
                                <td>{{ $student->gender == 'M' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <!-- Bottom -->
                <div class="bottom-section">
                    <div>
                        <div class="validity-badge">BERLAKU SELAMA MENJADI SISWA</div>
                    </div>
                    <div class="signature-area">
                        <div class="signature-date">Mengetahui,<br>Kepala Sekolah</div>
                        <div class="signature-name">{{ $principalName }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BACK ID Card -->
        <div class="id-card">
            <!-- Background Waves (Optional, lighter for back) -->
            <div class="card-bg">
                <div class="bg-wave-yellow" style="opacity: 0.1; clip-path: polygon(0 80%, 100% 0, 100% 100%, 0% 100%);"></div>
                <div class="bg-wave-green" style="opacity: 0.1; clip-path: polygon(0 90%, 100% 0, 100% 100%, 0% 100%);"></div>
            </div>
            
            <div class="card-content flex-col">
                <div class="back-title">Ketentuan Penggunaan</div>
                
                <div class="back-rules">
                    {{ $rules }}
                </div>
                
                <div class="barcode-wrapper">
                    <svg id="barcode"></svg>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        JsBarcode("#barcode", "{{ $student->nis }}", {
            format: "CODE128",
            lineColor: "{{ $fontColor }}",
            width: 1.5,
            height: 35,
            displayValue: true,
            fontSize: 12,
            margin: 0
        });
    </script>
</body>
</html>
