<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Stok Obat - MediTrack</title>
    <style>
        /* Gunakan CSS yang sama agar konsisten */
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

        .badge-danger {
            background: #fee2e2;
            color: #b91c1c;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-success {
            color: #059669;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>MediTrack Apt.</h2>
        <a href="{{ route('pharmacy.index') }}">Antrean Resep</a>
        <a href="{{ route('pharmacy.stock') }}" class="active">Stok Obat (EHR)</a>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <form action="{{ route('logout') }}" method="POST">@csrf <button type="submit" style="background:#ef4444; color:white; border:none; padding:8px 16px; border-radius:6px; cursor:pointer;">Logout</button></form>
        </div>
    </div>

    <div class="main-content">
        <h1>Manajemen Stok Obat</h1>

        <div class="card">
            <h3>Inventaris & Kontrol Stok (Fitur d)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama Obat</th>
                        <th>Sisa Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicines as $med)
                    <tr>
                        <td>{{ $med->name }}</td>
                        <td style="font-weight: bold; font-size: 16px;">{{ $med->stock }}</td>
                        <td>
                            @if($med->stock < 10)
                                <span class="badge-danger">STOK KRITIS</span>
                                @else
                                <span class="badge-success">Tersedia</span>
                                @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>