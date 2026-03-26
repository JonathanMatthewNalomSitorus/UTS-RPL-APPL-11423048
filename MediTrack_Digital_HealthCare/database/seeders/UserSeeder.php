<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun ADMIN (Untuk Analisis & Manajemen)
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@meditrack.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Akun DOKTER (Untuk EHR & Penjadwalan)
        User::create([
            'name' => 'dr. Pratama, Sp.A',
            'email' => 'doctor@meditrack.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
        ]);

        // 3. Akun APOTEKER (Untuk Farmasi & Stok)
        User::create([
            'name' => 'Siska, S.Farm',
            'email' => 'pharmacist@meditrack.com',
            'password' => Hash::make('password'),
            'role' => 'pharmacist',
        ]);

        // 4. Akun PASIEN (Untuk Testing di Flutter nanti)
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'patient@test.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
        ]);
    }
}
