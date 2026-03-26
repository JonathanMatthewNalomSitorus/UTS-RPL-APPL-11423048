<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Medicine;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gunakan updateOrCreate agar tidak error jika dijalankan berkali-kali
        Medicine::updateOrCreate(
            ['name' => 'Paracetamol 500mg'],
            [
                'stock' => 5,
                'price' => 5000
            ]
        );

        Medicine::updateOrCreate(
            ['name' => 'Amoxicillin'],
            [
                'stock' => 50,
                'price' => 12000
            ]
        );

        Medicine::updateOrCreate(
            ['name' => 'Promag'],
            [
                'stock' => 8,
                'price' => 8000
            ]
        );
    }
}
