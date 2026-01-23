<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #334155; margin: 0; padding: 0; background-color: #f8fafc; }
        .wrapper { width: 100%; padding: 40px 0; background-color: #f8fafc; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background-color: #2563eb; padding: 40px 20px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 700; letter-spacing: 0.5px; }
        .content { padding: 40px 30px; }
        .content p { margin-bottom: 20px; font-size: 16px; }
        .status-box { background-color: #f1f5f9; border-radius: 8px; padding: 20px; text-align: center; margin: 30px 0; border: 1px solid #e2e8f0; }
        .status-label { display: block; font-size: 12px; text-transform: uppercase; color: #64748b; font-weight: 700; margin-bottom: 5px; }
        .status-value { font-size: 20px; color: #0f172a; font-weight: 800; }
        .footer { background-color: #f8fafc; padding: 20px; text-align: center; color: #94a3b8; font-size: 13px; border-top: 1px solid #f1f5f9; }
        .btn { display: inline-block; padding: 12px 30px; background-color: #2563eb; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>PPDB ONLINE</h1>
            </div>

            <div class="content">
                <p>Halo, <strong>{{ $user->full_name }}</strong>!</p>
                <p>Kami senang menginformasikan bahwa pendaftaran akun Anda telah berhasil diterima oleh sistem kami. Berikut adalah status pendaftaran Anda saat ini:</p>

                <div class="status-box">
                    <span class="status-label">Status Pendaftaran</span>
                    <span class="status-value">{{ $user->status->value }}</span>
                </div>

                <p>Silakan login ke dashboard siswa untuk melengkapi dokumen pendukung (jika ada) dan memantau proses verifikasi lebih lanjut.</p>
                
                <div style="text-align: center;">
                    <a href="{{ url('/login') }}" class="btn">Masuk ke Dashboard</a>
                </div>

                <p style="margin-top: 30px; font-size: 14px; color: #64748b;">
                    Jika Anda tidak merasa melakukan pendaftaran ini, abaikan email ini atau hubungi tim dukungan kami.
                </p>
            </div>

            <div class="footer">
                &copy; {{ date('Y') }} Panitia PPDB Online. All rights reserved.<br>
                Sekolah Menengah Unggulan Indonesia
            </div>
        </div>
    </div>
</body>
</html>