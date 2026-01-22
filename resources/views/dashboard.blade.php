<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - PPDB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .nav-tabs .nav-link { border: none; color: #6c757d; font-weight: 600; padding: 15px 20px; }
        .nav-tabs .nav-link.active { color: #0d6efd; border-bottom: 3px solid #0d6efd; background: none; }
        .profile-header { background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%); color: white; border-radius: 15px; padding: 30px; margin-bottom: 30px; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-4">
            <div class="card p-4 text-center mb-4">
                <div class="mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->full_name) }}&background=0D6EFD&color=fff&size=128" 
                         class="rounded-circle img-thumbnail" alt="Profile">
                </div>
                <h5 class="fw-bold mb-1">{{ Auth::user()->full_name }}</h5>
                <p class="text-muted small mb-3">{{ Auth::user()->email }}</p>
                <div class="d-grid gap-2">
                    <span class="badge bg-primary py-2 mb-2">STATUS: {{ Auth::user()->status ?? 'DAFTAR' }}</span>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
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
        </div>

        <div class="col-lg-8">
            @if(empty(Auth::user()->nisn) || empty(Auth::user()->address))
                <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                    <div>
                        <h6 class="alert-heading fw-bold mb-1">Perhatian! Data Anda Belum Lengkap.</h6>
                        <p class="mb-0 small">Segera lengkapi biodata, data orang tua, dan sekolah asal melalui tombol edit.</p>
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
                        <div class="tab-pane fade show active" id="bio">
                            <div class="row mb-3">
                                <div class="col-sm-4 text-muted">NISN</div>
                                <div class="col-sm-8 fw-bold">{{ Auth::user()->nisn ?? '-' }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 text-muted">Jenis Kelamin</div>
                                <div class="col-sm-8 fw-bold">{{ Auth::user()->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 text-muted">Tempat, Tgl Lahir</div>
                                <div class="col-sm-8 fw-bold">
                                    {{ Auth::user()->birthCity->name ?? '-' }}, 
                                    {{ Auth::user()->dob ? date('d F Y', strtotime(Auth::user()->dob)) : '-' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 text-muted">No. WhatsApp</div>
                                <div class="col-sm-8 fw-bold">{{ Auth::user()->phone_number ?? '-' }}</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 text-muted">Alamat Tinggal</div>
                                <div class="col-sm-8 fw-bold small">{{ Auth::user()->address ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="ortu">
                            @if(Auth::user()->parentDetail)
                                <div class="row mb-3"><div class="col-sm-4 text-muted">Nama Orang Tua</div><div class="col-sm-8 fw-bold">{{ Auth::user()->parentDetail->parent_name }}</div></div>
                                <div class="row mb-3"><div class="col-sm-4 text-muted">Hubungan</div><div class="col-sm-8 fw-bold">{{ Auth::user()->parentDetail->relationship }}</div></div>
                                <div class="row mb-3"><div class="col-sm-4 text-muted">WhatsApp Ortu</div><div class="col-sm-8 fw-bold">{{ Auth::user()->parentDetail->parent_phone }}</div></div>
                            @else
                                <div class="text-center py-4 text-muted">Data orang tua belum dilengkapi.</div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="sekolah">
                            @if(Auth::user()->schoolDetail)
                                <div class="row mb-3"><div class="col-sm-4 text-muted">Nama Sekolah</div><div class="col-sm-8 fw-bold">{{ Auth::user()->schoolDetail->school_name }}</div></div>
                                <div class="row mb-3"><div class="col-sm-4 text-muted">Kota Sekolah</div><div class="col-sm-8 fw-bold">{{ Auth::user()->schoolDetail->city->name ?? '-' }}</div></div>
                                <div class="row mb-3"><div class="col-sm-4 text-muted">Tahun Lulus</div><div class="col-sm-8 fw-bold">{{ Auth::user()->schoolDetail->graduation_year }}</div></div>
                            @else
                                <div class="text-center py-4 text-muted">Data sekolah asal belum dilengkapi.</div>
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