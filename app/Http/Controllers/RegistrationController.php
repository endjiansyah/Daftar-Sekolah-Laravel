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
        // 1. Validasi Dasar
        $rules = [
            'registration_type' => 'required|in:partial,complete',
            'username'          => 'required|unique:users,username',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:8',
            'full_name'         => 'required|string',
            'nisn'              => 'nullable|digits:10',
            'pob'               => 'nullable|exists:cities,id', // Validasi ID Kota
        ];

        // 2. Validasi Tambahan untuk Pendaftaran Lengkap
        if ($request->registration_type === 'complete') {
            $rules = array_merge($rules, [
                'nisn'            => 'required|digits:10',
                'pob'             => 'required|exists:cities,id',
                'dob'             => 'required|date',
                'address'         => 'required',
                'parent_name'     => 'required|string',
                'parent_phone'    => 'required',
                'school_name'     => 'required',
                'school_address'  => 'required',
                'city_id'         => 'required|exists:cities,id',
                'graduation_year' => 'required|numeric',
            ]);
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            // 3. Simpan User (pob menyimpan ID kota)
            $user = User::create([
                'username'     => $request->username,
                'email'        => $request->email,
                'password'     => Hash::make($request->password),
                'full_name'    => $request->full_name,
                'nisn'         => $request->nisn,
                'gender'       => $request->gender,
                'phone_number' => $request->phone_number,
                'pob'          => $request->pob, // Simpan ID Kota
                'dob'          => $request->dob,
                'address'      => $request->address,
                'role'         => 'student',
                'status'       => RegistrationStatus::DAFTAR,
            ]);

            // 4. Simpan Detail Ortu (jika diisi)
            if ($request->filled('parent_name')) {
                $user->parentDetail()->create($request->only(['parent_name', 'relationship', 'parent_phone', 'parent_email']));
            }

            // 5. Simpan Detail Sekolah (jika diisi)
            if ($request->filled('school_name')) {
                $user->schoolDetail()->create([
                    'school_name'     => $request->school_name,
                    'school_address'  => $request->school_address,
                    'city_id'         => $request->city_id,
                    'graduation_year' => $request->graduation_year,
                ]);
            }

            // 6. Kirim Email Selamat Datang
            Mail::to($user->email)->send(new WelcomeRegistrationMail($user));

            DB::commit();
            Auth::login($user);
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Menampilkan halaman edit profil.
     */
    public function edit()
    {
        // Mengambil user yang sedang login beserta semua detailnya
        $user = auth()->user()->load(['parentDetail', 'schoolDetail', 'birthCity']);

        // Mengambil data kota untuk dropdown
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
            'full_name'    => 'required|string',
            'nisn'         => 'required|digits:10',
            'pob'          => 'required|exists:cities,id', // Validasi ID Kota
            'dob'          => 'required|date',
            'phone_number' => 'required',
            'address'      => 'required',
            'parent_name'  => 'required',
            'parent_phone' => 'required',
            'city_id'      => 'required|exists:cities,id', // Kota Sekolah
            'school_name'  => 'required',
        ]);

        try {
            DB::transaction(function () use ($user, $request) {
                // Update Biodata Utama
                $user->update([
                    'full_name'    => $request->full_name,
                    'nisn'         => $request->nisn,
                    'gender'       => $request->gender,
                    'phone_number' => $request->phone_number,
                    'pob'          => $request->pob,
                    'dob'          => $request->dob,
                    'address'      => $request->address,
                ]);

                // Update/Create Detail Ortu
                $user->parentDetail()->updateOrCreate(
                    ['user_id' => $user->id],
                    $request->only(['parent_name', 'relationship', 'parent_phone', 'parent_email'])
                );

                // Update/Create Detail Sekolah
                $user->schoolDetail()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'school_name'     => $request->school_name,
                        'school_address'  => $request->school_address,
                        'city_id'         => $request->city_id,
                        'graduation_year' => $request->graduation_year,
                    ]
                );
            });

            return redirect()->route('dashboard')->with('success', 'Profil berhasil dilengkapi!');
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
        'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)],
    ], [
        'current_password.current_password' => 'Password lama salah.',
        'password.confirmed' => 'Konfirmasi password baru tidak cocok.'
    ]);

    $request->user()->update([
        'password' => \Illuminate\Support\Facades\Hash::make($request->password),
    ]);

    // Menggunakan back() agar kembali ke halaman asal (Admin tetap di Admin, Siswa tetap di Siswa)
    return back()->with('success_password', 'Password berhasil diperbarui!');
}
}
