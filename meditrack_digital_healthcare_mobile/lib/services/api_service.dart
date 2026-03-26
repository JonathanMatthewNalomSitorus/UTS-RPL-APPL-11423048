import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  // IP Wi-Fi kamu (Pastikan tidak berubah di ipconfig)
  final String baseUrl = "http://10.98.200.181:8000/api";

  Future<Map<String, dynamic>?> login(String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/login'),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: jsonEncode({
          'email': email,
          'password': password,
        }),
      );

      print("LOGIN STATUS: ${response.statusCode}");
      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      }
      return null;
    } catch (e) {
      print("ERROR API LOGIN: $e");
      return null;
    }
  }

  Future<List<dynamic>> getHistory(String token) async {
    try {
      print("--- MENGAKSES API JONATHAN ---");

      final response = await http.get(
        // Pastikan /get-history atau /get-history-debug (pilih salah satu yang ada di api.php)
        Uri.parse('http://10.98.200.181:8000/api/get-history-debug'),
        headers: {
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 10));

      print("STATUS: ${response.statusCode}");
      print("HASIL BODY: ${response.body}");

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      }
      return [];
    } catch (e) {
      print("ADA ERROR DI FLUTTER: $e");
      return [];
    }
  }

  Future<bool> createAppointment(int doctorId, String date) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/create-appointment'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'doctor_id': doctorId,
          'appointment_date': date,
        }),
      );

      print("BOOKING STATUS: ${response.statusCode}");
      print("BOOKING RESPONSE: ${response.body}");

      return response.statusCode == 201; // 201 artinya Created
    } catch (e) {
      print("ERROR BOOKING: $e");
      return false;
    }
  }
}
