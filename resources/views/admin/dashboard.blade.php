<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PPDB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand">Panel Admin PPDB</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-outline-light btn-sm">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container-fluid px-4">
        <div class="card shadow">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 font-weight-bold text-primary">Daftar Pengajuan Siswa Baru</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Sekolah Asal</th>
                                <th>Data Ortu</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>
                                    <strong>{{ $student->full_name }}</strong><br>
                                    <small class="text-muted">{{ $student->email }}</small>
                                </td>
                                <td>
                                    {{ $student->schoolDetail->school_name ?? '⚠️ Belum Lengkap' }}
                                </td>
                                <td>
                                    {{ $student->parentDetail->parent_name ?? '⚠️ Belum Lengkap' }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $student->status->value == 'DAFTAR' ? 'warning' : ($student->status->value == 'DITOLAK' ? 'danger' : 'success') }}">
                                        {{ $student->status->value }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        {{-- Tombol Verifikasi --}}
                                        <form action="{{ route('admin.verify', [$student->id, 'TERVERIFIKASI']) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-success" {{ $student->status->value == 'TERVERIFIKASI' ? 'disabled' : '' }}>Setuju</button>
                                        </form>
                                        
                                        {{-- Tombol Tolak --}}
                                        <form action="{{ route('admin.verify', [$student->id, 'DITOLAK']) }}" method="POST" class="ms-1">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-danger" {{ $student->status->value == 'DITOLAK' ? 'disabled' : '' }}>Tolak</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>