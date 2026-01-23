<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Profil - PPDB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }

        .section-title {
            color: #333;
            font-weight: bold;
            border-left: 4px solid #0d6efd;
            padding-left: 10px;
            margin-bottom: 20px;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #555;
        }

        .required-info {
            font-size: 0.85rem;
            color: #dc3545;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold mb-1">Lengkapi Profil Pendaftaran</h3>
                        <p class="required-info">* Tanda bintang merah wajib diisi</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4 shadow-sm">Kembali</a>
                </div>

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- A. Identitas Pribadi --}}
                    <div class="card p-4">
                        <h5 class="section-title">A. Identitas Pribadi Siswa</h5>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $user->full_name) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                <select name="pob" class="form-select select2-city">
                                    @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('pob', $user->pob) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="dob" class="form-control" value="{{ old('dob', ($user->dob ? $user->dob->format('Y-m-d') : '')) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="gender" class="form-select">
                                    <option value="L" {{ old('gender', $user->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender', $user->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">NISN (10 Digit) <span class="text-danger">*</span></label>
                                <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $user->nisn) }}" maxlength="10">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Rata-rata Nilai Ijazah <span class="text-danger">*</span></label>
                                <input type="number" name="average_score" class="form-control" value="{{ old('average_score', $user->average_score) }}" step="0.01" min="0" max="100">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">No. WhatsApp Aktif <span class="text-danger">*</span></label>
                                <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Alamat Lengkap Rumah <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- B. Data Orang Tua --}}
                    <div class="card p-4">
                        <h5 class="section-title">B. Data Orang Tua / Wali</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Orang Tua / Wali <span class="text-danger">*</span></label>
                                <input type="text" name="parent_name" class="form-control" value="{{ old('parent_name', $user->parentDetail->parent_name ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Orang Tua</label>
                                <input type="email" name="parent_email" class="form-control" value="{{ old('parent_email', $user->parentDetail->parent_email ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hubungan / Status <span class="text-danger">*</span></label>
                                <select name="relationship" class="form-select">
                                    <option value="Ayah" {{ (old('relationship', $user->parentDetail->relationship ?? '') == 'Ayah') ? 'selected' : '' }}>Ayah Kandung</option>
                                    <option value="Ibu" {{ (old('relationship', $user->parentDetail->relationship ?? '') == 'Ibu') ? 'selected' : '' }}>Ibu Kandung</option>
                                    <option value="Wali" {{ (old('relationship', $user->parentDetail->relationship ?? '') == 'Wali') ? 'selected' : '' }}>Wali</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. WA Orang Tua <span class="text-danger">*</span></label>
                                <input type="text" name="parent_phone" class="form-control" value="{{ old('parent_phone', $user->parentDetail->parent_phone ?? '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- C. Informasi Sekolah --}}
                    <div class="card p-4">
                        <h5 class="section-title">C. Informasi Sekolah Asal</h5>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Nama Sekolah (SMP/MTs) <span class="text-danger">*</span></label>
                                <input type="text" name="school_name" class="form-control" value="{{ old('school_name', $user->schoolDetail->school_name ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tahun Lulus <span class="text-danger">*</span></label>
                                <input type="number" name="graduation_year" class="form-control" value="{{ old('graduation_year', $user->schoolDetail->graduation_year ?? 2025) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kota Sekolah <span class="text-danger">*</span></label>
                                <select name="city_id" class="form-select select2-city">
                                    @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ (old('city_id', $user->schoolDetail->city_id ?? '') == $city->id) ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Alamat Sekolah Asal</label>
                                <textarea name="school_address" class="form-control" rows="2">{{ old('school_address', $user->schoolDetail->school_address ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4 mb-5">
                        <button type="submit" class="btn btn-primary btn-lg shadow px-5">Simpan Perubahan Data</button>
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
        });
    </script>
</body>

</html>