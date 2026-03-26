import 'package:flutter/material.dart';
import '../services/api_service.dart';

class BookingPage extends StatefulWidget {
  @override
  _BookingPageState createState() => _BookingPageState();
}

class _BookingPageState extends State<BookingPage> {
  DateTime selectedDate = DateTime.now();

  void _handleBooking() async {
    // Kita pakai Dokter ID 1 sebagai contoh
    bool success =
        await ApiService().createAppointment(1, selectedDate.toString());

    if (success) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Janji Temu Berhasil Dibuat!")),
      );
      Navigator.pop(context); // Kembali ke Home/Riwayat
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Gagal membuat janji temu")),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar:
          AppBar(title: Text("Buat Janji Temu"), backgroundColor: Colors.teal),
      body: Padding(
        padding: EdgeInsets.all(20),
        child: Column(
          children: [
            Text("Pilih Tanggal Pemeriksaan:", style: TextStyle(fontSize: 16)),
            CalendarDatePicker(
              initialDate: selectedDate,
              firstDate: DateTime.now(),
              lastDate: DateTime(2026, 12, 31),
              onDateChanged: (date) => setState(() => selectedDate = date),
            ),
            SizedBox(height: 20),
            ElevatedButton(
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.teal,
                minimumSize: Size(double.infinity, 50),
              ),
              onPressed: _handleBooking,
              child: Text("KONFIRMASI JADWAL",
                  style: TextStyle(color: Colors.white)),
            ),
          ],
        ),
      ),
    );
  }
}
