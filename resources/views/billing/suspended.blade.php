<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Penangguhan Layanan SIMS - SMP Negeri 2 Tanggetada</title>
    @include('partials.favicon')
    <meta name="robots" content="noindex, nofollow, noarchive">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-body: #f8fafc;
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --danger-primary: #dc2626;
            --danger-soft: #fef2f2;
            --danger-border: #fecaca;
            --warning: #f59e0b;
            --accent: #2563eb;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --text-light: #334155;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-body);
            background-image: radial-gradient(#e2e8f0 1.2px, transparent 1.2px);
            background-size: 24px 24px;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 16px;
            line-height: 1.55;
            position: relative;
            overflow-x: hidden;
        }

        /* Subtle Ambient Glow */
        .ambient-glow {
            position: fixed;
            top: -120px;
            left: 50%;
            transform: translateX(-50%);
            width: 750px;
            height: 380px;
            background: radial-gradient(circle, rgba(239, 68, 68, 0.08) 0%, rgba(248, 250, 252, 0) 70%);
            pointer-events: none;
            z-index: 0;
        }

        .container {
            max-width: 720px;
            width: 100%;
            position: relative;
            z-index: 10;
        }

        /* Top Brand Card */
        .brand-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 0 4px;
        }

        .school-identity {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .school-logo-box {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 4px;
        }

        .school-logo-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .school-info h2 {
            font-size: 15px;
            font-weight: 800;
            letter-spacing: 0.3px;
            color: #0f172a;
        }

        .school-info p {
            font-size: 12px;
            color: #64748b;
        }

        .system-badge {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 999px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Main Notice Card */
        .notice-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.08), 0 0 0 1px rgba(220, 38, 38, 0.08);
            overflow: hidden;
        }

        /* Banner Alert Ribbon */
        .alert-banner {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: #ffffff;
            padding: 24px 28px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.1);
        }

        .alert-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .alert-icon svg {
            width: 26px;
            height: 26px;
            fill: none;
            stroke: #ffffff;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .alert-title h1 {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: 0.2px;
            line-height: 1.3;
            margin-bottom: 4px;
            color: #ffffff;
        }

        .alert-title p {
            font-size: 13px;
            color: #fee2e2;
            font-weight: 500;
        }

        /* Content Body */
        .card-body {
            padding: 28px;
        }

        .formal-text {
            font-size: 13.5px;
            color: #334155;
            margin-bottom: 22px;
            line-height: 1.6;
        }

        .formal-text strong {
            color: #0f172a;
        }

        /* Grid Details */
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 22px;
        }

        @media (max-width: 580px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }

        .detail-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
        }

        .detail-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 3px;
            font-weight: 600;
        }

        .detail-val {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }

        .detail-val.danger {
            color: #dc2626;
            font-family: 'JetBrains Mono', monospace;
        }

        .detail-val.amount {
            color: #0284c7;
            font-family: 'JetBrains Mono', monospace;
            font-size: 16px;
            font-weight: 800;
        }

        /* Bank Account Box */
        .bank-box {
            background: #f0f7ff;
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .bank-info {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .bank-header-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .bank-tag {
            background: #2563eb;
            color: white;
            font-size: 10.5px;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .bank-name {
            font-size: 13px;
            font-weight: 600;
            color: #1e40af;
        }

        .account-number {
            font-family: 'JetBrains Mono', monospace;
            font-size: 19px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 1px;
        }

        .account-holder {
            font-size: 12px;
            color: #475569;
        }

        .copy-btn {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #0f172a;
            font-family: inherit;
            font-size: 12px;
            font-weight: 600;
            padding: 8px 14px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .copy-btn:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
        }

        /* Action Buttons */
        .actions-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .btn {
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 700;
            padding: 12px 22px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
            flex: 1 1 200px;
            text-align: center;
        }

        .btn-wa {
            background: #16a34a;
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(22, 163, 74, 0.25);
            border: none;
        }

        .btn-wa:hover {
            background: #15803d;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(22, 163, 74, 0.35);
        }

        .btn-invoice {
            background: #f8fafc;
            color: #1e293b;
            border: 1px solid #cbd5e1;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .btn-invoice:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #94a3b8;
        }

        /* Legal / Recovery Notice */
        .recovery-info {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px 16px;
            border-radius: 0 8px 8px 0;
            font-size: 12.5px;
            color: #92400e;
            line-height: 1.5;
        }

        /* Footer */
        .footer-note {
            margin-top: 24px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
        }

        .footer-note a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-note a:hover {
            color: #0f172a;
        }
    </style>
</head>
<body>
    <div class="ambient-glow"></div>

    <div class="container">
        <!-- Top Identity -->
        <header class="brand-header">
            <div class="school-identity">
                <div class="school-logo-box">
                    @php
                        $schoolFavicon = !empty($site_settings['school_logo'])
                            ? asset('storage/' . $site_settings['school_logo'])
                            : 'https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg';
                    @endphp
                    <img src="{{ $schoolFavicon }}" alt="Logo SMP Negeri 2 Tanggetada">
                </div>
                <div class="school-info">
                    <h2>SMP NEGERI 2 TANGGETADA</h2>
                    <p>Sistem Informasi Manajemen Sekolah (SIMS)</p>
                </div>
            </div>
            <span class="system-badge">SIMS v2.0 Enterprise</span>
        </header>

        <!-- Main Suspension Notice Card -->
        <main class="notice-card">
            <!-- Red Banner Header -->
            <div class="alert-banner">
                <div class="alert-icon">
                    <svg viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <div class="alert-title">
                    <h1>LAYANAN SISTEM DITANGGUHKAN SEMENTARA</h1>
                    <p>Status: Menunggu Konfirmasi Pelunasan Tagihan Operasional (Overdue)</p>
                </div>
            </div>

            <!-- Card Content -->
            <div class="card-body">
                <p class="formal-text">
                    Diberitahukan kepada <strong>Pihak Sekolah SMP Negeri 2 Tanggetada</strong> (Kepala Sekolah, Bendahara, Operator TU, Dewan Guru, dan Siswa) bahwa terhitung sejak tanggal jatuh tempo, 
                    <strong>akses ke seluruh modul portal web, sistem presensi, LMS, dan aplikasi mobile SIMS untuk sementara waktu dinonaktifkan</strong> oleh penyedia layanan (Skillance Cloud).
                    Hal ini merujuk pada <strong>Perjanjian Kerja Sama Operasional & Pemeliharaan Sistem SIMS (Pasal 4 Ayat 3 & 4)</strong> mengenai kewajiban penyelesaian biaya langganan bulanan.
                </p>

                <!-- Grid Rincian Tagihan -->
                <div class="details-grid">
                    <div class="detail-item">
                        <div class="detail-label">Nomor Invoice Resmi</div>
                        <div class="detail-val">{{ $invoiceNumber }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Tanggal Jatuh Tempo</div>
                        <div class="detail-val danger">{{ $dueDate }} (Lewat Jatuh Tempo)</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Perihal Layanan</div>
                        <div class="detail-val">Operasional & Pemeliharaan Cloud SIMS</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Total Tagihan Periode Berjalan</div>
                        <div class="detail-val amount">{{ $nominal }}</div>
                    </div>
                </div>

                <!-- Bank Instructions Box -->
                <div class="bank-box">
                    <div class="bank-info">
                        <div class="bank-header-row">
                            <span class="bank-tag">{{ $bankName }}</span>
                            <span class="bank-name">Rekening Resmi Pengembang / Vendor</span>
                        </div>
                        <div class="account-number" id="accountNumberText">{{ $accountNumber }}</div>
                        <div class="account-holder">Atas Nama: <strong>{{ $accountName }}</strong> &bull; Berita: <code>SIMS SMPN 2 TANGGETADA</code></div>
                    </div>
                    <button type="button" class="copy-btn" onclick="copyAccountNumber()">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        <span id="copyBtnText">Salin No. Rek</span>
                    </button>
                </div>

                <!-- Action CTA Buttons -->
                <div class="actions-group">
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-wa">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.993.587 1.956.887 2.801.887 3.177 0 5.766-2.588 5.766-5.768 0-3.18-2.587-5.772-5.771-5.772zm3.392 8.243c-.145.409-.844.757-1.18.796-.341.04-.77.065-2.228-.537-1.742-.722-2.855-2.482-2.942-2.597-.087-.116-.708-.941-.708-1.793 0-.852.447-1.272.607-1.446.16-.175.348-.219.465-.219.116 0 .233.001.335.006.107.005.25.04.39.378.145.349.494 1.206.538 1.293.044.088.073.19.015.305-.058.117-.088.19-.175.292-.087.102-.184.227-.263.306-.087.087-.179.182-.077.357.102.175.453.747.971 1.208.667.594 1.23.778 1.405.865.175.088.277.073.379-.044.102-.117.437-.51.554-.685.117-.175.233-.146.393-.087.16.058 1.018.48 1.193.568.175.087.292.131.335.204.043.073.043.423-.102.832z"/>
                        </svg>
                        Konfirmasi Pembayaran via WhatsApp ({{ $whatsapp }})
                    </a>
                    <a href="{{ $invoiceUrl }}" target="_blank" class="btn btn-invoice">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Lihat Dokumen Invoice Resmi (A4)
                    </a>
                </div>

                <!-- Recovery Procedure -->
                <div class="recovery-info">
                    <strong>Prosedur Pemulihan Akses:</strong> Segera lakukan penyelesaian pembayaran sesuai invoice dan kirimkan bukti transfer ke WhatsApp Pengembang (<strong>{{ $whatsapp }}</strong>). Sistem dan seluruh data sekolah akan dipulihkan secara instan (*instant reactivation*) dalam waktu kurang dari 5 menit setelah verifikasi.
                </div>
            </div>
        </main>

        <!-- Footer Note -->
        <footer class="footer-note">
            <span>&copy; {{ date('Y') }} Skillance Cloud &bull; Layanan Pengelolaan SIMS Sekolah</span>
            <span>&bull;</span>
            <a href="{{ route('dev.portal.login') }}">Pengembang</a>
        </footer>
    </div>

    <script>
        function copyAccountNumber() {
            const accText = document.getElementById('accountNumberText').innerText.trim();
            navigator.clipboard.writeText(accText).then(() => {
                const btnText = document.getElementById('copyBtnText');
                const orig = btnText.innerText;
                btnText.innerText = 'Tersalin!';
                setTimeout(() => {
                    btnText.innerText = orig;
                }, 2000);
            }).catch(() => {
                alert('Nomor Rekening: ' + accText);
            });
        }
    </script>
</body>
</html>
