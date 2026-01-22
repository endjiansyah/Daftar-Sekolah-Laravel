<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary mb-4 shadow-sm">
        <div class="container">
            <span class="navbar-brand fw-bold">PPDB Online</span>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-light btn-sm">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            {{-- KIRI: PROFIL RINGKAS --}}
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm border-0 text-center p-3">
                    <div class="mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->full_name) }}&background=0D6EFD&color=fff" class="rounded-circle" width="100">
                    </div>
                    <h5 class="fw-bold">{{ $user->full_name }}</h5>
                    <p class="text-muted small">{{ $user->email }}</p>
                    <hr>
                    <div class="d-flex justify-content-between px-3">
                        <span class="small">Status:</span>
                        <span class="badge bg-{{ $user->status->value == 'DAFTAR' ? 'warning' : ($user->status->value == 'DITOLAK' ? 'danger' : 'success') }}">
                            {{ $user->status->value }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- KANAN: KONTEN UTAMA --}}
            <div class="col-md-8">
                @if(session('success'))
                    <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
                @endif

                {{-- KONDISI 1: JIKA DATA BELUM LENGKAP --}}
                @if(!$user->parentDetail || !$user->schoolDetail)
                    <div class="card border-danger mb-4 shadow-sm">
                        <div class="card-header bg-danger text-white fw-bold border-0">Lengkapi Data Pendaftaran</div>
                        <div class="card-body">
                            <p class="text-danger small fw-bold">Mohon periksa kembali identitas Anda dan lengkapi data orang tua serta sekolah asal.</p>
                            
                            <form action="{{ route('profile.complete') }}" method="POST">
                                @csrf
                                
                                {{-- 1. IDENTITAS SISWA (PRE-FILLED) --}}
                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person-fill"></i> 1. Identitas Dasar</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label small">Nama Lengkap</label>
                                        <input type="text" name="full_name" class="form-control bg-light" value="{{ $user->full_name }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">NISN</label>
                                        <input type="text" name="nisn" class="form-control" value="{{ $user->nisn }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Tempat Lahir</label>
                                        <input type="text" name="pob" class="form-control" value="{{ $user->pob }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Tanggal Lahir</label>
                                        <input type="date" name="dob" class="form-control" value="{{ $user->dob }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label small">Alamat Domisili</label>
                                        <textarea name="address" class="form-control" rows="2">{{ $user->address }}</textarea>
                                    </div>
                                </div>

                                <hr>

                                {{-- 2. DATA ORANG TUA --}}
                                <h6 class="fw-bold text-primary mb-3">2. Data Orang Tua</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label small">Nama Orang Tua</label>
                                        <input type="text" name="parent_name" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Hubungan</label>
                                        <select name="relationship" class="form-select">
                                            <option value="Ayah">Ayah</option>
                                            <option value="Ibu">Ibu</option>
                                            <option value="Wali">Wali</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label small">No. HP Orang Tua (WhatsApp)</label>
                                        <input type="text" name="parent_phone" class="form-control" placeholder="Contoh: 0812..." required>
                                    </div>
                                </div>

                                <hr>

                                {{-- 3. DATA SEKOLAH ASAL --}}
                                <h6 class="fw-bold text-primary mb-3">3. Data Sekolah Asal</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-12">
                                        <label class="form-label small">Nama Sekolah (SMP/MTs)</label>
                                        <input type="text" name="school_name" class="form-control" placeholder="Contoh: SMP Negeri 1 Jakarta" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Kota/Kabupaten Sekolah</label>
                                        <input type="text" name="city" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Tahun Lulus</label>
                                        <input type="number" name="graduation_year" class="form-control" value="2025" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-danger w-100 shadow-sm fw-bold py-2">SIMPAN & AJUKAN VERIFIKASI</button>
                            </form>
                        </div>
                    </div>

                {{-- KONDISI 2: JIKA SUDAH LENGKAP --}}
                @else
                    <div class="card border-success shadow-sm border-0">
                        <div class="card-header bg-success text-white fw-bold border-0">Detail Pendaftaran Anda</div>
                        <div class="card-body">
                            <div class="alert alert-info border-0 shadow-sm mb-4">
                                <i class="bi bi-info-circle-fill"></i> Data Anda sudah lengkap dan sedang dalam antrean verifikasi Admin.
                            </div>
                            
                            <h6 class="fw-bold text-success mb-3">Identitas & Orang Tua</h6>
                            <table class="table table-sm mb-4">
                                <tr><th width="35%" class="text-muted">NISN</th><td>{{ $user->nisn }}</td></tr>
                                <tr><th class="text-muted">Orang Tua</th><td>{{ $user->parentDetail->parent_name }} ({{ $user->parentDetail->relationship }})</td></tr>
                                <tr><th class="text-muted">Kontak Ortu</th><td>{{ $user->parentDetail->parent_phone }}</td></tr>
                            </table>

                            <h6 class="fw-bold text-success mb-3">Pendidikan Asal</h6>
                            <table class="table table-sm">
                                <tr><th width="35%" class="text-muted">Sekolah Asal</th><td>{{ $user->schoolDetail->school_name }}</td></tr>
                                <tr><th class="text-muted">Kota</th><td>{{ $user->schoolDetail->city }}</td></tr>
                                <tr><th class="text-muted">Tahun Lulus</th><td>{{ $user->schoolDetail->graduation_year }}</td></tr>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>