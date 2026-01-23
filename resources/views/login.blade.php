<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PPDB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --royal-blue: #002366;
        }

        body {
            background-color: #f4f7f9;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        /* Gaya Card yang selaras dengan tema utama */
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .card-header-theme {
            background-color: var(--royal-blue);
            padding: 30px;
            text-align: center;
            border-bottom: 4px solid #ffc107; /* Aksen Kuning Emas */
        }

        .logo-login {
            height: 70px;
            width: auto;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        /* Tombol Utama (Kuning/Gold) */
        .btn-theme {
            background-color: #ffc107;
            color: #000;
            font-weight: 700;
            padding: 12px;
            border: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-theme:hover {
            background-color: #eab000;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--royal-blue);
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #dee2e6;
        }

        .form-control:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.15);
        }

        .btn-back {
            color: var(--royal-blue);
            font-weight: 600;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .btn-back:hover {
            color: #0d6efd;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ url('/') }}" class="btn-back">
                        ← Kembali ke Beranda
                    </a>
                </div>

                <div class="card shadow">
                    <div class="card-header-theme text-white">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-login mb-3">
                        <h4 class="fw-bold mb-0">Masuk Akun</h4>
                        <p class="small text-white-50 mb-0">Portal PPDB Online 2026</p>
                    </div>

                    <div class="card-body p-4">
                        @if($errors->any())
                            <div class="alert alert-danger border-0 small mb-4">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label text-uppercase">Username / Email</label>
                                <input type="text"
                                    name="login"
                                    class="form-control @error('login') is-invalid @enderror"
                                    value="{{ old('login') }}"
                                    placeholder="Masukkan ID Anda"
                                    required
                                    autofocus>

                                @error('login')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-uppercase">Password</label>
                                <input type="password" 
                                       name="password" 
                                       class="form-control" 
                                       placeholder="••••••••"
                                       required>
                            </div>

                            <button type="submit" class="btn btn-theme w-100 mb-3 shadow-sm">
                                LOGIN SEKARANG
                            </button>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="small text-muted">Belum terdaftar? 
                        <a href="{{ route('register') }}" class="fw-bold text-decoration-none" style="color: var(--royal-blue);">Buat Akun Baru</a>
                    </p>
                    <hr class="w-25 mx-auto">
                    <p class="text-muted" style="font-size: 0.7rem;">&copy; 2026 <strong>EndProjects</strong></p>
                </div>

            </div>
        </div>
    </div>
</body>

</html>