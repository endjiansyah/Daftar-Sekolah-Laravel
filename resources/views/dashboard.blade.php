<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - PPDB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --royal-blue: #002366;
            --gold-theme: #ffc107;
            --soft-blue: #f0f4f8;
        }

        body {
            background-color: #f4f7f9;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .profile-header {
            background-color: var(--royal-blue);
            height: 80px;
            margin-bottom: -40px;
        }

        .avatar-container img {
            border: 4px solid white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        .score-box {
            background-color: var(--soft-blue);
            border: 1.5px dashed var(--royal-blue);
            border-radius: 12px;
            padding: 15px;
        }

        .nav-tabs {
            border-bottom: 2px solid #eee;
            background: white;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #777;
            font-weight: 700;
            padding: 15px;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            transition: 0.3s;
            text-transform: uppercase;
        }

        .nav-tabs .nav-link.active {
            color: var(--royal-blue);
            border-bottom: 3px solid var(--gold-theme);
            background: none;
        }

        .data-label {
            color: #888;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .data-value {
            color: var(--royal-blue);
            font-weight: 700;
            font-size: 1rem;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 8px 15px;
            border-radius: 30px;
            font-weight: 800;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row g-4">

            {{-- Kolom Kiri: Profil & Keamanan --}}
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="profile-header"></div>
                    <div class="card-body text-center pt-0">
                        <div class="avatar-container mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->full_name) }}&background=002366&color=fff&size=128"
                                class="rounded-circle" alt="Profile" width="100">
                        </div>
                        <h5 class="fw-bold mb-1" style="color: var(--royal-blue);">{{ Auth::user()->full_name }}</h5>
                        <p class="text-muted small mb-3">{{ Auth::user()->email }}</p>

                        <div class="score-box mb-3">
                            <div class="small text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem;">Rata-rata Nilai Ijazah</div>
                            <div class="h3 fw-bold mb-0" style="color: var(--royal-blue);">
                                {{ number_format(Auth::user()->average_score ?? 0, 2) }}
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <span class="status-badge {{ Auth::user()->status->name == 'DAFTAR' ? 'bg-warning text-dark' : 'bg-success text-white' }} mb-2 text-uppercase text-center">
                                <i class="bi bi-patch-check-fill me-1"></i> Status: {{ Auth::user()->status->name ?? 'DAFTAR' }}
                            </span>

                            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm fw-bold">
                                <i class="bi bi-pencil-square me-1"></i> Perbarui Profil
                            </a>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-link text-danger btn-sm text-decoration-none fw-bold mt-1">
                                    <i class="bi bi-box-arrow-right"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Keamanan Akun dengan Password Lama --}}
                <div class="card shadow-sm border-start border-danger border-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3 d-flex align-items-center">
                            <i class="bi bi-shield-lock-fill me-2 text-danger"></i>Keamanan Akun
                        </h6>

                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Input Password Lama --}}
                            <div class="mb-2">
                                <label class="form-label small fw-bold text-muted">Password Saat Ini</label>
                                <input type="password" name="current_password" class="form-control form-control-sm @error('current_password') is-invalid @enderror" placeholder="******" required>
                                @error('current_password')
                                <div class="invalid-feedback small">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-3 opacity-25">

                            {{-- Input Password Baru --}}
                            <div class="mb-2">
                                <label class="form-label small fw-bold text-muted">Password Baru</label>
                                <input type="password" name="password" class="form-control form-control-sm @error('password') is-invalid @enderror" placeholder="Min. 8 Karakter" required>
                                @error('password')
                                <div class="invalid-feedback small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Konfirmasi Password Baru --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control form-control-sm" placeholder="******" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-danger btn-sm fw-bold">
                                    <i class="bi bi-key-fill me-1"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Detail --}}
            <div class="col-lg-8">
                @if(empty(Auth::user()->nisn) || empty(Auth::user()->address) || !Auth::user()->parentDetail || !Auth::user()->schoolDetail)
                <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-4" style="border-left: 5px solid #ffc107 !important;">
                    <i class="bi bi-exclamation-circle-fill fs-3 me-3"></i>
                    <div>
                        <h6 class="alert-heading fw-bold mb-1">Data Belum Lengkap!</h6>
                        <p class="mb-0 small">Mohon lengkapi Biodata, Orang Tua, dan Sekolah agar panitia dapat memverifikasi berkas Anda.</p>
                    </div>
                </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-white p-0">
                        <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#bio">Biodata</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#ortu">Orang Tua</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sekolah">Sekolah</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-4">
                        <div class="tab-content">

                            {{-- Tab Biodata --}}
                            <div class="tab-pane fade show active" id="bio">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="data-label">Nama Lengkap</div>
                                        <div class="data-value">{{ Auth::user()->full_name }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="data-label">NISN</div>
                                        <div class="data-value">{{ Auth::user()->nisn ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="data-label">Jenis Kelamin</div>
                                        <div class="data-value">{{ Auth::user()->gender == 'L' ? 'Laki-laki' : (Auth::user()->gender == 'P' ? 'Perempuan' : '-') }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="data-label">Tempat, Tanggal Lahir</div>
                                        <div class="data-value">
                                            {{ Auth::user()->birthCity->name ?? '-' }},
                                            {{ Auth::user()->dob ? Auth::user()->dob->format('d F Y') : '-' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="data-label">No. HP / WhatsApp</div>
                                        <div class="data-value">{{ Auth::user()->phone_number ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="data-label">Email</div>
                                        <div class="data-value">{{ Auth::user()->email }}</div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="data-label">Alamat Rumah</div>
                                        <div class="data-value small">{{ Auth::user()->address ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tab Orang Tua --}}
                            <div class="tab-pane fade" id="ortu">
                                @if(Auth::user()->parentDetail)
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="data-label">Nama Orang Tua / Wali</div>
                                        <div class="data-value">{{ Auth::user()->parentDetail->parent_name }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="data-label">Hubungan</div>
                                        <div class="data-value">{{ Auth::user()->parentDetail->relationship }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="data-label">WhatsApp Orang Tua</div>
                                        <div class="data-value">{{ Auth::user()->parentDetail->parent_phone }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="data-label">Email Orang Tua</div>
                                        <div class="data-value">{{ Auth::user()->parentDetail->parent_email ?? '-' }}</div>
                                    </div>
                                </div>
                                @else
                                <div class="text-center py-5">
                                    <p class="text-muted">Data orang tua belum dilengkapi.</p>
                                </div>
                                @endif
                            </div>

                            {{-- Tab Sekolah --}}
                            <div class="tab-pane fade" id="sekolah">
                                @if(Auth::user()->schoolDetail)
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="data-label">Sekolah Asal (SMP/MTS)</div>
                                        <div class="data-value">{{ Auth::user()->schoolDetail->school_name }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="data-label">Kota Sekolah</div>
                                        <div class="data-value">{{ Auth::user()->schoolDetail->city->name ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="data-label">Tahun Lulus</div>
                                        <div class="data-value">{{ Auth::user()->schoolDetail->graduation_year }}</div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="data-label">Alamat Sekolah</div>
                                        <div class="data-value small">{{ Auth::user()->schoolDetail->school_address ?? '-' }}</div>
                                    </div>
                                </div>
                                @else
                                <div class="text-center py-5">
                                    <p class="text-muted">Data sekolah asal belum dilengkapi.</p>
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