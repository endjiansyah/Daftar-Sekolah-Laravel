<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\City;
use App\Enums\RegistrationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeRegistrationMail;

class RegistrationController extends Controller
{
    /**
     * TAMPILAN REGISTER
     */
    public function create()
    {
        $cities = City::orderBy('name', 'asc')->get();
        return view('auth.register', compact('cities'));
    }

    /**
     * Menangani pendaftaran akun baru (Siswa).
     */
    public function store(Request $request)
    {
        // 1. Validasi Gabungan (Dasar + Kondisional)
        $rules = [
            'registration_type' => 'required|in:partial,complete',
            'username'          => 'required|unique:users,username',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:8',
            'full_name'         => 'required|string',
            'nisn'              => 'nullable|digits:10|unique:users,nisn', // Tepat 10 digit
            'pob'               => 'nullable|exists:cities,id',
        ];

        // 2. Tambah Validasi jika Tipe Pendaftaran 'Lengkap'
        if ($request->registration_type === 'complete') {
            $rules = array_merge($rules, [
                'nisn'            => 'required|digits:10|unique:users,nisn',
                'pob'             => 'required|exists:cities,id',
                'dob'             => 'required|date',
                'address'         => 'required',
                'parent_name'     => 'required|string',
                'parent_phone'    => 'required',
                'school_name'     => 'required',
                'school_address'  => 'required',
                'city_id'         => 'required|exists:cities,id',
                'graduation_year' => 'required|numeric',
                'average_score'   => 'required|numeric|between:0,100',
            ]);
        }

        // Pesan Error Custom agar User tidak bingung
        $messages = [
            'nisn.digits' => 'NISN harus tepat 10 digit angka.',
            'nisn.unique' => 'NISN ini sudah digunakan oleh pendaftar lain.',
            'average_score.between' => 'Nilai rata-rata harus di antara 0 sampai 100.',
        ];

        $request->validate($rules, $messages);

        try {
            DB::beginTransaction();

            // 3. Simpan User (average_score sekarang masuk ke tabel users)
            $user = User::create([
                'username'      => $request->username,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'full_name'     => $request->full_name,
                'nisn'          => $request->nisn,
                'gender'        => $request->gender,
                'phone_number'  => $request->phone_number,
                'pob'           => $request->pob,
                'dob'           => $request->dob,
                'address'       => $request->address,
                'average_score' => $request->registration_type === 'complete' ? $request->average_score : null,
                'role'          => \App\Enums\UserRole::STUDENT, // Pastikan Enum ini sesuai projectmu
                'status'        => RegistrationStatus::DAFTAR,
            ]);

            // 4. Simpan Detail Ortu (jika diisi)
            if ($request->filled('parent_name')) {
                $user->parentDetail()->create([
                    'parent_name'   => $request->parent_name,
                    'relationship'  => $request->relationship,
                    'parent_phone'  => $request->parent_phone,
                    'parent_email'  => $request->parent_email,
                ]);
            }

            // 5. Simpan Detail Sekolah (jika diisi)
            if ($request->filled('school_name')) {
                $user->schoolDetail()->create([
                    'school_name'     => $request->school_name,
                    'school_address'  => $request->school_address,
                    'city_id'         => $request->city_id,
                    'graduation_year' => $request->graduation_year,
                    // average_score sudah dipindah ke tabel User, tidak perlu di sini lagi
                ]);
            }

            // 6. Kirim Email Selamat Datang
            Mail::to($user->email)->send(new WelcomeRegistrationMail($user));

            DB::commit();

            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Pendaftaran berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal Mendaftar: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan halaman edit profil.
     */
    public function edit()
    {
        $user = auth()->user()->load(['parentDetail', 'schoolDetail', 'birthCity']);
        $cities = \App\Models\City::orderBy('name', 'asc')->get();
        return view('student.edit', compact('user', 'cities'));
    }

    /**
     * Memperbarui profil siswa.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name'     => 'required|string',
            // 'ignore($user->id)' penting agar tidak error saat update data sendiri
            'nisn'          => 'required|digits:10|unique:users,nisn,' . $user->id,
            'pob'           => 'required|exists:cities,id',
            'dob'           => 'required|date',
            'phone_number'  => 'required',
            'address'       => 'required',
            'parent_name'   => 'required',
            'parent_phone'  => 'required',
            'city_id'       => 'required|exists:cities,id',
            'school_name'   => 'required',
            'average_score' => 'required|numeric|between:0,100',
        ], [
            'nisn.digits' => 'NISN harus tepat 10 digit angka.',
            'nisn.unique' => 'NISN ini sudah digunakan oleh pengguna lain.',
        ]);

        try {
            DB::transaction(function () use ($user, $request) {
                // 1. Update Tabel Users (Termasuk average_score)
                $user->update([
                    'full_name'     => $request->full_name,
                    'nisn'          => $request->nisn,
                    'gender'        => $request->gender,
                    'phone_number'  => $request->phone_number,
                    'pob'           => $request->pob,
                    'dob'           => $request->dob,
                    'address'       => $request->address,
                    'average_score' => $request->average_score, // Simpan di tabel user
                ]);

                // 2. Update/Create Detail Ortu
                $user->parentDetail()->updateOrCreate(
                    ['user_id' => $user->id],
                    $request->only(['parent_name', 'relationship', 'parent_phone', 'parent_email'])
                );

                // 3. Update/Create Detail Sekolah
                $user->schoolDetail()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'school_name'     => $request->school_name,
                        'school_address'  => $request->school_address,
                        'city_id'         => $request->city_id,
                        'graduation_year' => $request->graduation_year,
                        // average_score sudah tidak ada di tabel ini
                    ]
                );
            });

            return redirect()->route('dashboard')->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Gagal update: ' . $e->getMessage()]);
        }
    }

    /**
     * Mengubah password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'Password lama salah.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.'
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success_password', 'Password berhasil diperbarui!');
    }
}
