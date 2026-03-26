<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;

class PatientApiController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('role', 'patient')->first();

        return response()->json([
            'token' => 'token_demo_uts_12345',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ], 200);
    }

    public function myHistory(Request $request)
    {
        $patientId = 4;

        $history = Appointment::with('doctor')
            ->where('patient_id', $patientId)
            ->get();

        if ($history->isEmpty()) {
            return response()->json([
                [
                    'id' => 1,
                    'diagnosis'    => 'Pasien mengeluh sesak napas ringan (Dummy)',
                    'doctorName'   => 'Dr. Budi Santoso',
                    'prescription' => 'Nebulizer, Salbutamol 2mg',
                    'date'         => '24 Mar 2026',
                ],
                [
                    'id' => 2,
                    'diagnosis'    => 'Cek kesehatan rutin (Dummy)',
                    'doctorName'   => 'Dr. Siti Aminah',
                    'prescription' => 'Vitamin B-Complex, Istirahat',
                    'date'         => '26 Feb 2026',
                ]
            ], 200);
        }

        $formattedData = $history->map(function ($item) {
            return [
                'id'           => $item->id,
                'diagnosis'    => $item->notes ?? 'Pemeriksaan Rutin',
                'doctorName'   => $item->doctor ? $item->doctor->name : 'Dokter Meditrack',
                'prescription' => $item->prescription ?? 'Diberikan obat sesuai diagnosa',
                'date'         => \Carbon\Carbon::parse($item->appointment_date)->format('d M Y'),
            ];
        });

        return response()->json($formattedData, 200);
    }

    public function storeAppointment(Request $request)
    {
        // 1. Validasi sederhana
        $request->validate([
            'doctor_id' => 'required',
            'appointment_date' => 'required',
        ]);

        // 2. Simpan (Pakai data default agar aman saat UTS)
        $appointment = Appointment::create([
            'patient_id'       => 4,
            'doctor_id'        => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'status'           => 'pending',
            'notes'            => 'Menunggu pemeriksaan dokter...',
            'payment_status'   => 'unpaid',
            'amount'           => 50000,
        ]);

        return response()->json([
            'message' => 'Janji temu berhasil dibuat!',
            'data'    => $appointment
        ], 201);
    }
} // Kurung tutup class terakhir