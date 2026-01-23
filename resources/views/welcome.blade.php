<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB Online - Selamat Datang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom Biru Formal & Styling Dasar */
        :root {
            --royal-blue: #002366;
        }
        
        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; 
        }

        .bg-royal-blue { 
            background-color: var(--royal-blue); 
        }

        .hero-header {
            padding: 80px 0;
            border-bottom: 5px solid #ffc107; /* Aksen Kuning Emas */
        }

        .logo-img { 
            height: 110px; 
            width: auto;
            /* Filter ini opsional, untuk memberi bayangan halus pada logo agar tidak flat */
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.2));
        }

        .card-step {
            transition: all 0.3s ease;
            border: none;
        }

        .card-step:hover {
            transform: translateY(-5px);
        }

        /* Menyesuaikan warna tombol outline agar tidak terlalu tipis di background biru */
        .btn-outline-light {
            border-width: 2px;
        }
    </style>
</head>
<body>

    <header class="hero-header bg-royal-blue text-white text-center shadow">
        <div class="container">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img mb-4">
            
            <h1 class="display-5 fw-bold mb-2">Sistem Informasi PPDB</h1>
            <p class="lead mb-5 text-white-50">Portal Resmi Penerimaan Peserta Didik Baru Tahun Ajaran 2026/2027</p>
            
            <div class="d-flex justify-content-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-warning btn-lg px-4 fw-bold shadow-sm">Buka Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-4 fw-bold shadow-sm">Daftar Sekarang</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4 fw-bold">Masuk Akun</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="container my-5 px-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Alur Pendaftaran</h2>
            <div class="mx-auto bg-warning" style="height: 4px; width: 60px; border-radius: 2px;"></div>
        </div>

        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="card card-step shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="display-6 text-warning mb-3 fw-bold">01</div>
                        <h5 class="fw-bold">Registrasi</h5>
                        <p class="text-muted small mb-0">Buat akun menggunakan username dan email untuk mendapatkan akses penuh ke sistem pendaftaran.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-step shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="display-6 text-warning mb-3 fw-bold">02</div>
                        <h5 class="fw-bold">Lengkapi Profil</h5>
                        <p class="text-muted small mb-0">Isi data diri, data orang tua, dan asal sekolah Anda melalui dashboard pendaftar.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-step shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="display-6 text-warning mb-3 fw-bold">03</div>
                        <h5 class="fw-bold">Hasil Seleksi</h5>
                        <p class="text-muted small mb-0">Pantau status verifikasi dan hasil seleksi akhir secara berkala pada akun Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center py-4 bg-white border-top">
        <div class="container text-muted">
            <small>&copy; 2026 <strong>EndProjects</strong>. Seluruh Hak Cipta Dilindungi.</small>
        </div>
    </footer>

</body>
</html>