<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Medis - MediTrack</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background-color: #f8fafc;
            margin: 0;
            display: flex;
        }

        .sidebar {
            width: 260px;
            background: #1e293b;
            color: white;
            min-height: 100vh;
            padding: 20px;
            position: fixed;
        }

        .sidebar h2 {
            color: #38bdf8;
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #334155;
            padding-bottom: 10px;
        }

        .sidebar a {
            display: block;
            color: #94a3b8;
            text-decoration: none;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #334155;
            color: white;
        }

        .sidebar a.active {
            background: #38bdf8;
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
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            text-align: left;
            padding: 12px;
            background: #f1f5f9;
            color: #475569;
            font-size: 12px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            vertical-align: top;
        }

        .search-box {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }

        .btn-logout {
            background: #ef4444;
            color: white;
            padding: 8px 16px;
        }

        .search-box input {
            padding: 8px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            width: 300px;
        }

        .btn-search {
            background: #1e293b;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>MediTrack Dr.</h2>
        <a href="{{ route('appointments.index') }}">Antrean Pasien</a>
        <a href="{{ route('doctor.history') }}" class="active">Riwayat Medis (EHR)</a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-logout">Logout</button>
        </form>
    </div>

    <div class="main-content">
        <h1>Riwayat Kesehatan Elektronik (EHR)</h1>

        <div class="card">
            <form action="{{ route('doctor.history') }}" method="GET" class="search-box">
                <input type="text" name="search" placeholder="Cari Nama Pasien..." value="{{ request('search') }}">
                <button type="submit" class="btn-search">Cari Riwayat</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Pasien</th>
                        <th>Diagnosa (History)</th>
                        <th>Resep (Prescription)</th>
                        <th>Hasil Lab (Lab Result)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $h)
                    <tr>
                        <td style="white-space: nowrap;">{{ $h->updated_at->format('d M Y') }}</td>
                        <td><strong>{{ $h->patient->name }}</strong></td>
                        <td>{{ $h->diagnosis ?? '-' }}</td>
                        <td><code style="color: #059669;">{{ $h->prescription ?? '-' }}</code></td>
                        <td>
                            @if($h->lab_results)
                            <span style="color: #2563eb;">{{ $h->lab_results }}</span>
                            @else
                            <span style="color: #94a3b8;">Tidak ada data lab</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 30px; color: #94a3b8;">Belum ada riwayat medis tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>