<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Helpdesk TRIX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }

        /* ===== Halaman ===== */
        html, body { height: 100%; }
        body {
            margin: 0;
            background-color: #eef3f9;
            background-image:
                radial-gradient(circle at 10% 20%, rgba(200, 220, 245, 0.5), transparent 45%),
                radial-gradient(circle at 90% 80%, rgba(180, 210, 240, 0.4), transparent 45%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image: radial-gradient(rgba(80, 120, 170, 0.08) 1px, transparent 1px);
            background-size: 24px 24px;
            pointer-events: none;
            z-index: 0;
        }

        /* ===== Shell ===== */
        .login-shell {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            width: 100%;
            max-width: 980px;
            min-height: 580px;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow:
                0 30px 60px rgba(25, 45, 80, 0.12),
                0 4px 12px rgba(25, 45, 80, 0.06);
            animation: shellIn .65s cubic-bezier(.22, 1, .36, 1) both;
        }

        @keyframes shellIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ===== Panel Kiri ===== */
        .brand-panel {
            position: relative;
            background: #cce0f5; /* Pastel Light Blue */
            background-image: 
                radial-gradient(ellipse 100% 80% at 0% 0%, rgba(255, 255, 255, 0.7), transparent 70%),
                radial-gradient(ellipse 100% 100% at 100% 100%, rgba(150, 190, 235, 0.3), transparent 60%);
            color: #1a3a5c;
            padding: 3rem 3rem 2.75rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        /* Pola titik */
        .brand-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(40, 80, 140, 0.1) 1.5px, transparent 1.5px);
            background-size: 24px 24px;
            pointer-events: none;
        }

        /* Lingkaran dekoratif */
        .brand-panel::after {
            content: "";
            position: absolute;
            width: 350px; height: 350px;
            right: -100px; bottom: -100px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.5) 0%, transparent 70%);
            pointer-events: none;
            animation: pulse 12s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50%      { transform: scale(1.05); }
        }

        /* ===== HEADER LOGO & TEKS ===== */
        .brand-top {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            animation: fadeUp .55s ease-out .1s both;
        }

        .brand-top img {
            max-height: 40px;
            width: auto;
        }

        .brand-divider {
            width: 2px;
            height: 28px;
            background-color: rgba(26, 58, 92, 0.25);
            border-radius: 2px;
        }

        /* Teks sangat tegas dengan Outline (Stroke) */
        .brand-title {
            font-size: 1.55rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            margin: 0;
            color: #ffffff; /* Isi putih */
            /* Outline biru gelap tebal + Drop shadow halus */
            -webkit-text-stroke: 1px #0f2c4a; 
            text-shadow: 
                0px 3px 0px #0f2c4a,
                0px 4px 6px rgba(0, 0, 0, 0.3);
        }

        /* ===== ELEMEN ILUSTRASI (Tanpa Teks) ===== */
        .brand-visual {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeUp .55s ease-out .2s both;
            margin-top: 1rem;
        }

        .illustration-box {
            position: relative;
            width: 100%;
            max-width: 300px;
            aspect-ratio: 1/1;
        }

        /* Lingkaran-lingkaran konsentris abstrak */
        .il-circle {
            position: absolute;
            border-radius: 50%;
            border: 2px dashed rgba(27, 99, 171, 0.3);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
        }
        .il-c1 { width: 100%; height: 100%; animation: spin 25s linear infinite; }
        .il-c2 { width: 75%; height: 75%; border-style: solid; border-width: 1px; border-color: rgba(27, 99, 171, 0.15); }
        .il-c3 { width: 50%; height: 50%; border: 2px solid rgba(255, 255, 255, 0.8); background: rgba(255,255,255,0.2); backdrop-filter: blur(4px); }

        /* Ikon utama di tengah */
        .il-core {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 70px; height: 70px;
            background: #1b63ab;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            box-shadow: 0 10px 25px rgba(27, 99, 171, 0.4);
            z-index: 3;
        }

        /* Titik-titik node di orbit */
        .il-node {
            position: absolute;
            width: 16px; height: 16px;
            background: #ffffff;
            border: 3px solid #1b63ab;
            border-radius: 50%;
            z-index: 2;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .node-1 { top: 10%; left: 15%; }
        .node-2 { bottom: 15%; right: 10%; }
        .node-3 { top: 40%; right: 0%; }

        @keyframes spin { 100% { transform: translate(-50%, -50%) rotate(360deg); } }

        .brand-bottom {
            position: relative;
            z-index: 2;
            font-size: .75rem;
            color: #557799;
            font-weight: 600;
            animation: fadeUp .55s ease-out .3s both;
            margin-top: 1rem;
            text-align: center;
        }

        /* ===== Panel Kanan (Form) ===== */
        .form-panel {
            padding: 3rem 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .form-head {
            margin-bottom: 2rem;
            animation: fadeUp .55s ease-out .12s both;
        }
        .form-head h1 {
            font-size: 1.7rem;
            font-weight: 700;
            color: #1a2b3f;
            letter-spacing: -.015em;
            margin: 0 0 .4rem;
        }
        .form-head p {
            color: #6b7d91;
            font-size: .9rem;
            margin: 0;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ===== Form controls ===== */
        .form-group-anim { animation: fadeUp .55s ease-out both; }
        .form-group-anim:nth-of-type(1) { animation-delay: .2s; }
        .form-group-anim:nth-of-type(2) { animation-delay: .27s; }
        .form-group-anim:nth-of-type(3) { animation-delay: .34s; }
        .form-group-anim:nth-of-type(4) { animation-delay: .41s; }

        .form-label {
            font-weight: 600;
            font-size: .82rem;
            letter-spacing: .01em;
            color: #33475c;
            margin-bottom: .45rem;
        }

        .input-group-icon { position: relative; }
        .input-group-icon .bi {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #8a9bb0;
            font-size: .95rem;
            transition: color .2s;
            pointer-events: none;
        }

        .form-control {
            height: 48px;
            padding: .6rem 1rem .6rem 2.6rem;
            border-radius: 10px;
            border: 1.5px solid #dce3eb;
            font-size: .9rem;
            color: #1a2b3f;
            background: #fafbfd;
            transition: border-color .2s, background-color .2s, box-shadow .2s;
        }
        .form-control::placeholder { color: #9daec1; }
        .form-control:focus {
            background: #ffffff;
            border-color: #1b63ab;
            box-shadow: 0 0 0 3px rgba(27, 99, 171, 0.12);
        }
        .input-group-icon:focus-within .bi { color: #1b63ab; }

        .form-check-label { font-size: .85rem; color: #5b6c80; }
        .form-check-input { border-color: #c2cedb; cursor: pointer; }
        .form-check-input:checked {
            background-color: #1b63ab;
            border-color: #1b63ab;
        }

        /* ===== Button ===== */
        .btn-login {
            height: 48px;
            background: #153b6b;
            border: none;
            color: #ffffff;
            font-weight: 600;
            font-size: .95rem;
            letter-spacing: .02em;
            border-radius: 10px;
            width: 100%;
            transition: background-color .2s, transform .1s, box-shadow .2s;
            box-shadow: 0 6px 16px rgba(21, 59, 107, 0.25);
        }
        .btn-login:hover {
            background: #0f2d53;
            color: #ffffff;
            box-shadow: 0 10px 22px rgba(21, 59, 107, 0.35);
            transform: translateY(-1px);
        }
        .btn-login:active { transform: translateY(1px); }
        .btn-login:focus-visible {
            outline: 2px solid #1b63ab;
            outline-offset: 2px;
        }

        /* ===== Alert Error ===== */
        .alert-error {
            background: #fdf3f3;
            border: 1px solid #f0d3d3;
            color: #943838;
            padding: .7rem .9rem;
            border-radius: 10px;
            font-size: .85rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1.25rem;
            animation: fadeUp .35s ease-out both;
        }

        /* ===== Demo Box ===== */
        .demo-box {
            margin-top: 1.75rem;
            padding: 1rem;
            border-radius: 12px;
            background: #f5f8fc;
            border: 1px solid #e5ebf2;
            font-size: .8rem;
            color: #5b6c80;
            text-align: center;
            animation: fadeUp .55s ease-out .48s both;
        }
        .demo-box strong { color: #33475c; }
        .demo-box code {
            color: #153b6b;
            background: #e3ebf3;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: .78rem;
            font-weight: 600;
        }

        /* ===== Responsive ===== */
        @media (max-width: 880px) {
            .login-shell {
                grid-template-columns: 1fr;
                max-width: 460px;
                min-height: unset;
            }
            .brand-panel {
                padding: 2rem 2rem 1.5rem;
            }
            .brand-visual, .brand-bottom {
                display: none;
            }
            .brand-top {
                justify-content: center;
            }
            .form-panel { padding: 2.5rem 2rem; }
            .form-head { text-align: center; }
        }

        @media (max-width: 480px) {
            body { padding: 1rem; }
            .login-shell { border-radius: 16px; }
            .form-panel { padding: 2rem 1.5rem; }
            .form-head h1 { font-size: 1.5rem; }
            .brand-title { font-size: 1.3rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            .login-shell, .brand-panel::after, .il-c1,
            .brand-top, .brand-visual, .brand-bottom,
            .form-head, .form-group-anim, .demo-box {
                animation: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="login-shell">

        <!-- Panel Kiri -->
        <aside class="brand-panel">
            <!-- Bagian Atas: Logo & Judul -->
            <div class="brand-top">
                <img src="{{ asset('images/logo-trix.png') }}" alt="TRIX Logo">
                <div class="brand-divider"></div>
                <!-- Teks diubah menggunakan stroke -->
                <h1 class="brand-title">Helpdesk TRIX</h1>
            </div>

            <!-- Bagian Tengah: Ilustrasi Abstrak -->
            <div class="brand-visual">
                <div class="illustration-box">
                    <div class="il-circle il-c1"></div>
                    <div class="il-circle il-c2"></div>
                    <div class="il-circle il-c3"></div>
                    
                    <div class="il-node node-1"></div>
                    <div class="il-node node-2"></div>
                    <div class="il-node node-3"></div>

                    <div class="il-core">
                        <i class="bi bi-life-preserver"></i>
                    </div>
                </div>
            </div>

            <!-- Bagian Bawah: Copyright -->
            <div class="brand-bottom">
                &copy; 2026 Helpdesk TRIX
            </div>
        </aside>

        <!-- Panel Kanan (Form) -->
        <main class="form-panel">

            <div class="form-head">
                <h1>Masuk ke akun</h1>
                <p>Silakan login untuk melanjutkan</p>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="mb-3 form-group-anim">
                    <label class="form-label">Email</label>
                    <div class="input-group-icon">
                        <i class="bi bi-envelope"></i>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email') }}"
                               placeholder="nama@trix.co.id"
                               required autofocus>
                    </div>
                </div>

                <div class="mb-3 form-group-anim">
                    <label class="form-label">Password</label>
                    <div class="input-group-icon">
                        <i class="bi bi-lock"></i>
                        <input type="password" name="password" class="form-control"
                               placeholder="Masukkan password"
                               required>
                    </div>
                </div>

                <div class="form-check mb-4 form-group-anim">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn-login form-group-anim">
                    Login
                </button>
            </form>

            <div class="demo-box">
                <div class="mb-1"><strong>Akun demo:</strong></div>
                <div>
                    IT <code>it@example.com</code> &nbsp;•&nbsp;
                    Pelapor <code>pelapor@example.com</code>
                </div>
                <div class="mt-1">Password: <code>password</code></div>
            </div>

        </main>
    </div>

</body>
</html>