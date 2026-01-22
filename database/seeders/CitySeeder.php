<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan tabel sebelum import
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cities')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Link RAW dari repo yusufsyaifudin yang Anda temukan
        $url = "https://raw.githubusercontent.com/yusufsyaifudin/wilayah-indonesia/master/data/list_of_area/regencies.json";
        
        $this->command->info("Menghubungkan ke GitHub untuk mengambil 514 data kota...");

        try {
            // Mengambil data dengan timeout 30 detik
            $response = Http::timeout(30)->withoutVerifying()->get($url);

            if ($response->successful()) {
                $cities = $response->json();
                
                $this->command->getOutput()->progressStart(count($cities));

                foreach ($cities as $city) {
                    DB::table('cities')->insert([
                        'id'         => $city['id'],    // ID resmi (contoh: 3273)
                        'name'       => $city['name'],  // Nama Kota (contoh: KOTA BANDUNG)
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $this->command->getOutput()->progressAdvance();
                }

                $this->command->getOutput()->progressFinish();
                $this->command->info("Berhasil! Seluruh Kota/Kabupaten telah masuk ke database.");
            } else {
                $this->command->error("Gagal mengambil data. Status: " . $response->status());
            }
        } catch (\Exception $e) {
            $this->command->error("Koneksi gagal: " . $e->getMessage());
        }
    }
}