<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PPDB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            background-color: #f1f5f9;
            font-family: 'Inter', sans-serif;
            color: #334155;
        }

        .navbar {
            background-color: #0f172a;
            padding: 0.8rem 0;
            border-bottom: 4px solid #2563eb;
        }

        .navbar-brand img {
            height: 35px;
            margin-right: 12px;
        }

        .table-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            background: white;
        }

        .table thead th {
            background-color: #f8fafc;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            padding: 1rem;
        }

        .score-badge {
            background-color: #f1f5f9;
            color: #0f172a;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 700;
        }

        .badge-status {
            font-size: 0.75rem;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
        }

        /* --- CSS MODAL: SEMUA RATA KIRI --- */
        .section-tag {
            background: #f1f5f9;
            padding: 8px 12px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.8rem;
            color: #2563eb;
            margin: 24px 0 12px 0;
            text-transform: uppercase;
        }

        .detail-item {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #f8fafc;
        }

        .detail-label {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 500;
            width: 35%;
            /* Memberi ruang tetap untuk label di kiri */
            flex-shrink: 0;
        }

        .detail-value {
            color: #0f172a;
            font-size: 0.9rem;
            font-weight: 600;
            width: 65%;
            text-align: left;
            /* Paksa rata kiri */
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-dark mb-5 shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="navbar-brand fw-bold d-flex align-items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
                <span>ADMIN PPDB</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm fw-bold px-3">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="mb-4 d-flex justify-content-between align-items-end">
            <div>
                <h2 class="fw-bold">Manajemen Pendaftaran</h2>
                <p class="text-muted">Kelola data pendaftar dan verifikasi status siswa.</p>
            </div>
            <div class="mb-2">
                <span class="badge bg-dark px-3 py-2">Total: {{ $students->count() }} Siswa</span>
            </div>
        </div>

        <div class="card filter-card mb-4">
            <div class="card-body p-4">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted text-uppercase">Cari Siswa</label>
                        <input type="text" name="search" class="form-control shadow-none" placeholder="Nama / NISN..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Status</label>
                        <select name="status" class="form-select shadow-none" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="DAFTAR" {{ request('status') == 'DAFTAR' ? 'selected' : '' }}>DAFTAR</option>
                            <option value="TERVERIFIKASI" {{ request('status') == 'TERVERIFIKASI' ? 'selected' : '' }}>TERVERIFIKASI</option>
                            <option value="DITOLAK" {{ request('status') == 'DITOLAK' ? 'selected' : '' }}>DITOLAK</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary fw-bold w-100">Filter</button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-light border fw-bold w-100">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4"><i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}</div>
        @endif

        <div class="card table-card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3">SISWA</th>
                                <th>NISN</th>
                                <th>ASAL SEKOLAH</th>
                                <th class="text-center">NILAI</th>
                                <th>STATUS</th>
                                <th class="text-center px-4">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            @php
                            $currentStatus = $student->status->value ?? $student->status;
                            $isComplete = !empty($student->nisn) && !empty($student->address) && !empty($student->phone_number) && $student->parentDetail && $student->schoolDetail;
                            $color = $currentStatus == 'TERVERIFIKASI' ? 'success' : ($currentStatus == 'DITOLAK' ? 'danger' : 'warning');
                            @endphp
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-bold text-dark">{{ $student->full_name }}</div>
                                    <div class="text-primary small fw-medium" style="font-size: 0.75rem;">{{ $student->email }}</div>
                                </td>
                                <td class="fw-medium">{{ $student->nisn ?? '-' }}</td>
                                <td class="small">{{ $student->schoolDetail->school_name ?? '-' }}</td>
                                <td class="text-center">
                                    @if($student->schoolDetail && $student->schoolDetail->average_score)
                                    <span class="score-badge">{{ number_format($student->schoolDetail->average_score, 2) }}</span>
                                    @else
                                    <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td><span class="badge badge-status bg-{{ $color }} text-uppercase">{{ $currentStatus }}</span></td>
                                <td class="text-center px-4">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <button class="btn btn-sm btn-white border shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#modal{{ $student->id }}" style="height: 34px;">
                                            <i class="bi bi-eye text-primary me-1"></i> Detail
                                        </button>

                                        @if($currentStatus == 'DAFTAR' && $isComplete)
                                        <form action="{{ route('admin.verify', [$student->id, 'DITOLAK']) }}" method="POST" class="d-inline-block m-0">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger fw-bold px-3 shadow-sm" style="height: 34px;" onclick="return confirm('Tolak pendaftaran ini?')">Tolak</button>
                                        </form>
                                        <form action="{{ route('admin.verify', [$student->id, 'TERVERIFIKASI']) }}" method="POST" class="d-inline-block m-0">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success fw-bold px-3 shadow-sm" style="height: 34px;" onclick="return confirm('Verifikasi siswa ini?')">Verifikasi</button>
                                        </form>
                                        @elseif($currentStatus != 'DAFTAR')
                                        <form action="{{ route('admin.verify', [$student->id, 'DAFTAR']) }}" method="POST" class="d-inline-block m-0">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-secondary fw-bold px-3 shadow-sm" style="height: 34px;" onclick="return confirm('Kembalikan status ke Daftar?')">
                                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            {{-- MODAL DETAIL: 100% DATA UTUH --}}
                            <div class="modal fade" id="modal{{ $student->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header border-0 bg-white">
                                            <h4 class="modal-title fw-bold">Detail Profil Pendaftar</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body px-4 pb-4">

                                            <div class="section-tag">I. Data Pribadi & Akademik</div>
                                            <div class="detail-item">
                                                <div class="detail-label">Nama Lengkap</div>
                                                <div class="detail-value">{{ $student->full_name }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Email Akun</div>
                                                <div class="detail-value">{{ $student->email }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">NISN</div>
                                                <div class="detail-value">{{ $student->nisn ?? '-' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Rata-rata Nilai</div>
                                                <div class="detail-value fw-bold text-dark">{{ number_format($student->average_score ?? 0, 2) }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Jenis Kelamin</div>
                                                <div class="detail-value">{{ $student->gender == 'L' ? 'Laki-laki' : ($student->gender == 'P' ? 'Perempuan' : '-') }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Tempat, Tgl Lahir</div>
                                                <div class="detail-value">
                                                    {{ $student->birthCity->name ?? '-' }},
                                                    {{ $student->dob ? $student->dob->format('d F Y') : '-' }}
                                                </div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">No. WhatsApp</div>
                                                <div class="detail-value">{{ $student->phone_number ?? '-' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Alamat Rumah</div>
                                                <div class="detail-value small">{{ $student->address ?? '-' }}</div>
                                            </div>

                                            <div class="section-tag">II. Data Orang Tua / Wali</div>
                                            @if($student->parentDetail)
                                            <div class="detail-item">
                                                <div class="detail-label">Nama Orang Tua</div>
                                                <div class="detail-value">{{ $student->parentDetail->parent_name }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Hubungan</div>
                                                <div class="detail-value">{{ $student->parentDetail->relationship }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">WhatsApp Ortu</div>
                                                <div class="detail-value">{{ $student->parentDetail->parent_phone }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Email Orang Tua</div>
                                                <div class="detail-value">{{ $student->parentDetail->parent_email ?? '-' }}</div>
                                            </div>
                                            @else
                                            <div class="p-3 bg-light rounded text-muted small fw-bold">Data orang tua belum lengkap.</div>
                                            @endif

                                            <div class="section-tag">III. Informasi Sekolah Asal</div>
                                            @if($student->schoolDetail)
                                            <div class="detail-item">
                                                <div class="detail-label">Nama Sekolah</div>
                                                <div class="detail-value">{{ $student->schoolDetail->school_name }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Kota Sekolah</div>
                                                <div class="detail-value">{{ $student->schoolDetail->city->name ?? '-' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Tahun Lulus</div>
                                                <div class="detail-value">{{ $student->schoolDetail->graduation_year }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Alamat Sekolah</div>
                                                <div class="detail-value small">{{ $student->schoolDetail->school_address ?? '-' }}</div>
                                            </div>
                                            @else
                                            <div class="p-3 bg-light rounded text-muted small fw-bold">Data sekolah belum lengkap.</div>
                                            @endif
                                        </div>

                                        <div class="modal-footer border-0 p-4 pt-0 d-flex justify-content-between">
                                            <button type="button" class="btn btn-light fw-bold px-4 shadow-sm" data-bs-dismiss="modal">Tutup</button>
                                            <div class="d-flex gap-2">
                                                @if($currentStatus == 'DAFTAR')
                                                <form action="{{ route('admin.verify', [$student->id, 'DITOLAK']) }}" method="POST" class="m-0">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-danger fw-bold px-4 shadow-sm">Tolak</button>
                                                </form>
                                                <form action="{{ route('admin.verify', [$student->id, 'TERVERIFIKASI']) }}" method="POST" class="m-0">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-success px-5 fw-bold shadow-sm" {{ !$isComplete ? 'disabled' : '' }}>
                                                        Verifikasi
                                                    </button>
                                                </form>
                                                @else
                                                <form action="{{ route('admin.verify', [$student->id, 'DAFTAR']) }}" method="POST" class="m-0">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-secondary fw-bold px-4 shadow-sm">Reset Status</button>
                                                </form>
                                                @endif
                                            </div>
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