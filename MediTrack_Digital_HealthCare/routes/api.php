<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Kita pakai format String agar tidak ada error "unexpected token class"
$controller = 'App\Http\Controllers\Api\PatientApiController';

// 1. Jalur Login
Route::post('/login', $controller . '@login');

// 2. Jalur Riwayat (Pasien)
Route::get('/get-history', $controller . '@myHistory');
Route::get('/get-history-debug', $controller . '@myHistory');

// 3. Jalur Buat Janji Temu (Poin B)
Route::post('/create-appointment', $controller . '@storeAppointment');

// 4. Jalur Jadwal Dokter (Poin A - Akun Dokter)
Route::get('/doctor-schedule', $controller . '@doctorSchedule');

// Jalur User (Bawaan Laravel)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
