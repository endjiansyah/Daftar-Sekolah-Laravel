<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - PPDB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">PPDB ONLINE</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-light btn-sm fw-bold text-primary">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="row">
            {{-- KOLOM KIRI --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center py-4 mb-4">
                    <div class="card-body">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->full_name) }}&background=0D6EFD&color=fff" class="rounded-circle mb-3 shadow-sm" width="80">
                        <h6 class="fw-bold mb-1">{{ $user->full_name }}</h6>
                        <p class="text-muted small mb-3">{{ $user->email }}</p>
                        <div class="badge bg-{{ $user->status->value == 'DAFTAR' ? 'warning text-dark' : 'success' }} px-4 py-2 rounded-pill">
                            STATUS: {{ strtoupper($user->status->value) }}
                        </div>
                    </div>
                </div>
                <a href="{{ route('student.edit') }}" class="btn btn-primary w-100 fw-bold shadow-sm mb-4">
                    <i class="bi bi-pencil-square me-2"></i>LENGKAPI / EDIT DATA
                </a>
            </div>

            {{-- KOLOM KANAN: DATA SESUAI REQUIREMENT --}}
            <div class="col-md-8">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
                @endif

                {{-- A. PERSONAL INFORMATION --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0 text-primary">A. Personal Information</h6></div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <tbody>
                                <tr><td class="bg-light small fw-bold px-4 text-muted" width="35%">Username / Email</td><td class="px-4">{{ $user->username }} / {{ $user->email }}</td></tr>
                                <tr><td class="bg-light small fw-bold px-4 text-muted">Full Name</td><td class="px-4">{{ $user->full_name }}</td></tr>
                                <tr><td class="bg-light small fw-bold px-4 text-muted">Gender</td><td class="px-4">{{ $user->gender == 'L' ? 'Male' : ($user->gender == 'P' ? 'Female' : '-') }}</td></tr>
                                <tr><td class="bg-light small fw-bold px-4 text-muted">Place, Date of Birth</td><td class="px-4">{{ $user->pob ?? '-' }}, {{ $user->dob ?? '-' }}</td></tr>
                                <tr><td class="bg-light small fw-bold px-4 text-muted">Phone Number</td><td class="px-4">{{ $user->phone_number ?? '-' }}</td></tr>
                                <tr><td class="bg-light small fw-bold px-4 text-muted">Home Address</td><td class="px-4">{{ $user->address ?? '-' }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- B. PARENT / GUARDIAN INFORMATION --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0 text-primary">B. Parent / Guardian Information</h6></div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <tbody>
                                <tr><td class="bg-light small fw-bold px-4 text-muted" width="35%">Parent Name</td><td class="px-4">{{ $user->parentDetail->parent_name ?? '-' }}</td></tr>
                                <tr><td class="bg-light small fw-bold px-4 text-muted">Status (Relation)</td><td class="px-4">{{ $user->parentDetail->relationship ?? '-' }}</td></tr>
                                <tr><td class="bg-light small fw-bold px-4 text-muted">Phone Number</td><td class="px-4">{{ $user->parentDetail->parent_phone ?? '-' }}</td></tr>
                                <tr><td class="bg-light small fw-bold px-4 text-muted">Email</td><td class="px-4">{{ $user->parentDetail->parent_email ?? '-' }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- C. PREVIOUS SCHOOL INFORMATION --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0 text-primary">C. Previous School Information</h6></div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <tbody>
                                <tr><td class="bg-light small fw-bold px-4 text-muted" width="35%">School Name</td><td class="px-4">{{ $user->schoolDetail->school_name ?? '-' }}</td></tr>
                                <tr><td class="bg-light small fw-bold px-4 text-muted">School Address</td><td class="px-4">{{ $user->schoolDetail->school_address ?? '-' }}</td></tr>
                                <tr><td class="bg-light small fw-bold px-4 text-muted">City</td><td class="px-4">{{ $user->schoolDetail->city ?? '-' }}</td></tr>
                                <tr><td class="bg-light small fw-bold px-4 text-muted">Graduation Year</td><td class="px-4">{{ $user->schoolDetail->graduation_year ?? '-' }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>