<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Medicine;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * HALAMAN 1: Antrean Pasien (Penjadwalan - Fitur b)
     * Menampilkan daftar pasien yang baru mendaftar atau dijadwalkan ulang.
     */
    public function index()
    {
        $appointments = Appointment::with('patient')
            ->where('doctor_id', Auth::id())
            // PERBAIKAN: Menambahkan 'pending' agar data dari Flutter muncul di tabel
            ->whereIn('status', ['pending', 'booking', 'rescheduled'])
            ->orderBy('appointment_date', 'asc')
            ->get();

        return view('doctor.appointments_index', compact('appointments'));
    }

    /**
     * Update Status: Reschedule atau Cancel (Fitur b)
     */
    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        if ($request->action == 'reschedule') {
            $request->validate(['new_date' => 'required|after:now']);
            $appointment->update([
                'appointment_date' => $request->new_date,
                'status' => 'rescheduled'
            ]);
            return back()->with('success', 'Jadwal berhasil diperbarui.');
        }

        if ($request->action == 'cancel') {
            $appointment->update(['status' => 'cancelled']);
            return back()->with('info', 'Janji temu dibatalkan.');
        }
    }

    /**
     * HALAMAN 2: Riwayat Medis / EHR (Fitur c)
     */
    public function history(Request $request)
    {
        $query = Appointment::with('patient')
            ->where('doctor_id', Auth::id())
            ->where('status', 'diagnosed');

        if ($request->search) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $histories = $query->latest()->get();
        return view('doctor.patient_history', compact('histories'));
    }

    /**
     * FITUR C: Simpan Diagnosa & Resep (EHR)
     */
    public function updateEHR(Request $request, $id)
    {
        $request->validate([
            'diagnosis' => 'required',
            'prescription' => 'required',
            'amount' => 'required|numeric'
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->update([
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'lab_results' => $request->lab_results,
            'amount' => $request->amount,
            'status' => 'diagnosed',
            'payment_status' => 'unpaid'
        ]);

        return redirect()->route('doctor.history')->with('success', 'EHR Berhasil disimpan.');
    }

    /**
     * Menampilkan form pemeriksaan untuk dokter
     */
    public function edit($id)
    {
        $appointment = Appointment::with('patient')->findOrFail($id);

        // Proteksi agar dokter hanya bisa akses pasiennya sendiri
        if ($appointment->doctor_id !== Auth::id()) {
            return redirect()->route('appointments.index')->with('error', 'Akses ditolak.');
        }

        return view('doctor.appointments_edit', compact('appointment'));
    }

    /**
     * Fitur d: Antrean Apotek
     */
    public function pharmacyIndex()
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->where('status', 'diagnosed')
            ->orderBy('updated_at', 'asc')
            ->get();

        return view('pharmacists.pharmacy_index', compact('appointments'));
    }

    /**
     * Fitur d: Manajemen Stok Obat
     */
    public function stockIndex()
    {
        $medicines = Medicine::all();
        return view('pharmacists.stock_index', compact('medicines'));
    }

    /**
     * Fitur d: Penyerahan Obat & Pengurangan Stok
     */
    public function dispense(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        // Cari obat yang namanya ada di dalam teks resep dokter
        $medicine = Medicine::where('name', 'like', '%' . $appointment->prescription . '%')->first();

        if ($medicine) {
            if ($medicine->stock > 0) {
                $medicine->decrement('stock', 1);
            } else {
                return back()->with('error', 'Stok obat ' . $medicine->name . ' habis!');
            }
        }

        // Selesaikan alur janji temu
        $appointment->update([
            'status' => 'completed',
            'payment_status' => 'paid'
        ]);

        return redirect()->route('pharmacy.index')->with('success', 'Obat berhasil diserahkan & stok diperbarui!');
    }
}
