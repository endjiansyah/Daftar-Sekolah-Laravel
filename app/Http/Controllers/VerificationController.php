<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index()
    {
        // Ambil semua user dengan role student beserta relasinya
        $students = User::where('role', 'student')
            ->with(['parentDetail', 'schoolDetail', 'status'])
            ->latest()
            ->get();

        return view('admin.dashboard', compact('students'));
    }

    public function updateStatus(User $user, $statusId)
    {
        // Validasi: Cek kelengkapan data sebelum verifikasi ke status 'DITERIMA' (ID: 2 misal)
        // Jika status yang dituju adalah diterima (sesuaikan ID status di DB Anda)
        if ($statusId == 2) { 
            if (empty($user->nisn) || !$user->parentDetail || !$user->schoolDetail) {
                return back()->with('error', 'Gagal! Data siswa belum lengkap untuk diverifikasi.');
            }
        }

        $user->update(['status_id' => $statusId]);

        return back()->with('success', 'Status pendaftaran berhasil diperbarui.');
    }
}