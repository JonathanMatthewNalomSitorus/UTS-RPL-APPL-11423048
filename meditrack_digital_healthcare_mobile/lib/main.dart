import 'package:flutter/material.dart';
import 'services/api_service.dart';
import 'pages/history_page.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'MediTrack Mobile',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.teal),
        useMaterial3: true,
      ),
      home: const LoginPage(),
    );
  }
}

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  bool _isLoading = false;

  void _handleLogin() async {
    String email = _emailController.text.trim();
    String password = _passwordController.text.trim();

    print("DEBUG: Email = $email");

    // ✅ VALIDASI INPUT
    if (email.isEmpty || password.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Email & Password wajib diisi")),
      );
      return;
    }

    setState(() => _isLoading = true);

    try {
      var result = await ApiService().login(email, password);

      print("DEBUG RESULT: $result");

      if (!mounted) return;
      setState(() => _isLoading = false);

      if (result != null && result['token'] != null) {
        print("LOGIN BERHASIL");

        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => HistoryPage(token: result['token']),
          ),
        );
      } else {
        print("LOGIN GAGAL");

        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Login gagal / user tidak ditemukan")),
        );
      }
    } catch (e) {
      if (!mounted) return;
      setState(() => _isLoading = false);

      print("ERROR LOGIN: $e");

      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Terjadi error koneksi")),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("MediTrack Login")),
      body: Padding(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.local_hospital, size: 80, color: Colors.teal),
            const SizedBox(height: 20),
            TextField(
              controller: _emailController,
              decoration: const InputDecoration(
                labelText: "Email Pasien",
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 15),
            TextField(
              controller: _passwordController,
              obscureText: true,
              decoration: const InputDecoration(
                labelText: "Password",
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 25),
            _isLoading
                ? const CircularProgressIndicator()
                : ElevatedButton(
                    onPressed: _handleLogin,
                    child: const Text("MASUK"),
                  )
          ],
        ),
      ),
    );
  }
}
