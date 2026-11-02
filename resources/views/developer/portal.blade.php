<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skillance Control Center - Manajemen Fitur PRO</title>
    @include('partials.favicon')
    <meta name="robots" content="noindex, nofollow, noarchive">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #090d16;
            --card-bg: #0f172a;
            --card-border: #1e293b;
            --primary: #3b82f6;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text-main);
            line-height: 1.6;
            padding: 30px 20px 80px;
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
        }

        /* Top Nav */
        .top-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 14px;
            padding: 16px 24px;
            margin-bottom: 24px;
        }

        .brand-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #2563eb, #06b6d4);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 800;
        }

        .brand-text h2 {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .brand-text p {
            font-size: 11px;
            color: var(--text-muted);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn {
            font-family: inherit;
            font-size: 12.5px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.25);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        /* Success Alert */
        .alert-success {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Overview Banner */
        .overview-card {
            background: linear-gradient(135deg, #0f172a 0%, #172554 100%);
            border: 1px solid #1e3a8a;
            border-radius: 16px;
            padding: 24px 28px;
            margin-bottom: 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .client-info h1 {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .client-info p {
            font-size: 13px;
            color: #93c5fd;
        }

        .client-badge {
            background: rgba(37, 99, 235, 0.3);
            border: 1px solid #60a5fa;
            color: #bfdbfe;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        /* Section Title */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .section-header h3 {
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #cbd5e1;
        }

        /* Features Switchboard Grid */
        .features-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
            margin-bottom: 32px;
        }

        .feature-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 12px;
            padding: 18px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            transition: border 0.2s;
        }

        .feature-card.active {
            border-color: rgba(59, 130, 246, 0.5);
            background: #0f1b33;
        }

        .feature-left {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .feature-icon-badge {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #1e293b;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .feature-card.active .feature-icon-badge {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
        }

        .feature-title {
            font-size: 14.5px;
            font-weight: 700;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cat-tag {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 2px 6px;
            border-radius: 4px;
            background: #1e293b;
            color: #94a3b8;
        }

        .feature-desc {
            font-size: 12.5px;
            color: var(--text-muted);
            margin-top: 4px;
            line-height: 1.4;
        }

        /* Modern Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 52px;
            height: 28px;
            flex-shrink: 0;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #334155;
            transition: .3s;
            border-radius: 28px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .3s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--primary);
        }

        input:checked + .slider:before {
            transform: translateX(24px);
        }

        /* Security Audit Card */
        .audit-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 14px;
            padding: 20px 24px;
            margin-bottom: 28px;
        }

        .audit-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-top: 14px;
        }

        .audit-item {
            background: #090d16;
            border: 1px solid #1e293b;
            border-radius: 10px;
            padding: 12px 14px;
        }

        .audit-label {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .audit-val {
            font-size: 13.5px;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
            color: #ffffff;
            margin-top: 4px;
        }

        .save-bar {
            position: sticky;
            bottom: 20px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid #334155;
            border-radius: 14px;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body>

    <div class="container">
        
        <!-- Top Navigation -->
        <nav class="top-nav">
            <div class="brand-group">
                <div class="brand-icon">⚡</div>
                <div class="brand-text">
                    <h2>SKILLANCE CONTROL CENTER</h2>
                    <p>Developer Master Portal & Modular Feature Activation</p>
                </div>
            </div>
            <div class="nav-actions">
                <form action="{{ route('dev.portal.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        🔒 Kunci & Keluar Portal
                    </button>
                </form>
            </div>
        </nav>

        @if (session('success'))
            <div class="alert-success">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <!-- Client Info -->
        <div class="overview-card">
            <div class="client-info">
                <h1>SMP Negeri 2 Tanggetada</h1>
                <p>Domain: <code>https://smpn2tanggetada.sch.id</code> &nbsp;|&nbsp; Klien: Ir. Iwan Taufik</p>
            </div>
            <div class="client-badge">
                Pilot Project SaaS
            </div>
        </div>

        <!-- Form Feature Toggles -->
        <form action="{{ route('dev.portal.update') }}" method="POST">
            @csrf

            <div class="section-header">
                <h3>🎛️ Sakelar Modul Fitur PRO (Modular Add-ons)</h3>
                <span style="font-size: 12px; color: #94a3b8;">Hanya fitur bertanda <strong>AKTIF</strong> yang akan muncul di menu sekolah.</span>
            </div>

            <div class="features-grid">
                @foreach ($features as $key => $feature)
                    <div class="feature-card {{ $feature['is_active'] ? 'active' : '' }}">
                        <div class="feature-left">
                            <div class="feature-icon-badge">
                                @if (str_contains($key, 'absensi')) 👨‍🏫
                                @elseif(str_contains($key, 'spp')) 💳
                                @elseif(str_contains($key, 'whatsapp')) 💬
                                @elseif(str_contains($key, 'jurnal')) 📖
                                @else 📝
                                @endif
                            </div>
                            <div>
                                <div class="feature-title">
                                    {{ $feature['name'] }}
                                    <span class="cat-tag">{{ $feature['category'] }}</span>
                                </div>
                                <div class="feature-desc">{{ $feature['desc'] }}</div>
                            </div>
                        </div>

                        <label class="switch">
                            <input 
                                type="checkbox" 
                                name="{{ $key }}" 
                                value="1" 
                                {{ $feature['is_active'] ? 'checked' : '' }}
                            >
                            <span class="slider"></span>
                        </label>
                    </div>
                @endforeach
            </div>

            <!-- Security Audit Card -->
            <div class="audit-card">
                <div class="section-header" style="margin-bottom: 0;">
                    <h3 style="color: #10b981;">🛡️ Audit Keamanan Sistem & Server</h3>
                    <span style="font-size: 11px; color: #10b981; font-weight: 600;">STATUS: TERLINDUNGI</span>
                </div>
                <div class="audit-grid">
                    <div class="audit-item">
                        <div class="audit-label">Environment</div>
                        <div class="audit-val">{{ $securityAudit['app_env'] }}</div>
                    </div>
                    <div class="audit-item">
                        <div class="audit-label">Debug Mode</div>
                        <div class="audit-val" style="color: {{ str_contains($securityAudit['app_debug'], 'AMAN') ? '#10b981' : '#f59e0b' }}">
                            {{ $securityAudit['app_debug'] }}
                        </div>
                    </div>
                    <div class="audit-item">
                        <div class="audit-label">Security Headers</div>
                        <div class="audit-val" style="color: #10b981;">{{ $securityAudit['security_headers'] }}</div>
                    </div>
                    <div class="audit-item">
                        <div class="audit-label">Database Engine</div>
                        <div class="audit-val">{{ $securityAudit['database_status'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Sticky Bottom Save Bar -->
            <div class="save-bar">
                <div style="font-size: 13px; color: #cbd5e1;">
                    Perubahan langsung berlaku instan pada menu antarmuka sekolah.
                </div>
                <button type="submit" class="btn btn-primary">
                    💾 Simpan Konfigurasi Fitur
                </button>
            </div>

        </form>

    </div>

</body>
</html>
