<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skillance Gateway - Developer Authorization</title>
    @include('partials.favicon')
    <meta name="robots" content="noindex, nofollow, noarchive">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: #090d16;
            color: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
            background: #0f172a;
            border: 1px solid #1e293b;
            border-radius: 16px;
            padding: 36px 32px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            position: relative;
            overflow: hidden;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #06b6d4, #10b981);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .shield-icon {
            width: 48px;
            height: 48px;
            background: rgba(59, 130, 246, 0.12);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 12px;
        }

        .brand-header h1 {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #ffffff;
            text-transform: uppercase;
        }

        .brand-header p {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 12px;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-key {
            width: 100%;
            background: #090d16;
            border: 1px solid #334155;
            border-radius: 10px;
            padding: 12px 14px;
            color: #ffffff;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            outline: none;
            transition: all 0.2s;
        }

        .input-key:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .btn-submit {
            width: 100%;
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .footer-note {
            margin-top: 24px;
            text-align: center;
            font-size: 11px;
            color: #475569;
            line-height: 1.5;
        }

        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #10b981;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="brand-header">
            <div class="shield-icon">🔐</div>
            <h1>Skillance Gateway</h1>
            <p>Portal Khusus Pengembang & Konfigurasi Fitur PRO</p>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    <div>⚠️ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('dev.portal.auth') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="secret_key">Kunci Rahasia Developer (Master Key)</label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        id="secret_key" 
                        name="secret_key" 
                        class="input-key" 
                        placeholder="••••••••••••••••••••••••••••"
                        required 
                        autocomplete="off"
                        autofocus
                    >
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <span>Verifikasi Otoritas</span> →
            </button>
        </form>

        <div class="footer-note">
            <div class="security-badge">
                <span>🛡️</span> Akses Terproteksi Rate-Limit (5x / menit)
            </div>
            <div style="margin-top: 4px;">Halaman ini terisolasi dan tidak ditampilkan di menu publik.</div>
        </div>
    </div>

</body>
</html>
