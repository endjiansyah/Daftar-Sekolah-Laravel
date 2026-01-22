<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran Siswa Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        body { background-color: #f8f9fa; }
        .section-card { background: white; border-radius: 12px; padding: 25px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .section-title { font-weight: 700; color: #0d6efd; text-transform: uppercase; border-bottom: 2px solid #e9ecef; padding-bottom: 10px; margin-bottom: 20px; }
        .required-label::after { content: " *"; color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST">
                @csrf

                <div class="section-card text-center">
                    <h3 class="fw-bold mb-4">Pendaftaran Siswa Baru</h3>
                    <div class="d-flex justify-content-center gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="registration_type" id="type_partial" value="partial" {{ old('registration_type', 'partial') == 'partial' ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="type_partial">Pendaftaran Singkat</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="registration_type" id="type_complete" value="complete" {{ old('registration_type') == 'complete' ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="type_complete">Pendaftaran Lengkap</label>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <div class="section-title">A. Data Akun & Identitas Pribadi</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required-label">Username</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required-label">Nama Lengkap</label>
                            <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label js-dynamic-star">No. WhatsApp Siswa</label>
                            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}" placeholder="08xxxx">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label js-dynamic-star">NISN (10 Digit)</label>
                            <input type="text" name="nisn" class="form-control" value="{{ old('nisn') }}" maxlength="10">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label js-dynamic-star">Jenis Kelamin</label>
                            <select name="gender" class="form-select">
                                <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label js-dynamic-star">Tempat Lahir</label>
                            <select name="pob" class="form-select select2-city">
                                <option value="">-- Pilih Kota Lahir --</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('pob') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label js-dynamic-star">Tanggal Lahir</label>
                            <input type="date" name="dob" class="form-control" value="{{ old('dob') }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label js-dynamic-star">Alamat Tinggal Siswa</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <div id="complete_fields" style="display: none;">
                    <div class="section-card">
                        <div class="section-title">B. Data Orang Tua / Wali</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required-label">Nama Orang Tua</label>
                                <input type="text" name="parent_name" class="form-control" value="{{ old('parent_name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required-label">Hubungan</label>
                                <select name="relationship" class="form-select">
                                    <option value="Ayah" {{ old('relationship') == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                                    <option value="Ibu" {{ old('relationship') == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                                    <option value="Wali" {{ old('relationship') == 'Wali' ? 'selected' : '' }}>Wali</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required-label">No. WA Orang Tua</label>
                                <input type="text" name="parent_phone" class="form-control" value="{{ old('parent_phone') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Orang Tua</label>
                                <input type="email" name="parent_email" class="form-control" value="{{ old('parent_email') }}">
                            </div>
                        </div>
                    </div>

                    <div class="section-card">
                        <div class="section-title">C. Data Sekolah Asal</div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label required-label">Nama Sekolah (SMP/MTS)</label>
                                <input type="text" name="school_name" class="form-control" value="{{ old('school_name') }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label required-label">Alamat Lengkap Sekolah</label>
                                <textarea name="school_address" class="form-control" rows="2">{{ old('school_address') }}</textarea>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label required-label">Kota Sekolah</label>
                                <select name="city_id" class="form-select select2-city">
                                    <option value="">-- Pilih Kota --</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required-label">Tahun Lulus</label>
                                <input type="number" name="graduation_year" class="form-control" value="{{ old('graduation_year', 2025) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mb-5">Daftar Sekarang</button>
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
            placeholder: '-- Pilih --'
        });

        function toggleRegistrationView() {
            if ($('#type_complete').is(':checked')) {
                $('#complete_fields').slideDown();
                $('.js-dynamic-star').addClass('required-label');
                // Tambahkan attribute required secara dinamis jika diperlukan
            } else {
                $('#complete_fields').slideUp();
                $('.js-dynamic-star').removeClass('required-label');
            }
        }

        $('input[name="registration_type"]').on('change', toggleRegistrationView);
        
        // Jalankan saat halaman pertama kali dimuat
        toggleRegistrationView();
    });
</script>
</body>
</html>