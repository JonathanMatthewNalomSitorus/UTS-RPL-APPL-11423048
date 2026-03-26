<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Medicine;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Proteksi Role (Fitur a)
        if (!$user || $user->role === 'patient') {
            Auth::logout();
            return redirect('/login')->with('error', 'Akses ditolak. Silakan gunakan aplikasi mobile.');
        }

        // 2. Logika Khusus Admin
        if ($user->role === 'admin') {
            if (!$request->is('admin/*')) {
                return redirect()->route('dashboard');
            }

            // Analisis Ringkas (Fitur e)
            $total_patients = User::where('role', 'patient')->count();
            $pending_appointments = Appointment::where('status', 'booking')->count();
            $low_stock_medicines = Medicine::where('stock', '<', 10)->count() ?? 0;

            // --- TAMBAHAN UNTUK FITUR (E) & (F) ---

            // Hitung Total Pendapatan Hari Ini (Analisis Tren - Fitur e)
            $today_revenue = Appointment::whereDate('updated_at', Carbon::today())
                ->where('payment_status', 'paid')
                ->sum('amount');

            // Ambil Tagihan yang Belum Dibayar (Kasir - Fitur f)
            // Pasien yang sudah diperiksa dokter (status: diagnosed) tapi belum bayar
            $pending_payments = Appointment::with('patient')
                ->where('status', 'diagnosed')
                ->where('payment_status', 'unpaid')
                ->latest()
                ->get();

            // Data Janji Temu Hari Ini (Fitur b)
            $today_appointments = Appointment::with(['patient', 'doctor'])
                ->whereDate('appointment_date', Carbon::today())
                ->latest()
                ->take(5)
                ->get();

            return view('admin.admin_dashboard', compact(
                'total_patients',
                'pending_appointments',
                'low_stock_medicines',
                'today_appointments',
                'pending_payments', // Kirim ke view untuk Kasir
                'today_revenue'     // Kirim ke view untuk Analisis
            ));
        }

        // 3. Logika Khusus Dokter (Fitur b & c)
        if ($user->role === 'doctor') {
            return redirect()->route('appointments.index');
        }

        // 4. Logika Khusus Apoteker (Fitur d)
        if ($user->role === 'pharmacist') {
            return redirect()->route('pharmacy.index');
        }

        return redirect('/login');
    }
}
