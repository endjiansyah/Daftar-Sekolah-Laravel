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

    public function updateStatus(User $user, $status)
    {
        // Cek apakah string yang dikirim sesuai dengan Enum (TERVERIFIKASI / DITOLAK)
        try {
            $user->update([
                'status' => $status
            ]);

            $msg = $status == 'TERVERIFIKASI' ? 'Pendaftaran diterima!' : 'Pendaftaran ditolak.';
            return back()->with('success', "Berhasil! Status {$user->full_name} kini {$msg}");
        } catch (\Exception $e) {
            return back()->with('error', "Gagal memperbarui status: " . $e->getMessage());
        }
    }
}
