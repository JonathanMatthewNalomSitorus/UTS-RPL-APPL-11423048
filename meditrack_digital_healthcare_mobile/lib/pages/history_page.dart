import 'package:flutter/material.dart';
import '../models/appointment.dart';
import '../services/api_service.dart';
import 'booking_page.dart'; // <--- PASTIKAN IMPORT INI ADA

class HistoryPage extends StatelessWidget {
  final String token;
  HistoryPage({required this.token});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Riwayat Medis Saya"),
        backgroundColor: Colors.teal,
        elevation: 0,
      ),
      // Tombol Tambah Janji Temu diletakkan di sini
      floatingActionButton: FloatingActionButton(
        onPressed: () async {
          // Tunggu sampai user selesai booking di halaman sebelah
          await Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => BookingPage()),
          );
          // Begitu kembali ke sini, kita paksa refresh data
          (context as Element).markNeedsBuild();
        },
        backgroundColor: Colors.teal,
        child: Icon(Icons.add, color: Colors.white),
      ),
      body: FutureBuilder<List<dynamic>>(
        future: ApiService().getHistory(token),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return Center(child: CircularProgressIndicator(color: Colors.teal));
          }

          if (snapshot.hasError) {
            return Center(
              child: Padding(
                padding: const EdgeInsets.all(20.0),
                child: Text(
                  "Gagal terhubung ke server.\nCek koneksi ke http://10.98.200.181:8000",
                  textAlign: TextAlign.center,
                  style: TextStyle(color: Colors.red),
                ),
              ),
            );
          }

          if (!snapshot.hasData || snapshot.data!.isEmpty) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.history, size: 50, color: Colors.grey),
                  SizedBox(height: 10),
                  Text("Belum ada riwayat pemeriksaan."),
                  TextButton(
                    onPressed: () => (context as Element).markNeedsBuild(),
                    child: Text("Coba Lagi"),
                  )
                ],
              ),
            );
          }

          return ListView.builder(
            padding: EdgeInsets.symmetric(vertical: 10),
            itemCount: snapshot.data!.length,
            itemBuilder: (context, index) {
              try {
                var data = Appointment.fromJson(snapshot.data![index]);
                return Card(
                  margin: EdgeInsets.symmetric(horizontal: 15, vertical: 8),
                  elevation: 4,
                  shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12)),
                  child: ListTile(
                    contentPadding: EdgeInsets.all(15),
                    title: Text(
                      "Diagnosa: ${data.diagnosis}",
                      style: TextStyle(fontWeight: FontWeight.bold),
                    ),
                    subtitle: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        SizedBox(height: 8),
                        Text("Dokter: ${data.doctorName}"),
                        SizedBox(height: 4),
                        Text(
                          "Resep: ${data.prescription}",
                          style: TextStyle(
                              color: Colors.green, fontWeight: FontWeight.w600),
                        ),
                        SizedBox(height: 8),
                        Divider(),
                        Text(
                          "Tanggal: ${data.date}",
                          style: TextStyle(fontSize: 12, color: Colors.grey),
                        ),
                      ],
                    ),
                    trailing: Icon(Icons.medical_services, color: Colors.teal),
                  ),
                );
              } catch (e) {
                return SizedBox.shrink();
              }
            },
          );
        },
      ),
    );
  }
}
