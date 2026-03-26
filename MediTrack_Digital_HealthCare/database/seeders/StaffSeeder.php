<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        // a) Akun Admin (Untuk Manajemen & Analisis)
        User::create([
            'name' => 'Administrator MediTrack',
            'email' => 'admin@meditrack.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // b) Akun Dokter (Untuk Penjadwalan & EHR)
        User::create([
            'name' => 'dr. Andi Pratama',
            'email' => 'doctor@meditrack.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
        ]);

        // c) Akun Apoteker (Untuk Farmasi & Stok)
        User::create([
            'name' => 'Siska Farmasi, Apt.',
            'email' => 'pharmacist@meditrack.com',
            'password' => Hash::make('password'),
            'role' => 'pharmacist',
        ]);

        // d) Akun Pasien Contoh (Untuk Testing Login Mobile)
        User::create([
            'name' => 'Budi Pasien',
            'email' => 'patient@test.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
        ]);
    }
}
