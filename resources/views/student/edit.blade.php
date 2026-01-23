<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Profil - PPDB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
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
            margin-bottom: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
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

        .section-title-custom {
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--royal-blue);
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--soft-blue);
            padding-bottom: 10px;
        }

        .section-title-custom i {
            margin-right: 10px;
            color: var(--gold-theme);
            font-size: 1.2rem;
        }

        .form-label {
            color: #888;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .form-control,
        .form-select {
            border: 1.5px solid #eee;
            border-radius: 10px;
            padding: 10px 15px;
            font-weight: 600;
            color: var(--royal-blue);
        }

        .form-control:focus {
            border-color: var(--royal-blue);
            box-shadow: none;
        }

        .btn-save {
            background-color: var(--gold-theme);
            color: #000;
            font-weight: 800;
            border-radius: 12px;
            padding: 18px;
            transition: 0.3s;
            border: none;
            width: 100%;
            font-size: 1.1rem;
        }

        .btn-save:hover {
            background-color: #eab000;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 193, 7, 0.4);
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row g-4">

            {{-- Kolom Kiri: Sidebar --}}
            <div class="col-lg-4">
                <div class="card">
                    <div class="profile-header"></div>
                    <div class="card-body text-center pt-0">
                        <div class="avatar-container mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->full_name) }}&background=002366&color=fff&size=128"
                                class="rounded-circle" alt="Profile" width="100">
                        </div>
                        <h5 class="fw-bold mb-1" style="color: var(--royal-blue);">{{ Auth::user()->full_name }}</h5>
                        <p class="text-muted small mb-3">{{ Auth::user()->email }}</p>

                        <div class="d-grid gap-2">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm fw-bold shadow-sm">
                                <i class="bi bi-arrow-left me-1"></i> Dashboard Utama
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-link text-danger btn-sm text-decoration-none fw-bold w-100 mt-1">
                                    <i class="bi bi-box-arrow-right me-1"></i> Keluar Aplikasi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Form Berkelompok --}}
            <div class="col-lg-8">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card">
                        <div class="card-body p-4">
                            <div class="section-title-custom">
                                <i class="bi bi-person-vcard-fill"></i> A. Biodata & Nilai Siswa
                            </div>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Nama Lengkap (Sesuai Ijazah)</label>
                                    <input type="text" name="full_name" class="form-control" value="{{ old('full_name', Auth::user()->full_name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">NISN</label>
                                    <input type="text" name="nisn" class="form-control" value="{{ old('nisn', Auth::user()->nisn) }}" maxlength="10" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Rata-rata Nilai Ijazah</label>
                                    <input type="number" step="0.01" name="average_score" class="form-control" value="{{ old('average_score', Auth::user()->average_score ?? '') }}" style="background-color: #fffdf5; border-color: var(--gold-theme);" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="gender" class="form-select" required>
                                        <option value="L" {{ old('gender', Auth::user()->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('gender', Auth::user()->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Kota Kelahiran</label>
                                    <select name="pob" class="form-select select2-city" required>
                                        @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('pob', Auth::user()->pob) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="dob" class="form-control" value="{{ old('dob', Auth::user()->dob ? Auth::user()->dob->format('Y-m-d') : '') }}" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Nomor HP Siswa</label>
                                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', Auth::user()->phone_number) }}" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Alamat Rumah Lengkap</label>
                                    <textarea name="address" class="form-control" rows="2" required>{{ old('address', Auth::user()->address) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-4">
                            <div class="section-title-custom">
                                <i class="bi bi-people-fill"></i> B. Data Orang Tua / Wali
                            </div>
                            <div class="row g-3">
                                <div class="col-md-7">
                                    <label class="form-label">Nama Orang Tua</label>
                                    <input type="text" name="parent_name" class="form-control" value="{{ old('parent_name', Auth::user()->parentDetail->parent_name ?? '') }}" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Hubungan</label>
                                    <select name="relationship" class="form-select" required>
                                        <option value="Ayah" {{ (old('relationship', Auth::user()->parentDetail->relationship ?? '') == 'Ayah') ? 'selected' : '' }}>Ayah</option>
                                        <option value="Ibu" {{ (old('relationship', Auth::user()->parentDetail->relationship ?? '') == 'Ibu') ? 'selected' : '' }}>Ibu</option>
                                        <option value="Wali" {{ (old('relationship', Auth::user()->parentDetail->relationship ?? '') == 'Wali') ? 'selected' : '' }}>Wali</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nomor HP Orang Tua</label>
                                    <input type="text" name="parent_phone" class="form-control" value="{{ old('parent_phone', Auth::user()->parentDetail->parent_phone ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Orang Tua</label>
                                    <input type="email" name="parent_email" class="form-control" value="{{ old('parent_email', Auth::user()->parentDetail->parent_email ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-4">
                            <div class="section-title-custom">
                                <i class="bi bi-mortarboard-fill"></i> C. Data Sekolah Asal
                            </div>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label">Nama Sekolah (SMP/MTs)</label>
                                    <input type="text" name="school_name" class="form-control" value="{{ old('school_name', Auth::user()->schoolDetail->school_name ?? '') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tahun Lulus</label>
                                    <input type="number" name="graduation_year" class="form-control" value="{{ old('graduation_year', Auth::user()->schoolDetail->graduation_year ?? 2025) }}" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Kota Sekolah</label>
                                    <select name="city_id" class="form-select select2-city" required>
                                        @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ (old('city_id', Auth::user()->schoolDetail->city_id ?? '') == $city->id) ? 'selected' : '' }}>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Alamat Lengkap Sekolah</label>
                                    <input type="text" name="school_address" class="form-control" value="{{ old('school_address', Auth::user()->schoolDetail->school_address ?? '') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <button type="submit" class="btn btn-save shadow">
                            <i class="bi bi-cloud-check-fill me-2"></i> SIMPAN SEMUA DATA PENDAFTARAN
                        </button>
                        <p class="text-center text-muted small mt-3">
                            <i class="bi bi-lock-fill me-1"></i> Data Anda akan tersimpan dengan aman di sistem kami.
                        </p>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2-city').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
            $('input[name="nisn"]').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    </script>
</body>

</html>