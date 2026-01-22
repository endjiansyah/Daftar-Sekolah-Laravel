<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Siswa Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Formulir Pendaftaran SMA</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('register.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="form-label d-block fw-bold">Tipe Pendaftaran</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="registration_type" id="type_partial" value="partial" checked>
                                    <label class="form-check-label" for="type_partial">Parsial (Hanya Data Pribadi)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="registration_type" id="type_complete" value="complete">
                                    <label class="form-check-label" for="type_complete">Lengkap (Semua Data)</label>
                                </div>
                            </div>

                            <hr>

                            <h5 class="text-primary">1. Data Pribadi</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}">
                                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            </div>

                            <div id="additional_fields" style="display: none;">
                                <hr>
                                <h5 class="text-primary">2. Data Orang Tua</h5>
                                <div class="mb-3">
                                    <label class="form-label">Nama Orang Tua/Wali</label>
                                    <input type="text" name="parent_name" class="form-control">
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Hubungan</label>
                                        <select name="relationship" class="form-select">
                                            <option value="Ayah">Ayah</option>
                                            <option value="Ibu">Ibu</option>
                                            <option value="Wali">Wali</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">No. HP Orang Tua</label>
                                        <input type="text" name="parent_phone" class="form-control">
                                    </div>
                                </div>

                                <hr>
                                <h5 class="text-primary">3. Data Sekolah Asal</h5>
                                <div class="mb-3">
                                    <label class="form-label">Nama SMP</label>
                                    <input type="text" name="school_name" class="form-control">
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Kota</label>
                                        <input type="text" name="city" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tahun Lulus</label>
                                        <input type="number" name="graduation_year" class="form-control" value="2025">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100 p-2">Daftar Sekarang</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Logika untuk menampilkan/menyembunyikan input tambahan
        const typePartial = document.getElementById('type_partial');
        const typeComplete = document.getElementById('type_complete');
        const additionalFields = document.getElementById('additional_fields');

        function toggleFields() {
            additionalFields.style.display = typeComplete.checked ? 'block' : 'none';
        }

        typePartial.addEventListener('change', toggleFields);
        typeComplete.addEventListener('change', toggleFields);
    </script>
</body>
</html>