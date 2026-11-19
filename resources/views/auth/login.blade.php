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
        body {
            background: #0033a0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            margin: 0;
        }
        .login-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            padding: 2.75rem 2rem 2.25rem;
            width: 100%;
            max-width: 420px;
        }

        /* Logo & Judul */
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header img {
            max-height: 90px;
            width: auto;
            max-width: 100%;
            margin-bottom: 1.25rem;
        }
        .login-header h1 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 .35rem;
        }
        .login-header p {
            color: #64748b;
            font-size: .875rem;
            margin: 0;
        }

        /* Form */
        .form-label {
            font-weight: 600;
            font-size: .85rem;
            color: #334155;
            margin-bottom: .4rem;
        }
        .form-control {
            padding: .7rem 1rem;
            border-radius: 8px;
            border: 1.5px solid #e2e8f0;
            font-size: .9rem;
            transition: all .15s;
        }
        .form-control:focus {
            border-color: #0033a0;
            box-shadow: 0 0 0 3px rgba(0, 51, 160, 0.1);
        }
        .form-check-label {
            font-size: .85rem;
            color: #64748b;
        }
        .form-check-input:checked {
            background-color: #0033a0;
            border-color: #0033a0;
        }

        /* Button */
        .btn-login {
            background: #0033a0;
            border: none;
            color: #fff;
            padding: .75rem;
            font-weight: 600;
            font-size: .95rem;
            border-radius: 8px;
            width: 100%;
            transition: all .15s;
        }
        .btn-login:hover {
            background: #002270;
        }

        /* Alert Error */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: .7rem .9rem;
            border-radius: 8px;
            font-size: .85rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1.25rem;
        }

        /* Demo Box */
        .demo-box {
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid #e2e8f0;
            font-size: .78rem;
            color: #64748b;
            text-align: center;
        }
        .demo-box code {
            color: #0033a0;
            background: #eff6ff;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: .75rem;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 2rem 1.5rem 1.75rem;
            }
            .login-header img {
                max-height: 70px;
            }
            .login-header h1 {
                font-size: 1.15rem;
            }
        }
    </style>
</head>
<body>

    <div class="login-card">

        {{-- Header --}}
        <div class="login-header">
            <img src="{{ asset('images/logo-trix.png') }}" alt="TRIX">
            <h1>Helpdesk TRIX</h1>
            <p>Silakan login untuk melanjutkan</p>
        </div>

        {{-- Error --}}
        @if ($errors->any())
            <div class="alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email') }}"
                       placeholder="nama@trix.co.id"
                       required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control"
                       placeholder="Masukkan password"
                       required>
            </div>

            <div class="form-check mb-4">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">
                    Ingat saya
                </label>
            </div>

            <button type="submit" class="btn-login">
                Login
            </button>
        </form>

        {{-- Demo --}}
        <div class="demo-box">
            <div class="mb-1"><strong>Akun demo:</strong></div>
            <div>
                IT <code>it@example.com</code> &nbsp;•&nbsp;
                Pelapor <code>pelapor@example.com</code>
            </div>
            <div class="mt-1">Password: <code>password</code></div>
        </div>

    </div>

</body>
</html>