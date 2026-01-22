<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\City;
use App\Enums\RegistrationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeRegistrationMail;

class RegistrationController extends Controller
{
    /**
     * Menangani pendaftaran akun baru (Siswa).
     * Sesuai instruksi: Membuang logika parsial, langsung mewajibkan data lengkap.
     */
    public function store(Request $request)
    {
        // 1. Validasi Data Mandatory (Section A, B, dan C)
        $rules = [
            // Identitas Akun & Personal (A)
            'username'       => 'required|unique:users,username',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|min:8',
            'full_name'      => 'required|string|max:255',
            'nisn'           => 'required|numeric|digits:10',
            'gender'         => 'required|in:L,P',
            'phone_number'   => 'required', // Tambahan requirement assessment

            // Data Orang Tua (B)
            'parent_name'    => 'required|string',
            'parent_phone'   => 'required',
            'parent_email'   => 'required|email',

            // Data Sekolah Asal (C)
            'school_name'    => 'required|string',
            'school_address' => 'required|string',
            'city_id'        => 'required|exists:cities,id', // Menggunakan ID dari tabel cities 514 kota
            'graduation_year'=> 'required|numeric',
        ];

        $request->validate($rules);

        try {
            DB::beginTransaction();

            // 2. Membuat record user (Table: users)
            $user = User::create([
                'username'     => $request->username,
                'email'        => $request->email,
                'password'     => Hash::make($request->password),
                'full_name'    => $request->full_name,
                'nisn'         => $request->nisn,
                'gender'       => $request->gender,
                'phone_number' => $request->phone_number,
                'pob'          => $request->pob,
                'dob'          => $request->dob,
                'address'      => $request->address,
                'role'         => 'student',
                'status'       => RegistrationStatus::DAFTAR,
            ]);

            // 3. Membuat record data orang tua (Table: parent_details)
            $user->parentDetail()->create([
                'parent_name'  => $request->parent_name,
                'relationship' => $request->relationship,
                'parent_phone' => $request->parent_phone,
                'parent_email' => $request->parent_email,
            ]);

            // 4. Membuat record data sekolah (Table: school_details)
            $user->schoolDetail()->create([
                'school_name'    => $request->school_name,
                'school_address' => $request->school_address,
                'city_id'        => $request->city_id, // Menyimpan ID hasil dropdown
                'graduation_year'=> $request->graduation_year,
            ]);

            DB::commit();

            Auth::login($user);

            // Kirim email selamat datang (opsional, dibungkus try agar tidak menghambat pendaftaran)
            try {
                Mail::to($user->email)->send(new WelcomeRegistrationMail($user));
            } catch (\Exception $e) {
                // Lanjutkan jika mail server belum diset
            }

            return redirect()->route('dashboard')->with('success', 'Pendaftaran berhasil!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal mendaftar: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan halaman edit profil.
     * Mengambil daftar 514 kota untuk ditampilkan di dropdown View.
     */
    public function edit()
    {
        $user = Auth::user()->load(['parentDetail', 'schoolDetail.city']);
        
        // Ambil semua kota dari database untuk dropdown
        $cities = City::orderBy('name', 'asc')->get(); 
        
        return view('student.edit', compact('user', 'cities'));
    }

    /**
     * Memperbarui profil siswa, orang tua, dan sekolah secara sinkron.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name'      => 'required|string',
            'nisn'           => 'required|numeric|digits:10',
            'city_id'        => 'required|exists:cities,id',
            'school_name'    => 'required',
            'parent_name'    => 'required',
            'parent_phone'   => 'required',
        ]);

        try {
            DB::transaction(function () use ($user, $request) {
                // Update Identitas Dasar
                $user->update([
                    'full_name'    => $request->full_name,
                    'nisn'         => $request->nisn,
                    'gender'       => $request->gender,
                    'phone_number' => $request->phone_number,
                    'pob'          => $request->pob,
                    'dob'          => $request->dob,
                    'address'      => $request->address,
                ]);

                // Update/Create Data Orang Tua
                $user->parentDetail()->updateOrCreate(
                    ['user_id' => $user->id],
                    $request->only(['parent_name', 'relationship', 'parent_phone', 'parent_email'])
                );

                // Update/Create Data Sekolah Asal
                $user->schoolDetail()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'school_name'    => $request->school_name,
                        'school_address' => $request->school_address,
                        'city_id'        => $request->city_id,
                        'graduation_year'=> $request->graduation_year,
                    ]
                );
            });

            return redirect()->route('dashboard')->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    /**
     * Mengubah password user yang sedang login.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => 'required|confirmed|min:8',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}