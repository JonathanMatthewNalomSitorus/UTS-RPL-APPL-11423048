<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Pastikan User Budi (ID 4) & Dokter (ID 2) ada
        // (Jika kamu sudah punya data ini, lewati bagian ini)

        // 2. Buat Data Riwayat Medis
        \App\Models\Appointment::create([
            'patient_id' => 4, // ID Budi Santoso
            'doctor_id' => 2,  // ID Dokter (Sesuaikan ID di DB kamu)
            'appointment_date' => now()->subDays(2), // 2 hari yang lalu
            'notes' => 'Pasien mengeluh sesak napas ringan. Diagnosa: Gejala asma ringan. Disarankan pakai inhaler.',
            'status' => 'completed',
        ]);

        \App\Models\Appointment::create([
            'patient_id' => 4,
            'doctor_id' => 1,
            'appointment_date' => now()->subMonths(1), // 1 bulan lalu
            'notes' => 'Cek kesehatan rutin. Tekanan darah 120/80. Kondisi sangat baik.',
            'status' => 'completed',
        ]);
    }
}
