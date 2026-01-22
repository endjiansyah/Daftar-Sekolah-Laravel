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
            <span class="navbar-brand">PPDB ONLINE</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-outline-light btn-sm">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">{{ $user->full_name }}</h5>
                        <p class="text-muted small">{{ $user->email }}</p>
                        <hr>
                        <div class="badge {{ $user->status->value === 'TERVERIFIKASI' ? 'bg-success' : 'bg-warning' }} p-2 w-100 fs-6">
                            STATUS: {{ $user->status->value }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold">Detail Data Identitas</div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr><th width="30%">NISN</th><td>: {{ $user->nisn ?? '-' }}</td></tr>
                            <tr><th>Gender</th><td>: {{ $user->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                            <tr><th>Tempat Lahir</th><td>: {{ $user->pob ?? '-' }}</td></tr>
                            <tr><th>Tanggal Lahir</th><td>: {{ $user->dob ?? '-' }}</td></tr>
                            <tr><th>Alamat</th><td>: {{ $user->address ?? '-' }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>