<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        // Gunakan query builder agar bisa difilter
        $query = User::where('role', UserRole::STUDENT)
            ->with(['parentDetail', 'schoolDetail.city', 'birthCity']);

        // Fitur Pencarian (Nama / NISN)
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                    ->orWhere('nisn', 'like', '%' . $request->search . '%');
            });
        }

        // Fitur Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $students = $query->latest()->get();

        return view('admin.dashboard', compact('students'));
    }

    public function updateStatus(User $user, $status)
    {
        // Validasi apakah status yang dikirim ada di dalam Enum RegistrationStatus
        // Ini mencegah error jika ada input manual via URL yang tidak valid
        $validStatuses = array_column(RegistrationStatus::cases(), 'value');

        if (!in_array($status, $validStatuses)) {
            return back()->with('error', "Status tidak valid.");
        }

        $user->update(['status' => $status]);

        // Supaya rapi, kita konversi pesan suksesnya
        $message = "Status {$user->full_name} berhasil diubah menjadi " . strtoupper($status);

        return back()->with('success', $message);
    }
}
