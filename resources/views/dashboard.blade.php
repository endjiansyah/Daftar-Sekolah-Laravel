<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <span class="navbar-brand">PPDB Online</span>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-light btn-sm">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->full_name) }}&background=0D6EFD&color=fff" class="rounded-circle" width="100">
                        </div>
                        <h5>{{ $user->full_name }}</h5>
                        <p class="text-muted small">{{ $user->email }}</p>
                        <hr>
                        <div class="d-flex justify-content-between px-3">
                            <span>Status:</span>
                            <span class="badge bg-{{ $user->status->value == 'DAFTAR' ? 'warning' : ($user->status->value == 'DITOLAK' ? 'danger' : 'success') }}">
                                {{ $user->status->value }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- KONDISI 1: JIKA DATA BELUM LENGKAP --}}
                @if(!$user->parentDetail || !$user->schoolDetail)
                    <div class="card border-danger mb-4">
                        <div class="card-header bg-danger text-white">Lengkapi Data Pendaftaran</div>
                        <div class="card-body">
                            <p>Akun Anda berhasil dibuat, namun Anda belum melengkapi data Orang Tua atau Sekolah. Mohon lengkapi di bawah ini:</p>
                            
                            <form action="{{ route('profile.complete') }}" method="POST">
                                @csrf
                                <h6 class="fw-bold text-primary">Data Orang Tua</h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Orang Tua</label>
                                        <input type="text" name="parent_name" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Hubungan</label>
                                        <select name="relationship" class="form-select">
                                            <option value="Ayah">Ayah</option>
                                            <option value="Ibu">Ibu</option>
                                            <option value="Wali">Wali</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. HP Orang Tua</label>
                                    <input type="text" name="parent_phone" class="form-control" required>
                                </div>

                                <hr>
                                <h6 class="fw-bold text-primary">Data Sekolah Asal</h6>
                                <div class="mb-3">
                                    <label class="form-label">Nama Sekolah (SMP)</label>
                                    <input type="text" name="school_name" class="form-control" required>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Kota</label>
                                        <input type="text" name="city" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tahun Lulus</label>
                                        <input type="number" name="graduation_year" class="form-control" value="2025" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-danger w-100">Simpan & Ajukan Verifikasi</button>
                            </form>
                        </div>
                    </div>

                {{-- KONDISI 2: JIKA SUDAH LENGKAP --}}
                @else
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">Detail Pendaftaran Anda</div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                Data Anda sudah lengkap dan sedang dalam antrean verifikasi Admin.
                            </div>
                            <table class="table table-sm">
                                <tr><th>Orang Tua</th><td>: {{ $user->parentDetail->parent_name }}</td></tr>
                                <tr><th>Sekal Asal</th><td>: {{ $user->schoolDetail->school_name }}</td></tr>
                                <tr><th>Kota</th><td>: {{ $user->schoolDetail->city }}</td></tr>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>