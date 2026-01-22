<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index()
    {
        // Ambil semua user yang rolenya student
        $students = User::where('role', UserRole::STUDENT)
                        ->with(['parentDetail', 'schoolDetail'])
                        ->latest()
                        ->get();

        return view('admin.dashboard', compact('students'));
    }

    public function updateStatus(User $user, $status)
    {
        // Update status menggunakan value dari Enum
        $user->update(['status' => $status]);

        return back()->with('success', "Status {$user->full_name} berhasil diubah menjadi {$status}");
    }
}
