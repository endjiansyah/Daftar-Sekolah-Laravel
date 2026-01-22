<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\UserRole;
use App\Enums\RegistrationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeRegistrationMail;

class RegistrationController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Dasar (Data Pribadi)
        $rules = [
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'full_name' => 'required|string|max:255',
        ];

        // 2. Tambah Validasi jika user memilih pendaftaran "Lengkap"
        if ($request->registration_type === 'complete') {
            $rules = array_merge($rules, [
                'parent_name' => 'required',
                'parent_phone' => 'required',
                'school_name' => 'required',
                'city' => 'required',
                'graduation_year' => 'required|numeric',
            ]);
        }

        $validated = $request->validate($rules);

        // 3. Eksekusi Simpan Data dengan Transaction
        try {
            DB::beginTransaction();

            // Simpan ke Tabel Users
            $user = User::create([
                'username'  => $request->username,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'full_name' => $request->full_name,
                'nisn'      => $request->nisn,
                'gender'    => $request->gender,
                'pob'       => $request->pob,
                'dob'       => $request->dob,
                'address'   => $request->address,
                'role'      => 'student',
                'status' => \App\Enums\RegistrationStatus::DAFTAR,
            ]);

            // Jika Complete, Simpan ke tabel pendukung
            if ($request->registration_type === 'complete') {
                $user->parentDetail()->create([
                    'parent_name' => $request->parent_name,
                    'relationship' => $request->relationship,
                    'parent_phone' => $request->parent_phone,
                    'parent_email' => $request->parent_email,
                ]);

                $user->schoolDetail()->create([
                    'school_name' => $request->school_name,
                    'school_address' => $request->school_address,
                    'city' => $request->city,
                    'graduation_year' => $request->graduation_year,
                ]);
            }

            DB::commit();
            
            // 4. Auto-Login setelah daftar (Sesuai Requirement)
            Auth::login($user);

            // 5. Kirim Email Notifikasi
            Mail::to($user->email)->send(new WelcomeRegistrationMail($user));
            
            return redirect()->route('dashboard')->with('success', 'Pendaftaran berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function completeProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'parent_name' => 'required|string',
            'relationship' => 'required',
            'parent_phone' => 'required',
            'school_name' => 'required',
            'city' => 'required',
            'graduation_year' => 'required|numeric',
        ]);

        DB::transaction(function () use ($user, $validated) {
            $user->parentDetail()->create([
                'parent_name' => $validated['parent_name'],
                'relationship' => $validated['relationship'],
                'parent_phone' => $validated['parent_phone'],
            ]);

            $user->schoolDetail()->create([
                'school_name' => $validated['school_name'],
                'school_address' => '-', // Bisa ditambahkan inputnya nanti
                'city' => $validated['city'],
                'graduation_year' => $validated['graduation_year'],
            ]);
        });

        return back()->with('success', 'Data berhasil dilengkapi!');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'parent_name' => 'required',
            'parent_phone' => 'required',
            'school_name' => 'required',
            'city' => 'required',
            'graduation_year' => 'required|numeric',
        ]);

        // Simpan/Update data menggunakan updateOrCreate (biar aman)
        $user->parentDetail()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'parent_name' => $request->parent_name,
                'relationship' => $request->relationship,
                'parent_phone' => $request->parent_phone,
                'parent_email' => $request->parent_email,
            ]
        );

        $user->schoolDetail()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'school_name' => $request->school_name,
                'school_address' => $request->school_address,
                'city' => $request->city,
                'graduation_year' => $request->graduation_year,
            ]
        );

        return back()->with('success', 'Data berhasil dilengkapi! Admin akan segera memverifikasi.');
    }
}
