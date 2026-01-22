<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Data Siswa - PPDB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <span class="navbar-brand fw-bold text-warning">PENGATURAN DATA</span>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm fw-bold">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold mb-0 text-primary">Formulir Pendaftaran</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            
                            <h6 class="fw-bold mb-3"><i class="bi bi-1-circle-fill me-2"></i>IDENTITAS DASAR</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="small fw-bold">NISN</label>
                                    <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $user->nisn) }}" placeholder="10 Digit NISN">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Jenis Kelamin</label>
                                    <select name="gender" class="form-select">
                                        <option value="" disabled selected>Pilih...</option>
                                        <option value="L" {{ $user->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ $user->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="small fw-bold">Alamat Lengkap</label>
                                    <textarea name="address" class="form-control" rows="2">{{ old('address', $user->address) }}</textarea>
                                </div>
                            </div>

                            <hr>

                            <h6 class="fw-bold mb-3 mt-4"><i class="bi bi-2-circle-fill me-2"></i>DATA ORANG TUA / WALI</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="small fw-bold">Nama Lengkap Wali</label>
                                    <input type="text" name="parent_name" class="form-control" value="{{ old('parent_name', $user->parentDetail->parent_name ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Hubungan</label>
                                    <select name="relationship" class="form-select">
                                        <option value="Ayah" {{ ($user->parentDetail->relationship ?? '') == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                                        <option value="Ibu" {{ ($user->parentDetail->relationship ?? '') == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                                        <option value="Wali" {{ ($user->parentDetail->relationship ?? '') == 'Wali' ? 'selected' : '' }}>Wali</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="small fw-bold">Nomor WhatsApp Aktif</label>
                                    <input type="text" name="parent_phone" class="form-control" value="{{ old('parent_phone', $user->parentDetail->parent_phone ?? '') }}" required>
                                </div>
                            </div>

                            <hr>

                            <h6 class="fw-bold mb-3 mt-4"><i class="bi bi-3-circle-fill me-2"></i>SEKOLAH ASAL</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-12">
                                    <label class="small fw-bold">Nama SMP / MTs</label>
                                    <input type="text" name="school_name" class="form-control" value="{{ old('school_name', $user->schoolDetail->school_name ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Kota/Kabupaten Sekolah</label>
                                    <input type="text" name="city" class="form-control" value="{{ old('city', $user->schoolDetail->city ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Tahun Lulus</label>
                                    <input type="number" name="graduation_year" class="form-control" value="{{ old('graduation_year', $user->schoolDetail->graduation_year ?? '2025') }}" required>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                                    <i class="bi bi-check2-circle me-1"></i> SIMPAN DAN PERBARUI DATA
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>