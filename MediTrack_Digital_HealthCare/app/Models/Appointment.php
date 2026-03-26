<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    // Tambahkan ini agar Laravel tahu kolom mana saja yang boleh diisi
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'notes',
        'status'
    ];

    /**
     * Relasi ke User (sebagai Dokter)
     * Kita asumsikan dokter adalah User yang punya role 'doctor'
     */
    public function doctor()
    {
        // Parameter kedua adalah foreign key di tabel appointments
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
