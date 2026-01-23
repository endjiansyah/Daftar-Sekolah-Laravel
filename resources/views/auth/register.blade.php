<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Siswa Baru - PPDB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        :root {
            --royal-blue: #002366;
            --gold-theme: #ffc107;
        }

        body {
            background-color: #f4f7f9;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        /* Header Form dengan Tema Royal Blue */
        .register-header {
            background-color: var(--royal-blue);
            color: white;
            padding: 40px 20px;
            border-radius: 15px 15px 0 0;
            border-bottom: 5px solid var(--gold-theme);
            text-align: center;
        }

        .logo-reg {
            height: 80px;
            margin-bottom: 15px;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.2));
        }

        .section-card {
            background: white;
            border-radius: 0 0 15px 15px;
            /* Menempel dengan header jika kartu pertama */
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: none;
        }

        /* Untuk kartu identitas setelah kartu pertama */
        .section-card.sub-card {
            border-radius: 15px;
        }

        .section-title {
            font-weight: 700;
            color: var(--royal-blue);
            text-transform: uppercase;
            border-bottom: 2px solid #f1f1f1;
            padding-bottom: 12px;
            margin-bottom: 25px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
        }

        .section-title::before {
            content: "";
            display: inline-block;
            width: 5px;
            height: 20px;
            background: var(--gold-theme);
            margin-right: 10px;
            border-radius: 2px;
        }

        .required-label::after {
            content: " *";
            color: #dc3545;
            font-weight: bold;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #444;
            text-uppercase: uppercase;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #dce0e4;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--gold-theme);
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.15);
        }

        .btn-theme {
            background-color: var(--gold-theme);
            color: #000;
            font-weight: 700;
            padding: 15px;
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-theme:hover {
            background-color: #eab000;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
        }

        .form-check-input:checked {
            background-color: var(--royal-blue);
            border-color: var(--royal-blue);
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ url('/') }}" class="text-decoration-none fw-bold" style="color: var(--royal-blue);">
                        ← Kembali ke Beranda
                    </a>
                    <span class="text-muted small">Sudah memiliki akun?
                        <a href="{{ route('login') }}" class="fw-bold text-decoration-none ms-1" style="color: var(--royal-blue);">Login Di Sini</a>
                    </span>
                </div>

                @if($errors->any())
                <div class="alert alert-danger shadow-sm border-0">
                    <h6 class="fw-bold">Mohon perbaiki kesalahan berikut:</h6>
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('register.store') }}" method="POST">
                    @csrf

                    <div class="register-header">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-reg">
                        <h3 class="fw-bold mb-1">Formulir Pendaftaran Siswa</h3>
                        <p class="text-white-50 small mb-0">Tahun Ajaran 2026/2027</p>
                    </div>

                    <div class="section-card shadow-sm mb-4">
                        <div class="d-flex justify-content-center gap-5 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="registration_type" id="type_partial" value="partial" {{ old('registration_type', 'partial') == 'partial' ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-dark" for="type_partial">Pendaftaran Singkat</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="registration_type" id="type_complete" value="complete" {{ old('registration_type') == 'complete' ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-dark" for="type_complete">Pendaftaran Lengkap</label>
                            </div>
                        </div>
                    </div>

                    <div class="section-card sub-card shadow-sm">
                        <div class="section-title">A. Data Akun & Identitas Pribadi</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required-label">Username</label>
                                <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="Contoh: budi2026" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required-label">Nama Lengkap</label>
                                <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" placeholder="Nama sesuai Ijazah" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required-label">Email Aktif</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="email@contoh.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Min. 8 Karakter" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label js-dynamic-star">Tempat Lahir</label>
                                <select name="pob" class="form-select select2-city">
                                    <option value="">-- Pilih Kota --</option>
                                    @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('pob') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label js-dynamic-star">Tanggal Lahir</label>
                                <input type="date" name="dob" class="form-control" value="{{ old('dob') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label js-dynamic-star">Jenis Kelamin</label>
                                <select name="gender" class="form-select">
                                    <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label js-dynamic-star">NISN (10 Digit)</label>
                                <input type="text" name="nisn" class="form-control" value="{{ old('nisn') }}" maxlength="10" placeholder="00xxxxxxxx">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label js-dynamic-star">Rata-rata Nilai Ijazah</label>
                                <input type="number" name="average_score" class="form-control" value="{{ old('average_score') }}" step="0.01" min="0" max="100" placeholder="0.00">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label js-dynamic-star">No. WhatsApp Siswa</label>
                                <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}" placeholder="08xxxxxx">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label js-dynamic-star">Alamat Tinggal Siswa</label>
                                <textarea name="address" class="form-control" rows="2" placeholder="Jl. Nama Jalan No. RT/RW, Kec, Kota...">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div id="complete_fields" style="display: none;">
                        <div class="section-card sub-card shadow-sm">
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

                        <div class="section-card sub-card shadow-sm">
                            <div class="section-title">C. Data Sekolah Asal</div>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label required-label">Nama Sekolah (SMP/MTS)</label>
                                    <input type="text" name="school_name" class="form-control" value="{{ old('school_name') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required-label">Tahun Lulus</label>
                                    <input type="number" name="graduation_year" class="form-control" value="{{ old('graduation_year', 2025) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required-label">Kota Sekolah</label>
                                    <select name="city_id" class="form-select select2-city">
                                        <option value="">-- Pilih Kota --</option>
                                        @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required-label">Alamat Lengkap Sekolah</label>
                                    <input type="text" name="school_address" class="form-control" value="{{ old('school_address') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-theme w-100 mb-5 shadow">KIRIM PENDAFTARAN SEKARANG</button>

                    <div class="text-center mb-5">
                        <hr class="w-25 mx-auto">
                        <p class="text-muted small">&copy; 2026 <strong>EndProjects</strong>. Seluruh Hak Cipta Dilindungi.</p>
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

            function toggleRegistrationView() {
                if ($('#type_complete').is(':checked')) {
                    $('#complete_fields').slideDown();
                    $('.js-dynamic-star').addClass('required-label');
                    $('input[name="nisn"], input[name="average_score"], select[name="pob"], input[name="dob"]').prop('required', true);
                } else {
                    $('#complete_fields').slideUp();
                    $('.js-dynamic-star').removeClass('required-label');
                    $('input[name="nisn"], input[name="average_score"], select[name="pob"], input[name="dob"]').prop('required', false);
                }
            }

            $('input[name="registration_type"]').on('change', toggleRegistrationView);
            toggleRegistrationView();

            $('input[name="nisn"]').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    </script>
</body>

</html>