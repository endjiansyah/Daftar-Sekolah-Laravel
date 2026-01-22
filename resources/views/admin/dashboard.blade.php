<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PPDB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', Tahoma, sans-serif; }
        .table-card { border: none; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow: hidden; }
        .section-title { background: #f8f9fa; padding: 10px 15px; border-left: 4px solid #0d6efd; font-weight: bold; color: #333; font-size: 0.85rem; letter-spacing: 0.5px; margin-bottom: 15px; }
        .info-label { color: #6c757d; font-size: 0.75rem; font-weight: 700; text-uppercase: true; margin-bottom: 2px; }
        .info-value { color: #212529; font-weight: 600; margin-bottom: 15px; font-size: 0.95rem; }
        .modal-content { border-radius: 20px; border: none; }
        .badge-status { font-size: 0.7rem; padding: 5px 12px; border-radius: 50px; font-weight: 700; }
        .btn-verif-fast { font-size: 0.75rem; font-weight: 700; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#"><i class="bi bi-shield-check me-2"></i>PPDB ADMIN</a>
        <div class="ms-auto d-flex align-items-center text-white">
            <span class="small me-3">Halo, {{ Auth::user()->full_name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm px-3">Keluar</button>
            </form>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold mb-0">Manajemen Pendaftaran</h3>
            <p class="text-muted small">Total Pendaftar: <strong>{{ $students->count() }}</strong> Siswa</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3">SISWA & EMAIL</th>
                            <th>NISN</th>
                            <th>ASAL SEKOLAH</th>
                            <th>STATUS</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        @php
                            // LOGIKA CEK KELENGKAPAN DATA
                            $isComplete = !empty($student->nisn) && 
                                         !empty($student->address) && 
                                         $student->parentDetail && 
                                         $student->schoolDetail;

                            $status = $student->status->name ?? 'DAFTAR';
                            $color = $status == 'DITERIMA' ? 'success' : ($status == 'DITOLAK' ? 'danger' : 'warning text-dark');
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ $student->full_name }}</div>
                                <div class="text-primary small" style="font-size: 0.75rem;">{{ $student->email }}</div>
                            </td>
                            <td><span class="badge bg-light text-dark border fw-normal">{{ $student->nisn ?? '-' }}</span></td>
                            <td>{{ $student->schoolDetail->school_name ?? '-' }}</td>
                            <td><span class="badge badge-status bg-{{ $color }}">{{ $status }}</span></td>
                            <td class="text-center px-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailSiswa{{ $student->id }}" title="Lihat Semua Data">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    @if($status == 'DAFTAR' && $isComplete)
                                    <form action="{{ route('admin.verify', [$student->id, 2]) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success btn-verif-fast shadow-sm" onclick="return confirm('Terima siswa ini?')">
                                            <i class="bi bi-check-circle me-1"></i> Verif
                                        </button>
                                    </form>
                                    @elseif($status == 'DAFTAR' && !$isComplete)
                                    <span class="badge bg-light text-danger border small d-flex align-items-center" style="font-size: 0.65rem;" title="Data belum lengkap">
                                        <i class="bi bi-x-circle me-1"></i> Belum Lengkap
                                    </span>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="detailSiswa{{ $student->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header border-0 bg-white shadow-sm">
                                        <h5 class="modal-title fw-bold">Detail Profil: {{ $student->full_name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4 pt-0">
                                        <div class="row g-3">
                                            <div class="col-12"><div class="section-title mt-3">I. DATA PRIBADI</div></div>
                                            <div class="col-md-6 mt-3"><p class="info-label">Email Akun</p><p class="info-value text-primary">{{ $student->email }}</p></div>
                                            <div class="col-md-6 mt-3"><p class="info-label">NISN</p><p class="info-value">{{ $student->nisn ?? 'BELUM DIISI' }}</p></div>
                                            <div class="col-md-4"><p class="info-label">Jenis Kelamin</p><p class="info-value">{{ $student->gender == 'L' ? 'Laki-laki' : ($student->gender == 'P' ? 'Perempuan' : '-') }}</p></div>
                                            <div class="col-md-4"><p class="info-label">WhatsApp</p><p class="info-value">{{ $student->phone_number ?? '-' }}</p></div>
                                            <div class="col-md-4"><p class="info-label">Status</p><p><span class="badge bg-{{ $color }}">{{ $status }}</span></p></div>
                                            <div class="col-md-12"><p class="info-label">TTL</p><p class="info-value">{{ $student->birthCity->name ?? '-' }}, {{ $student->dob ? $student->dob->format('d F Y') : '-' }}</p></div>
                                            <div class="col-md-12"><p class="info-label">Alamat Domisili</p><p class="info-value border-bottom pb-2">{{ $student->address ?? 'BELUM DIISI' }}</p></div>

                                            <div class="col-12"><div class="section-title">II. DATA ORANG TUA</div></div>
                                            @if($student->parentDetail)
                                                <div class="col-md-6"><p class="info-label">Nama Ortu</p><p class="info-value">{{ $student->parentDetail->parent_name }}</p></div>
                                                <div class="col-md-6"><p class="info-label">Hubungan</p><p class="info-value">{{ $student->parentDetail->relationship }}</p></div>
                                                <div class="col-md-6"><p class="info-label">WhatsApp Ortu</p><p class="info-value">{{ $student->parentDetail->parent_phone }}</p></div>
                                            @else
                                                <div class="col-12 py-2 text-danger small">Data orang tua belum dilengkapi.</div>
                                            @endif

                                            <div class="col-12"><div class="section-title">III. DATA SEKOLAH</div></div>
                                            @if($student->schoolDetail)
                                                <div class="col-md-8"><p class="info-label">Sekolah Asal</p><p class="info-value">{{ $student->schoolDetail->school_name }}</p></div>
                                                <div class="col-md-4"><p class="info-label">Lulus</p><p class="info-value">{{ $student->schoolDetail->graduation_year }}</p></div>
                                                <div class="col-md-12"><p class="info-label">Alamat Sekolah</p><p class="info-value">{{ $student->schoolDetail->city->name ?? '-' }} - {{ $student->schoolDetail->school_address ?? '-' }}</p></div>
                                            @else
                                                <div class="col-12 py-2 text-danger small">Data sekolah belum dilengkapi.</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light border-0">
                                        <button type="button" class="btn btn-secondary px-4 shadow-sm" data-bs-dismiss="modal">Tutup</button>
                                        @if($status == 'DAFTAR')
                                            <form action="{{ route('admin.verify', [$student->id, 2]) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm" {{ !$isComplete ? 'disabled' : '' }}>
                                                    {{ $isComplete ? 'Verifikasi & Terima' : 'Data Belum Lengkap' }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>