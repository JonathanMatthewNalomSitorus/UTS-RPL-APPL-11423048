<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\AppointmentController;
use App\Http\Controllers\Api\PatientApiController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::post('/login', [PatientApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/my-history', [PatientApiController::class, 'myHistory']);
});

// 1. Redirect Halaman Utama ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Auth Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// 3. Authenticated Routes (Staff Only)
Route::middleware(['auth'])->group(function () {

    // --- ROLE: ADMIN ---
    Route::prefix('admin')->group(function () {
        // URL: /admin/dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/admin/pay/{id}', function ($id) {
            $app = \App\Models\Appointment::findOrFail($id);
            $app->update(['payment_status' => 'paid']);
            return back()->with('success', 'Pembayaran Pasien ' . $app->patient->name . ' Berhasil!');
        })->name('admin.pay');
    });

    // --- ROLE: DOCTOR (Penjadwalan & EHR) ---
    Route::prefix('doctor')->middleware(['auth'])->group(function () {
        Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
        Route::post('/appointments/{id}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
        Route::get('/history', [AppointmentController::class, 'history'])->name('doctor.history');
        Route::get('/appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
        Route::patch('/appointments/{id}', [AppointmentController::class, 'updateEHR'])->name('appointments.update');
    });

    // --- ROLE: PHARMACIST (Apotek) ---
    Route::prefix('pharmacy')->middleware(['auth'])->group(function () {
        // Menu Antrean Resep
        Route::get('/queue', [AppointmentController::class, 'pharmacyIndex'])->name('pharmacy.index');

        // TAMBAHKAN BARIS INI: Pintu untuk Stok Obat
        Route::get('/stock', [AppointmentController::class, 'stockIndex'])->name('pharmacy.stock');

        // Route untuk aksi penyerahan obat
        Route::post('/dispense/{id}', [AppointmentController::class, 'dispense'])->name('pharmacy.dispense');
    });

    // Universal Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

require __DIR__ . '/auth.php';
