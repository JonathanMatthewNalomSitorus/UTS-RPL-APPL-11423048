class Appointment {
  final int id;
  final String diagnosis;
  final String prescription;
  final String doctorName;
  final String date;
  final String paymentStatus; // 1. Sudah ada, bagus!

  Appointment({
    required this.id,
    required this.diagnosis,
    required this.prescription,
    required this.doctorName,
    required this.date,
    required this.paymentStatus, // 2. Sudah ada, bagus!
  });

  factory Appointment.fromJson(Map<String, dynamic> json) {
    // Parsing ID dengan aman
    int parsedId = 0;
    if (json['id'] != null) {
      parsedId = int.tryParse(json['id'].toString()) ?? 0;
    }

    // Ambil data diagnosa
    String diagnosisText =
        json['diagnosis'] ?? json['notes'] ?? 'Tidak ada diagnosa';

    // Ambil Nama Dokter
    String doctor = 'Dokter Umum';
    if (json['doctorName'] != null) {
      doctor = json['doctorName'].toString();
    } else if (json['doctor'] != null && json['doctor']['name'] != null) {
      doctor = json['doctor']['name'].toString();
    }

    // Ambil Tanggal
    String formattedDate = '-';
    if (json['date'] != null) {
      formattedDate = json['date'].toString();
    } else if (json['updated_at'] != null) {
      formattedDate = json['updated_at'].toString().split(' ')[0];
    }

    // --- PERBAIKAN DI SINI ---
    return Appointment(
      id: parsedId,
      diagnosis: diagnosisText,
      prescription: json['prescription'] ?? 'Diberikan obat sesuai diagnosa',
      doctorName: doctor,
      date: formattedDate,
      paymentStatus:
          json['payment_status'] ?? 'unpaid', // <-- WAJIB DITAMBAHKAN DI SINI
    );
  }
}
