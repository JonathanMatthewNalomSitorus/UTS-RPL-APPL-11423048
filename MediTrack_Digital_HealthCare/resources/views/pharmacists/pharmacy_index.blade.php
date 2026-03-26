<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Antrean Resep - MediTrack</title>
    <style>
        /* CSS yang sama dengan kode asli kamu */
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background-color: #fdfefd;
            margin: 0;
            display: flex;
        }

        .sidebar {
            width: 260px;
            background: #064e3b;
            color: white;
            min-height: 100vh;
            padding: 20px;
            position: fixed;
        }

        .sidebar h2 {
            color: #34d399;
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #065f46;
            padding-bottom: 10px;
        }

        .sidebar a {
            display: block;
            color: #a7f3d0;
            text-decoration: none;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        .sidebar a.active {
            background: #059669;
            color: white;
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #eee;
        }

        th {
            text-align: left;
            padding: 15px;
            background: #f8fafc;
            color: #065f46;
            font-size: 13px;
            text-transform: uppercase;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f0fdf4;
            color: #064e3b;
        }

        .btn-finish {
            background: #10b981;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>MediTrack Apt.</h2>
        <a href="{{ route('pharmacy.index') }}" class="active">Antrean Resep</a>
        <a href="{{ route('pharmacy.stock') }}">Stok Obat (EHR)</a>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <form action="{{ route('logout') }}" method="POST">@csrf <button type="submit" style="background:#ef4444; color:white; border:none; padding:8px 16px; border-radius:6px; cursor:pointer;">Logout</button></form>
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
        @endif

        <div class="card">
            <h3>Daftar Resep Dokter (Real-time)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Isi Resep</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $app)
                    <tr>
                        <td><strong>{{ $app->patient->name }}</strong></td>
                        <td>Dr. {{ $app->doctor->name }}</td>
                        <td><code style="color: #059669; font-weight: bold; background:#f0fdf4; padding:4px 8px; border-radius:4px;">{{ $app->prescription }}</code></td>
                        <td>
                            <form action="{{ route('pharmacy.dispense', $app->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-finish">Siapkan & Serahkan Obat</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" align="center" style="padding: 40px; color: #94a3b8;">Tidak ada antrean resep saat ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>