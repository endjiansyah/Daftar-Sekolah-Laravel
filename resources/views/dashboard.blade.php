<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - PPDB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .nav-tabs {
            border-bottom: none;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 600;
            padding: 15px 20px;
            transition: 0.3s;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 3px solid #0d6efd;
            background: none;
        }

        .score-box {
            background-color: #f0f7ff;
            border: 1px dashed #0d6efd;
            border-radius: 10px;
            padding: 10px;
        }

        .tab-content {
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row">

            <div class="col-lg-4">
                {{-- Kartu Profil Kiri --}}
                <div class="card p-4 text-center mb-4">
                    <div class="mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->full_name) }}&background=0D6EFD&color=fff&size=128"
                            class="rounded-circle img-thumbnail" alt="Profile">
                    </div>
                    <h5 class="fw-bold mb-1">{{ Auth::user()->full_name }}</h5>
                    <p class="text-muted small mb-3">{{ Auth::user()->email }}</p>

                    {{-- HIGHLIGHT NILAI (Mengambil dari tabel users) --}}
                    <div class="score-box mb-3">
                        <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Rata-rata Nilai Ijazah</div>
                        <div class="h3 fw-bold text-primary mb-0">
                            {{ number_format(Auth::user()->average_score ?? 0, 2) }}
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <span class="badge {{ Auth::user()->status->name == 'DAFTAR' ? 'bg-warning' : 'bg-success' }} py-2 mb-2 text-uppercase">
                            STATUS: {{ Auth::user()->status->name ?? 'DAFTAR' }}
                        </span>

                        <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm shadow-sm">
                            <i class="bi bi-pencil-square me-1"></i> Edit / Lengkapi Profil
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link text-danger btn-sm text-decoration-none">
                                <i class="bi bi-box-arrow-right"></i> Keluar Aplikasi
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Ganti Password --}}
                <div class="card p-4 shadow-sm">
                    <h6 class="fw-bold mb-3 text-dark">
                        <i class="bi bi-shield-lock-fill me-2 text-danger"></i>Keamanan Akun
                    </h6>

                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-1 text-muted">Password Baru</label>
                            <input type="password" name="password" class="form-control form-control-sm" placeholder="Min. 8 Karakter">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold mb-1 text-muted">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-sm" placeholder="******">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger btn-sm">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                {{-- Notifikasi Data Belum Lengkap --}}
                @if(empty(Auth::user()->nisn) || empty(Auth::user()->address) || !Auth::user()->parentDetail || !Auth::user()->schoolDetail)
                <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                    <div>
                        <h6 class="alert-heading fw-bold mb-1">Perhatian! Data Anda Belum Lengkap.</h6>
                        <p class="mb-0 small">Beberapa informasi penting (NISN, Nilai, atau Data Sekolah) masih kosong. Segera lengkapi agar proses verifikasi dapat dilanjutkan.</p>
                    </div>
                </div>
                @endif

                <div class="card overflow-hidden">
                    <div class="card-header bg-white p-0">
                        <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#bio">BIODATA</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#ortu">ORANG TUA</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sekolah">SEKOLAH</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-4">
                        <div class="tab-content" id="myTabContent">

                            {{-- Tab Biodata: NISN & Nilai diposisikan di sini sesuai hirarki User --}}
                            <div class="tab-pane fade show active" id="bio">
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Nama Lengkap</div>
                                    <div class="col-sm-8 fw-bold">{{ Auth::user()->full_name }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">NISN</div>
                                    <div class="col-sm-8 fw-bold">{{ Auth::user()->nisn ?? '-' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Rata-rata Nilai</div>
                                    <div class="col-sm-8 fw-bold">{{ number_format(Auth::user()->average_score ?? 0, 2) }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Jenis Kelamin</div>
                                    <div class="col-sm-8 fw-bold">
                                        {{ Auth::user()->gender == 'L' ? 'Laki-laki' : (Auth::user()->gender == 'P' ? 'Perempuan' : '-') }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Tempat, Tgl Lahir</div>
                                    <div class="col-sm-8 fw-bold">
                                        {{ Auth::user()->birthCity->name ?? '-' }},
                                        {{ Auth::user()->dob ? Auth::user()->dob->format('d F Y') : '-' }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">No. WhatsApp</div>
                                    <div class="col-sm-8 fw-bold">{{ Auth::user()->phone_number ?? '-' }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Alamat Rumah</div>
                                    <div class="col-sm-8 fw-bold small text-secondary">{{ Auth::user()->address ?? '-' }}</div>
                                </div>
                            </div>

                            {{-- Tab Orang Tua --}}
                            <div class="tab-pane fade" id="ortu">
                                @if(Auth::user()->parentDetail)
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Nama Orang Tua</div>
                                    <div class="col-sm-8 fw-bold">{{ Auth::user()->parentDetail->parent_name }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Hubungan</div>
                                    <div class="col-sm-8 fw-bold">{{ Auth::user()->parentDetail->relationship }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">WhatsApp Ortu</div>
                                    <div class="col-sm-8 fw-bold">{{ Auth::user()->parentDetail->parent_phone }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Email Ortu</div>
                                    <div class="col-sm-8 fw-bold">{{ Auth::user()->parentDetail->parent_email ?? '-' }}</div>
                                </div>
                                @else
                                <div class="text-center py-5">
                                    <p class="text-muted">Data orang tua belum diisi.</p>
                                </div>
                                @endif
                            </div>

                            {{-- Tab Sekolah --}}
                            <div class="tab-pane fade" id="sekolah">
                                @if(Auth::user()->schoolDetail)
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Sekolah Asal</div>
                                    <div class="col-sm-8 fw-bold">{{ Auth::user()->schoolDetail->school_name }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Kota</div>
                                    <div class="col-sm-8 fw-bold">{{ Auth::user()->schoolDetail->city->name ?? '-' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Tahun Lulus</div>
                                    <div class="col-sm-8 fw-bold">{{ Auth::user()->schoolDetail->graduation_year }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Alamat Sekolah</div>
                                    <div class="col-sm-8 fw-bold small text-secondary">{{ Auth::user()->schoolDetail->school_address ?? '-' }}</div>
                                </div>
                                @else
                                <div class="text-center py-5">
                                    <p class="text-muted">Data sekolah belum diisi.</p>
                                </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>